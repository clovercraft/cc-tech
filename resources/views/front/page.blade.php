@extends('front.layout')

@section('body')
    <main class="page-body">
        <x-cms.page :page="$page" />
    </main>
@endsection
