<div class="flex justify-center my-2">
    <div class="dropdown dropdown-top">
        <label tabindex="0" class="btn gap-1 normal-case btn-ghost">
            <span class="flag-icon flag-icon-{{Config::get('languages')[App::getLocale()]['flag-icon']}}"></span>
            <span>{{ Config::get('languages')[App::getLocale()]['display'] }}</span>
            <svg width="12px" height="12px" class="ml-1 inline-block h-3 w-3 fill-current opacity-60 sm:inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2048 2048">
                <path d="M1799 349l242 241-1017 1017L7 590l242-241 775 775 775-775z"></path>
            </svg>
        </label>
        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
            @foreach (Config::get('languages') as $lang => $language)
                <li>
                    <a class="@if ($lang == App::getLocale()) active @endif" href="{{ route('lang.switch', $lang) }}">
                        <span class="flag-icon flag-icon-{{$language['flag-icon']}}"></span>
                        {{$language['display']}}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
