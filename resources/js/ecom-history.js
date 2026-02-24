document.addEventListener('DOMContentLoaded', function () {
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
    opacity: 0
    transform: translateY(-10px);
    }
    to {
    opacity: 1;
    transform: translateY(0);
    }
    }
    @keyframes fadeIn {
    from {
     opacity: 0;
    } to {
     opacity: 1;
    }
    }
    .animate-slideIn {
    animation: slideIn 0.3s ease-out;
    }
    .animate-spin {
    animation: spin 1s linear infinite;
    }
    @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
    }
    `;

    document.head.appendChild(style);

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    deleteSelectedBtn.addEventListener('click', function () {
        if (selectedCount === 0) return;

        if (confirm(`Are you sure you want to delete ${selectedCount} item(s)?`)) {
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const row = checkbox.closest('.history-row');
                    if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-100px)';
                    row.style.transition = 'all 0.3s ease';

                    setTimeout(() => {
                        row.classList.add('hidden');
                        updateSelectedCount();
                    }, 300);
                }
            }
            });

            setTimeout(() => {
                deleteOptionContainer.classList.add('hidden');
                deleteOptionContainer.classList.remove('flex');
            }, 350);
        }
    });

    cancelDeleteBtn.addEventListener('click', function () {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        deleteOptionContainer.classList.add('hidden');
        deleteOptionContainer.classList.remove('flex');
        updateSelectedCount();
    });

    let activeDropdown = null;

    function closeAllDropdowns() {
        document.querySelectorAll('.options-dropdown').forEach(dropdown => {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('block');
        });

        activeDropdown = null;
    }

    optionsButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const dropdown = this.nextElementSibling;

            if (!dropdown || !dropdown.classList.contains('options-dropdown')) {
                return;
            } 

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
        if (!dropdown) return;

        const deleteOption = dropdown.querySelector('.delete-option');
        if (!deleteOption) return;

        deleteOption.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const row = this.closest('.history-row');
            if (!row) return;

            const nameElement = row.querySelector('.text-sm.font-medium');
            if (!nameElement) return;

            const personName = nameElement.textContent.trim();

            if (confirm(`Are you sure you want to delete "${personName}"?`)) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100px)';
                row.style.transition = 'all 0.3s ease';

                setTimeout(() => {
                    row.classList.add('hidden');
                    closeAllDropdowns();
                    updateSelectedCount();
                }, 300);
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.option-btn') && !e.target.closest('.option-dropdown')) {
            closeAllDropdowns();
        }
    });

    historyFilter.addEventListener('change', function () {
        const filterValue = this.value;

        historyRows.forEach(row => {
            const rowType = row.getAttribute('data-type');

            if (filterValue === 'all' || rowType === filterValue) {
                row.classList.remove('hidden');
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
                row.style.animation = 'fadeIn 0.3s ease-out';
            } else {
                row.classList.add('hidden');
            }
        });

        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    });

    refreshBtn.addEventListener('click', function () {
        refreshIcon.classList.add('animate-spin');

        setTimeout(() => {
            historyFilter.value = 'all';

            historyRows.forEach(row => {
                row.classList.remove('hidden');
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
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
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = message;
        toast.style.animation = 'slideIn 0.3s ease-out';
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 2700);
    }

    updateSelectedCount();

});