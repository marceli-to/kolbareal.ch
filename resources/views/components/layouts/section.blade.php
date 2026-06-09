@props(['container' => true])

<section {{ $attributes }}>
	@if ($container)
		<div class="mx-auto w-full max-w-6xl px-24 py-32 md:py-64">
			{{ $slot }}
		</div>
	@else
		{{ $slot }}
	@endif
</section>
