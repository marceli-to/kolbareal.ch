<?php

use App\Models\Registration;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

new class extends Component
{
	/** @var array<int,string> */
	public array $apartment_sizes = [];

	public string $first_name = '';

	public string $last_name = '';

	public string $street = '';

	public string $zip_city = '';

	public string $email = '';

	public string $phone = '';

	public bool $privacy = false;

	public string $turnstileToken = '';

	public bool $submitted = false;

	/** Apartment sizes offered, in display order. */
	public array $sizes = ['1.5', '2.5', '3.5', '4.5', '5.5'];

	/** @return array<string,mixed> */
	protected function rules(): array
	{
		return [
			'apartment_sizes' => ['required', 'array', 'min:1'],
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
			'street' => ['required', 'string', 'max:255'],
			'zip_city' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255'],
			'phone' => ['nullable', 'string', 'max:255'],
			'privacy' => ['accepted'],
		];
	}

	public function submit(): void
	{
		$this->validate();

		if (! $this->verifyTurnstile()) {
			$this->addError('turnstileToken', __('Die Spam-Prüfung ist fehlgeschlagen. Bitte versuchen Sie es erneut.'));
			$this->dispatch('turnstile:reset');

			return;
		}

		Registration::create([
			'apartment_sizes' => $this->apartment_sizes,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'street' => $this->street,
			'zip_city' => $this->zip_city,
			'email' => $this->email,
			'phone' => $this->phone ?: null,
		]);

		$this->reset([
			'apartment_sizes', 'first_name', 'last_name',
			'street', 'zip_city', 'email', 'phone', 'privacy', 'turnstileToken',
		]);

		$this->submitted = true;
	}

	private function verifyTurnstile(): bool
	{
		$secret = config('services.turnstile.secret_key');

		// No secret configured (e.g. local dev before keys arrive) → skip the check.
		if (blank($secret)) {
			return true;
		}

		if (blank($this->turnstileToken)) {
			return false;
		}

		$response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
			'secret' => $secret,
			'response' => $this->turnstileToken,
			'remoteip' => request()->ip(),
		]);

		return $response->json('success') === true;
	}
}; ?>

