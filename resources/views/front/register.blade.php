@extends('platform::auth')
@section('title', __('Sign in to your account'))

@section('content')
    <h1 class="h4 text-black mb-4">{{ __('Sign in to your account') }}</h1>

    <form class="m-t-md" role="form" method="POST" action="{{ route('front.submit_register') }}">
        @csrf

        <div class="mb-3">
            <p>To manage your whitelisted Minecraft accounts, please
        </div>
    </form>
@endsection
