<div {{ $attributes->merge(['class' => 'alert alert-warning shadow-default border border-base-300']) }}>
	<div class="flex gap-3">
		<div class="items-center flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <div class="flex flex-col">
            @isset($header)
                <div class="text-xl font-semibold tracking-wide">
                    {{ $header }}
                </div>
            @endisset
            <div>
                {{ $message }}
            </div>
        </div>
	</div>
</div>
