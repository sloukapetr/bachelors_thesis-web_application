<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline btn-error']) }}>
    {{ $slot }}
</button>
