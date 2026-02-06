<div>
    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

    <script>
        const btn = document.getElementById('profileButton');
        const dropdown = document.getElementById('profileDropdown');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            dropdown.classList.toggle('animate-fade-in');
        });

        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</div>



