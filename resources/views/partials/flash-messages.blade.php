@if (session('success'))
    <div class="alert alert-success app-surface py-2 px-3 mb-3" role="status">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger app-surface py-2 px-3 mb-3" role="alert">
        <p class="fw-semibold mb-2">Please correct the following errors:</p>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

