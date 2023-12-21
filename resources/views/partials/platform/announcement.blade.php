<div class="container mb-2 mt-2">
    <p>Published on: {{ $announcement->updated_at->format('d M, Y') }}</p>
</div>
<div class="container p-4 bg-white rounded-top shadow-sm mb-4 mt-4 rounded-bottom">
    {!! $announcement->content !!}
</div>
