@extends('front.layout')

@section('body')
    <x-cms-banner :page="$page" />
    <x-cms.page :page="$page" />
@endsection
