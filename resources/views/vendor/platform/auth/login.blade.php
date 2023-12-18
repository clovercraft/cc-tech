@extends('platform::auth')
@section('title', __('Sign in to your account'))

@section('content')
    <h1 class="h4 text-black mb-2">{{ __('Sign in to your account') }}</h1>
    <p class="text-black mb-4">{{ __('If you have not signed in before, Discord sign-in will create a new account.') }}
    </p>
    <a class="btn btn-discord" href="{{ route('discord.authorize-member') }}">
        <x-orchid-icon path="bs.discord" width="1.5em" height="1.5em" class="mx-2 my-2" />
        Login with Discord
    </a>
    <hr>

    <form class="m-t-md" role="form" method="POST" data-controller="form"
        data-form-need-prevents-form-abandonment-value="false" data-action="form#submit"
        action="{{ route('platform.login.auth') }}">
        @csrf

        @includeWhen($isLockUser, 'platform::auth.lockme')
        @includeWhen(!$isLockUser, 'platform::auth.signin')
    </form>
    <style>
        a.btn-discord {
            color: #fff;
            background: #5865F2;
        }
    </style>
@endsection
