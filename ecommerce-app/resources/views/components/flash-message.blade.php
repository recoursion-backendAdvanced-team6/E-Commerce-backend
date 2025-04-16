@if (session('success'))
    <div class="mb-4 p-4 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 rounded bg-red-100 text-red-800">
        {{ $message }}
    </div>
@endif