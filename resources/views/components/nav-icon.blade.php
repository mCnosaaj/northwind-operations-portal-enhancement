@props(['name'])

<span class="nav-icon" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        @switch($name)
            @case('dashboard')
                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                <rect x="3" y="14" width="7" height="7" rx="1.5" />
                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                @break

            @case('customers')
                <circle cx="9" cy="7" r="4" />
                <path d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                <path d="M16 3.2a4 4 0 0 1 0 7.6" />
                <path d="M18 15a4 4 0 0 1 3 3.9V21" />
                @break

            @case('products')
                <path d="m12 3 9 5-9 5-9-5 9-5Z" />
                <path d="m3 8 9 5 9-5" />
                <path d="M3 8v8l9 5 9-5V8" />
                <path d="M12 13v8" />
                @break

            @case('orders')
                <path d="M6 2h9l3 3v17H6z" />
                <path d="M14 2v4h4" />
                <path d="M9 11h6M9 15h6M9 19h4" />
                @break

            @case('sales')
                <path d="M3 3v18h18" />
                <path d="m7 15 4-4 3 3 5-6" />
                <path d="M16 8h3v3" />
                @break

            @case('inventory')
                <path d="m3 10 9-6 9 6" />
                <path d="M5 9v11h14V9" />
                <path d="M8 20v-6h8v6" />
                <path d="M9 10h.01M12 10h.01M15 10h.01" />
                @break
        @endswitch
    </svg>
</span>
