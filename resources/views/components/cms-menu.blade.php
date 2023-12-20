<div class="container-fluid">
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-xl">
            <a class="navbar-brand" href="{{ route('front.home') }}">
                <img src="/img/logo.png" alt="Clovercraft">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach ($items as $item)
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ $item->route }}">{{ $item->label }}</a>
                        </li>
                    @endforeach
                </ul>
                <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('platform.login') }}">HUB Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