<div class="mx-auto w-full max-w-6xl px-24 py-32 md:py-64">
	@if ($submitted)
		<div
			class="flex flex-col gap-24 text-white"
			role="status"
			aria-live="polite">
			<h2 class="text-xl md:text-2xl font-bold uppercase tracking-tight text-white">
				Wir haben Ihre Anmeldung erhalten
			</h2>
			<p class="text-pretty text-sm text-white">
				Vielen Dank für Ihr Interesse. Wir melden uns, sobald die Vermietung startet.
			</p>
		</div>
	@else
		<h2 class="text-xl md:text-2xl font-bold uppercase tracking-tight text-white">
      Kontaktformular
    </h2>

		<form wire:submit="submit" class="mt-16 flex flex-col gap-24">
			{{-- Apartment size selection --}}
			<fieldset>
				<legend class="mb-16 text-lg md:text-xl font-bold text-white">
          Wohnungsgrösse auswählen
        </legend>
				<div
					x-data
					x-on:change="$el.querySelectorAll('input').forEach((c) => c.classList.remove('outline-2', '-outline-offset-2', 'outline-red-400'))"
					class="grid grid-cols-1 gap-x-40 gap-y-12 sm:grid-flow-col sm:grid-cols-2 sm:grid-rows-3 max-w-2xl">
					@foreach ($sizes as $size)
						<label for="size-{{ \Illuminate\Support\Str::slug($size) }}" class="flex cursor-pointer items-center gap-12 text-xs md:text-sm text-white">
							<span class="group inline-grid size-20 grid-cols-1 sm:size-16">
								<input
									id="size-{{ \Illuminate\Support\Str::slug($size) }}"
									type="checkbox"
									value="{{ $size }}"
									wire:model="apartment_sizes"
									@class([
										'col-start-1 row-start-1 appearance-none rounded-none border-0 bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white forced-colors:appearance-auto',
										'outline-2 -outline-offset-2 outline-red-400' => $errors->has('apartment_sizes'),
									])>
								<svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-7/8 self-center justify-self-center stroke-ink">
									<path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-not-has-checked:opacity-0" />
								</svg>
							</span>
							<span>{{ $size }}-Zimmerwohnung</span>
						</label>
					@endforeach
				</div>
			</fieldset>

			{{-- Name row --}}
			<div class="grid grid-cols-1 gap-x-20 gap-y-20 sm:grid-cols-2">
				<x-form.input name="first_name" label="Vorname*" wire:model="first_name" autocomplete="given-name" />
				<x-form.input name="last_name" label="Name*" wire:model="last_name" autocomplete="family-name" />
			</div>

			{{-- Address row --}}
			<div class="grid grid-cols-1 gap-x-20 gap-y-20 sm:grid-cols-2">
				<x-form.input name="street" label="Strasse/Nr*" wire:model="street" autocomplete="street-address" />
				<x-form.input name="zip_city" label="PLZ/Ort*" wire:model="zip_city" autocomplete="postal-code" />
			</div>

			{{-- Contact row --}}
			<div class="grid grid-cols-1 gap-x-20 gap-y-20 sm:grid-cols-2">
				<x-form.input name="email" label="E-Mail*" type="email" wire:model="email" autocomplete="email" />
				<x-form.input name="phone" label="Telefon" type="tel" wire:model="phone" autocomplete="tel" />
			</div>

			{{-- Privacy consent --}}
			<label for="privacy" x-data class="flex cursor-pointer items-start gap-12 text-xs md:text-sm text-white">
				<span class="flex h-lh items-center text-sm">
					<span class="group inline-grid size-20 grid-cols-1 sm:size-16">
						<input
							id="privacy"
							type="checkbox"
							name="privacy"
							wire:model="privacy"
							x-on:change="$el.classList.remove('outline-2', '-outline-offset-2', 'outline-red-400')"
							@class([
								'col-start-1 row-start-1 appearance-none rounded-none border-0 bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white forced-colors:appearance-auto',
								'outline-2 -outline-offset-2 outline-red-400' => $errors->has('privacy'),
							])
						>
						<svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-7/8 self-center justify-self-center stroke-ink">
							<path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-not-has-checked:opacity-0" />
						</svg>
					</span>
				</span>
				<span>
					Ich habe die <a href="#" class="hover:underline decoration-1 underline-offset-2">Datenschutzerklärung</a> gelesen und akzeptiere diese.
				</span>
			</label>

			{{-- Cloudflare Turnstile runs invisibly (rendered after the form); only its error surfaces here --}}
			@error('turnstileToken')
				<p class="text-xs md:text-sm text-white underline">{{ $message }}</p>
			@enderror

			<div>
				<button
					type="submit"
					class="cursor-pointer inline-flex items-center justify-center bg-white px-15 py-10 text-sm md:text-lg font-bold uppercase tracking-wide text-sage transition hover:bg-shell disabled:opacity-60"
					wire:loading.attr="disabled"
					wire:target="submit"
				>
					<span wire:loading.remove wire:target="submit">Absenden</span>
					<span wire:loading wire:target="submit">Wird gesendet…</span>
				</button>
			</div>
		</form>

		{{-- Cloudflare Turnstile (Invisible) — no visible UI; runs in the background and feeds the token to Livewire --}}
		@if (filled(config('services.turnstile.site_key')))
			<div
				wire:ignore
				x-data
				x-on:turnstile:reset.window="window.turnstile && $refs.widget.dataset.widgetId && window.turnstile.reset($refs.widget.dataset.widgetId)"
			>
				<div
					x-ref="widget"
					x-init="
						const render = () => {
							$refs.widget.dataset.widgetId = window.turnstile.render($refs.widget, {
								sitekey: '{{ config('services.turnstile.site_key') }}',
								callback: (token) => $wire.set('turnstileToken', token),
								'expired-callback': () => $wire.set('turnstileToken', ''),
							});
						};
						window.turnstile ? render() : document.addEventListener('turnstile:loaded', render, { once: true });
					"
				></div>
			</div>
		@endif
	@endif
</div>
