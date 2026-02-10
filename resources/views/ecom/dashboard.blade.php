<div>
    <main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </main>

    <script src="js/dashboard-ecom.js"></script>
</div>

<div id="deleteOptionContainer"
    class="hidden items-center gap-4 p-3 mb-4 bg-gradient-to-r 
    from-red-50 to-orange-50 border border-red-200 rounded-lg">
    <div class="text-sm font-medium text-red-600">
        <span id="selectedCount">0</span> item(s) selected
    </div>
    <button id="deleteSelectedBtn" disabled
        class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-medium rounded-lg hover:from-red-700 hover:to-red-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:shadow-md">
        Delete Selected
    </button>
    <button id="cancelDeleteBtn" class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
        Cancel
    </button>
</div>

<div
    class="absolute right-0 top-full mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden transition-all duration-200 origin-top-right">
    <a href="#"
        class="view-option flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors duration-150 border-b border-gray-100">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <span class="text-sm">View Details</span>
    </a>
    <a href="#"
        class="edit-option flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors duration-150 border-b border-gray-100">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        <span class="text-sm">Edit</span>
    </a>
    <a href="#"
        class="delete-option flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span class="text-sm">Delete</span>
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteOptionContainer = document.getElementById('deleteOptionContainer');
        const selectedCountElement = document.getElementById('selectedCount');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const checkboxes = document.querySelectorAll('.history-checkbox');
        const optionsButtons = document.querySelectorAll('.options-btn');
        const historyFilter = document.getElementById('history-filter');
        const refreshBtn = document.getElementById('refresh-btn');
        const refreshIcon = document.getElementById('refresh-icon');
        const historyRows = document.querySelectorAll('.history-row');

        let selectedCount = 0;

        function updateSelectedCount() {
            selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            selectedCountElement.textContent = selectedCount;

            if (selectedCount > 0) {
                deleteOptionContainer.classList.remove('hidden');
                deleteOptionContainer.classList.add('flex');
                deleteOptionContainer.style.animation = 'slideIn 0.3s ease-out';
            } else {
                deleteOptionContainer.classList.add('hidden');
                deleteOptionContainer.classList.remove('flex');
            }

            deleteSelectedBtn.disabled = selectedCount === 0;
        }

        const style = document.createElement('style');
        style.textContent = `
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-10px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    .animate-slideIn {
                        animation: slideIn 0.3s ease-out;
                    }
                `;
        document.head.appendChild(style);

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        deleteSelectedBtn.addEventListener('click', function() {
            if (selectedCount === 0) return;

            if (confirm(`Are you sure you want to delete ${selectedCount} item(s)?`)) {
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const row = checkbox.closest('.history-row');
                        row.classList.add('opacity-0', '-translate-x-24');
                        row.style.transition = 'all 0.3s ease';

                        setTimeout(() => {
                            row.classList.add('hidden');
                            updateSelectedCount();
                        }, 300);
                    }
                });

                setTimeout(() => {
                    deleteOptionContainer.classList.add('hidden');
                    deleteOptionContainer.classList.remove('flex');
                }, 350);
            }
        });

        cancelDeleteBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            deleteOptionContainer.classList.add('hidden');
            deleteOptionContainer.classList.remove('flex');
            updateSelectedCount();
        });

        let activeDropdown = null;

        function closeAllDropdowns() {
            document.querySelectorAll('.absolute.right-0.top-full').forEach(dropdown => {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('block');
            });
            activeDropdown = null;
        }

        optionsButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;

                if (dropdown === activeDropdown) {
                    dropdown.classList.add('hidden');
                    dropdown.classList.remove('block');
                    activeDropdown = null;
                } else {
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('block');
                    activeDropdown = dropdown;
                }
            });

            const dropdown = button.nextElementSibling;
            const deleteOption = dropdown.querySelector('.delete-option');
            const viewOption = dropdown.querySelector('.view-option');
            const editOption = dropdown.querySelector('.edit-option');

            deleteOption.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const row = this.closest('.history-row');
                const personName = row.querySelector('.text-sm.font-medium').textContent.trim();

                if (confirm(`Are you sure you want to delete "${personName}"?`)) {
                    row.classList.add('opacity-0', '-translate-x-24');
                    row.style.transition = 'all 0.3s ease';

                    setTimeout(() => {
                        row.classList.add('hidden');
                        closeAllDropdowns();
                        updateSelectedCount();
                    }, 300);
                }
            });

            viewOption.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const row = this.closest('.history-row');
                const personName = row.querySelector('.text-sm.font-medium').textContent.trim();
                alert(`Viewing details for: ${personName}`);
                closeAllDropdowns();
            });

            editOption.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const row = this.closest('.history-row');
                const personName = row.querySelector('.text-sm.font-medium').textContent.trim();
                alert(`Editing: ${personName}`);
                closeAllDropdowns();
            });
        });

        document.addEventListener('click', closeAllDropdowns);

        historyFilter.addEventListener('change', function() {
            const filterValue = this.value;

            historyRows.forEach(row => {
                const rowType = row.getAttribute('data-type');

                if (filterValue === 'all' || rowType === filterValue) {
                    row.classList.remove('hidden');
                    row.classList.add('table-row');
                    row.style.animation = 'fadeIn 0.3s ease-out';
                } else {
                    row.classList.add('hidden');
                    row.classList.remove('table-row');
                }
            });

            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });

        refreshBtn.addEventListener('click', function() {
            refreshIcon.classList.add('animate-spin');

            setTimeout(() => {
                historyFilter.value = 'all';

                historyRows.forEach(row => {
                    row.classList.remove('hidden');
                    row.classList.add('table-row');
                    row.classList.remove('opacity-0', '-translate-x-24');
                    row.style.animation = 'fadeIn 0.5s ease-out';
                });

                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateSelectedCount();

                refreshIcon.classList.remove('animate-spin');

                showToast('History refreshed successfully!');
            }, 1000);
        });

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className =
                'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slideIn';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        updateSelectedCount();
    });
</script>
