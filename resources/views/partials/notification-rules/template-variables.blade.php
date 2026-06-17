<aside class="card app-surface h-100" aria-labelledby="template-variables-title">
    <div class="card-body p-3 p-md-4">
        <h2 id="template-variables-title" class="h6 mb-2">Supported template variables</h2>
        <p class="text-muted small mb-3">Use these placeholders in subject and message templates.</p>

        <ul class="list-group list-group-flush small">
            <li class="list-group-item px-0"><code>@{{ ticket.number }}</code></li>
            <li class="list-group-item px-0"><code>@{{ ticket.title }}</code></li>
            <li class="list-group-item px-0"><code>@{{ ticket.status }}</code></li>
            <li class="list-group-item px-0"><code>@{{ requester.name }}</code></li>
            <li class="list-group-item px-0"><code>@{{ reviewer.name }}</code></li>
            <li class="list-group-item px-0"><code>@{{ recipient.name }}</code></li>
            <li class="list-group-item px-0"><code>@{{ service_item.name }}</code></li>
        </ul>
    </div>
</aside>

