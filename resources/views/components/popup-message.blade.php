@if (session('success'))
    <div x-data="{ open: true }" x-show="open" x-init="setTimeout(() => open = false, 5000)"
            class="bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div x-data="{ open: true }" x-show="open" x-init="setTimeout(() => open = false, 5000)"
            class="bg-red-500 text-white py-2 px-4 rounded-lg shadow-lg">
        <p>{{ session('error') }}</p>
    </div>
@endif
