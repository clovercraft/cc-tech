<main id="page-content" class="container">
    <h1 class="title">{{ $title }}</h1>
    @if ($hasSubtitle)
        <h2 class="subtitle">{{ $subtitle }}</h2>
    @endif
    {!! $content !!}
</main>
