<div {{ $attributes->merge(['class' => 'alert alert-success shadow-default border border-base-300']) }}>
	<div class="flex gap-3">
		<div class="items-center flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
