<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-100">
    @isset($logo)
        <div>
            {{ $logo }}
        </div>
    @endisset

    @isset($title)
        <div class="font-semibold tracking-wider text-lg">
            {{ $title }}
        </div>
    @endisset

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-base-200 shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
