<section class="page-title container-xl">
    <h1 class="title">{{ $title }}</h1>
    @if ($hasSubtitle)
        <h2 class="subtitle">{{ $subtitle }}</h2>
    @endif
</section>
<x-cms-banner :page="$page" />
<section class="page-content" class="container-xl">
    {!! $content !!}
</section>
