@props([
	'name',
	'label',
	'type' => 'text',
])

<div x-data>
	<label for="{{ $name }}" class="sr-only">{{ $label }}</label>
	<input
		type="{{ $type }}"
		id="{{ $name }}"
		name="{{ $name }}"
		placeholder="{{ $label }}"
		@error($name) aria-invalid="true" @enderror
		x-on:focus="$el.classList.remove('outline-2', '-outline-offset-2', 'outline-red-400'); $el.removeAttribute('aria-invalid')"
		{{ $attributes->class([
			'w-full bg-white px-15 py-10 text-xs md:text-sm text-ink placeholder:text-ink focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-ink/40',
			'outline-2 -outline-offset-2 outline-red-400' => $errors->has($name),
		]) }}
	>
</div>
