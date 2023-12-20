@extends('front.layout')

@section('body')
    <main class="page-body d-flex flex-column container-xl">
        <aside class="page-title container-xl">
            <h1 class='title'>Welcome to Clovercraft!</h1>
            <h2 class='subtitle'>An open and supportive gaming community</h2>
        </aside>
        <x-cms-banner />
        <section class="page-content container-xl">
            <p>The home page will have static content, which we'll write out in this template file.</p>
        </section>
    </main>
@endsection
