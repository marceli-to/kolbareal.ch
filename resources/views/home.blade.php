<x-layouts.app>
	{{-- 1 — Header visual --}}
	<x-layouts.header />

	<main class="isolate">
		{{-- 2 — Intro text --}}
		<x-layouts.section>
			<h1 class="text-balance text-2xl md:text-3xl font-bold tracking-tight uppercase">
				Kolb Areal – Mein Zuhause
			</h1>
			<h2 class="text-xl md:text-2xl font-bold tracking-tight uppercase mb-20 md:mb-24">
				Erstbezug ab Frühling 2027
			</h2>

			<div class="space-y-20 text-sm md:text-lg text-pretty max-w-prose">
				<p>
					An der Alte Mühlackerstrasse in Zürich-Affoltern entstehen 29 moderne und
					grosszügig konzipierte Mietwohnungen mit 1.5 bis 5.5 Zimmern. Die hochwertigen
					Wohnungen bieten zeitgemässen Wohnkomfort und attraktive Grundrisse für Singles,
					Paare und Familien.
				</p>
				<p>
					Gerne senden wir Ihnen weitere Informationen zu, sobald die Vermietung startet.
					Bitte füllen Sie hierzu das Kontaktformular aus.
				</p>
			</div>
		</x-layouts.section>

		{{-- 3 — Contact form --}}
		<x-layouts.section :container="false" id="kontakt" class="bg-sage">
			<livewire:contact-form />
		</x-layouts.section>

		{{-- 4 — Map --}}
		<x-layouts.section :container="false" class="relative">
			<div id="map" class="h-[420px] w-full bg-ink/10 md:h-[560px]"></div>
		</x-layouts.section>
	</main>

	{{-- 5 — Footer --}}
	<x-layouts.footer />
</x-layouts.app>
