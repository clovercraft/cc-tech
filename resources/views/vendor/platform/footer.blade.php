@guest
    <p class="small text-center mb-1 px-5">
        {{ __('The application code is published under the MIT license.') }}
    </p>

    <ul class="nav justify-content-center mb-5">
        <li class="nav-item"><a href="https://wiki.clovercraft.gg" class="nav-link px-2 text-muted">Wiki</a></li>
        <li class="nav-item"><a href="https://discord.gg/clovercraft" target="_blank"
                class="nav-link px-2 text-muted">Discord</a></li>
        <li class="nav-item"><a href="https://github.com/clovercraft" target="_blank"
                class="nav-link px-2 text-muted">GitHub</a></li>
    </ul>
@else
    <div class="text-center user-select-none my-4 d-none d-lg-block">
        <p class="small mb-0">
            {{ __('The application code is published under the MIT license.') }} 2016 - {{ date('Y') }}<br>
            <a href="http://orchid.software" target="_blank" rel="noopener">
                {{ __('Version') }}: {{ \Orchid\Platform\Dashboard::VERSION }}
            </a>
        </p>
    </div>
@endguest
