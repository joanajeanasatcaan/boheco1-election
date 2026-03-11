<div>
    {{-- Auth user data for JS schedule gate --}}
    <span id="auth-user" class="hidden"
        data-district="{{ auth()->user()->ecomProfile?->district ?? '' }}"
        data-name="{{ auth()->user()->name ?? '' }}">
    </span>

    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

    @vite('resources/js/ecom-dashboard.js')
</div>