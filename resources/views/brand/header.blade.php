@push('head')
    <link href="/logo-small.png" id="favicon" rel="icon">
@endpush

<div class="h2 d-flex align-items-center fix-header">
    {{-- @auth
        <x-orchid-icon path="bs.house" class="d-inline d-xl-none" />
    @endauth --}}
    <img class="img-fluid brand-logo" alt="Clovercraft" src="/img/logo.png" />
    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        Clovercraft
        <small class="align-top opacity mx-2">HUB</small>
    </p>
</div>
<style>
    .brand-logo {
        min-height: 25px;
        max-height: 75px;
        height: 100%;
    }
</style>
