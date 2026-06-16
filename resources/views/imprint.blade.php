<x-layouts.app title="Impressum – Kolbareal Affoltern">
	{{-- 1 — Header visual --}}
	<x-layouts.header />

	<main class="isolate">
		{{-- 2 — Content --}}
		<x-layouts.section>
			<h1 class="text-balance text-2xl md:text-3xl font-bold tracking-tight uppercase mb-20 md:mb-24">
				Impressum
			</h1>

			<div class="space-y-20 text-sm md:text-lg text-pretty max-w-prose">
				<div class="space-y-12">
					<h2 class="text-lg md:text-xl font-bold tracking-tight">Verantwortlich</h2>
					<p>
            Kolb Immobilien AG<br>
            Blumenfeldstrasse 85<br>
            8046 Zürich
					</p>
				</div>

				<div class="space-y-12">
					<h2 class="text-lg md:text-xl font-bold tracking-tight">Design und Entwicklung</h2>
					<p>
						Stoz Werbeagentur AG<br>
						Barzloostrasse 2<br>
						8330 Pfäffikon ZH<br>
						<a href="mailto:hello@stoz.ch" class="hover:underline decoration-1 underline-offset-2">hello@stoz.ch</a><br>
						<a href="https://www.stoz.ch" target="_blank" rel="noopener" class="hover:underline decoration-1 underline-offset-2">www.stoz.ch</a>
					</p>
				</div>

				<div class="space-y-12">
					<h2 class="text-lg md:text-xl font-bold tracking-tight">Programmierung</h2>
					<p>
						Marcel Stadelmann, Zürich<br>
						<a href="https://marceli.to" target="_blank" rel="noopener" class="hover:underline decoration-1 underline-offset-2">marceli.to</a>
					</p>
				</div>
			</div>
		</x-layouts.section>
	</main>

	{{-- 3 — Footer --}}
	<x-layouts.footer />
</x-layouts.app>
