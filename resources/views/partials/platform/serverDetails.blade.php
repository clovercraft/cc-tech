@if (isset($server))
    <div class="container px-4 bg-white rounded-top shadow-sm mb-4 rounded-bottom">
        <table class="table">
            <tbody>
                <tr>
                    <td><strong>Type</strong></td>
                    <td>{{ $server->type }}</td>
                </tr>
                <tr>
                    <td><strong>Version</strong></td>
                    <td>{{ $server->current_version }}</td>
                </tr>
                <tr>
                    <td><strong>IP</strong></td>
                    <td>{{ $server->ip }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <p>Data not loaded.</p>
@endif
