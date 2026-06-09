<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Notifications\WeeklyRegistrationsExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_all_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Kolb Areal – Mein Zuhause')
            ->assertSee('Kontaktformular')
            ->assertSee('Erstbezug ab Frühling 2027');
    }

    public function test_it_stores_a_valid_registration(): void
    {
        config(['services.turnstile.secret_key' => 'test-secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true])]);

        Livewire::test('contact-form')
            ->set('apartment_sizes', ['2.5', '3.5'])
            ->set('first_name', 'Anna')
            ->set('last_name', 'Muster')
            ->set('street', 'Teststrasse 1')
            ->set('zip_city', '8048 Zürich')
            ->set('email', 'anna@example.com')
            ->set('phone', '044 123 45 67')
            ->set('privacy', true)
            ->set('turnstileToken', 'dummy-token')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertSame(1, Registration::count());

        $registration = Registration::first();
        $this->assertSame(['2.5', '3.5'], $registration->apartment_sizes);
        $this->assertSame('Anna Muster', $registration->fullName());
    }

    public function test_it_rejects_a_failed_turnstile_check(): void
    {
        config(['services.turnstile.secret_key' => 'test-secret']);
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

        Livewire::test('contact-form')
            ->set('apartment_sizes', ['2.5'])
            ->set('first_name', 'Anna')
            ->set('last_name', 'Muster')
            ->set('street', 'Teststrasse 1')
            ->set('zip_city', '8048 Zürich')
            ->set('email', 'anna@example.com')
            ->set('privacy', true)
            ->set('turnstileToken', 'bad-token')
            ->call('submit')
            ->assertHasErrors('turnstileToken')
            ->assertSet('submitted', false);

        $this->assertSame(0, Registration::count());
    }

    public function test_it_validates_required_fields(): void
    {
        Livewire::test('contact-form')
            ->call('submit')
            ->assertHasErrors([
                'apartment_sizes' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'street' => 'required',
                'zip_city' => 'required',
                'email' => 'required',
                'privacy' => 'accepted',
            ]);

        $this->assertSame(0, Registration::count());
    }

    public function test_it_exports_new_registrations_and_marks_them_exported(): void
    {
        Notification::fake();

        Registration::create([
            'apartment_sizes' => ['1.5'],
            'first_name' => 'Bea',
            'last_name' => 'Test',
            'street' => 'Weg 2',
            'zip_city' => '8048 Zürich',
            'email' => 'bea@example.com',
        ]);

        $this->artisan('registrations:export-weekly')->assertSuccessful();

        Notification::assertSentOnDemand(WeeklyRegistrationsExport::class);
        $this->assertSame(1, Registration::whereNotNull('exported_at')->count());

        // A second run should send an empty digest and not re-export.
        Notification::fake();
        $this->artisan('registrations:export-weekly')->assertSuccessful();
        Notification::assertSentOnDemand(
            WeeklyRegistrationsExport::class,
            fn ($notification) => $notification->registrations->isEmpty(),
        );
    }
}
