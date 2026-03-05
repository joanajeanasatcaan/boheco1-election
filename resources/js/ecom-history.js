document.addEventListener('DOMContentLoaded', function () {

    // ── Inject styles ──────────────────────────────────────────────
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        .animate-spin-custom { animation: spin 1s linear infinite; }
    `;
    document.head.appendChild(style);

    // ── Elements ───────────────────────────────────────────────────
    const tableBody             = document.getElementById('historyTableBody');
    const emptyState            = document.getElementById('emptyState');
    const loadingState          = document.getElementById('loadingState');
    const deleteOptionContainer = document.getElementById('deleteOptionContainer');
    const selectedCountEl       = document.getElementById('selectedCount');
    const deleteSelectedBtn     = document.getElementById('deleteSelectedBtn');
    const cancelDeleteBtn       = document.getElementById('cancelDeleteBtn');
    const historyFilter         = document.getElementById('history-filter');
    const refreshBtn            = document.getElementById('refresh-btn');
    const refreshIcon           = document.getElementById('refresh-icon');

    // ── Badge config per type ──────────────────────────────────────
    const typeBadge = {
        voted:    'bg-blue-50 text-blue-700 border-blue-200',
        verified: 'bg-green-50 text-green-700 border-green-200',
        updated:  'bg-orange-50 text-orange-700 border-orange-200',
    };
    const typeLabel = {
        voted:    'Voted',
        verified: 'Verified',
        updated:  'Profile Updates',
    };

    // ── Render ─────────────────────────────────────────────────────
    function renderRows(records) {
        tableBody.innerHTML = '';

        if (!records.length) {
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        records.forEach(record => {
            const badge  = typeBadge[record.type] ?? 'bg-gray-100 text-gray-600 border-gray-200';
            const label  = typeLabel[record.type]  ?? record.type;
            const performedBy = record.performed_by?.name ?? '—';

            const tr = document.createElement('tr');
            tr.className = 'history-row hover:bg-gray-50 transition-colors duration-150';
            tr.dataset.type = record.type;
            tr.dataset.id   = record.id;

            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox"
                        class="history-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${record.member?.name ?? '—'}</div>
                    <div class="text-sm text-gray-500">#${record.member?.id ?? ''}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full border ${badge}">
                        ${label}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${record.created_at}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${performedBy}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="relative">
                        <button class="options-btn px-3 py-1 rounded-lg hover:bg-gray-200 transition-colors duration-150">
                            ···
                        </button>
                        <div class="options-dropdown absolute right-0 top-full mt-1 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden origin-top-right">
                            <a href="#" class="delete-option flex items-center gap-2 px-4 py-3 hover:bg-red-50 text-red-600 hover:text-red-700 transition-colors duration-150">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="text-sm">Delete</span>
                            </a>
                        </div>
                    </div>
                </td>
            `;

            tableBody.appendChild(tr);
        });

        wireRowEvents();
    }

    // ── Fetch from API ─────────────────────────────────────────────
    async function loadHistory(params = {}) {
        loadingState.classList.remove('hidden');
        emptyState.classList.add('hidden');
        tableBody.innerHTML = '';
        updateSelectedCount();

        try {
            const query    = new URLSearchParams(params).toString();
            const response = await fetch(`/api/ecom/history?${query}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include',
            });

            if (!response.ok) throw new Error('Failed to fetch history');

            const data = await response.json();
            renderRows(data.data ?? []);

        } catch (err) {
            console.error(err);
            showToast('Failed to load history', 'error');
        } finally {
            loadingState.classList.add('hidden');
        }
    }

    // ── Delete single row via API ──────────────────────────────────
    async function deleteRecord(id, row) {
        try {
            const response = await fetch(`/api/ecom/history/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                credentials: 'include',
            });

            if (!response.ok) throw new Error('Delete failed');

            row.style.opacity    = '0';
            row.style.transform  = 'translateX(-100px)';
            row.style.transition = 'all 0.3s ease';
            setTimeout(() => { row.remove(); updateSelectedCount(); }, 300);

            showToast('Record deleted');

        } catch (err) {
            console.error(err);
            showToast('Failed to delete record', 'error');
        }
    }

    // ── Bulk delete via API ────────────────────────────────────────
    async function deleteBulk(ids) {
        try {
            const response = await fetch('/api/ecom/history', {
                method: 'DELETE',
                headers: {
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                credentials: 'include',
                body: JSON.stringify({ ids }),
            });

            if (!response.ok) throw new Error('Bulk delete failed');

            document.querySelectorAll('.history-checkbox:checked').forEach(cb => {
                const row = cb.closest('.history-row');
                if (!row) return;
                row.style.opacity    = '0';
                row.style.transform  = 'translateX(-100px)';
                row.style.transition = 'all 0.3s ease';
                setTimeout(() => { row.remove(); updateSelectedCount(); }, 300);
            });

            setTimeout(() => {
                deleteOptionContainer.classList.add('hidden');
                deleteOptionContainer.classList.remove('flex');
            }, 350);

            showToast(`${ids.length} record(s) deleted`);

        } catch (err) {
            console.error(err);
            showToast('Failed to delete records', 'error');
        }
    }

    // ── Wire events on dynamically rendered rows ───────────────────
    let activeDropdown = null;

    function closeAllDropdowns() {
        document.querySelectorAll('.options-dropdown').forEach(d => {
            d.classList.add('hidden');
            d.classList.remove('block');
        });
        activeDropdown = null;
    }

    function wireRowEvents() {
        // Checkboxes
        document.querySelectorAll('.history-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        // Options dropdowns
        document.querySelectorAll('.options-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;
                if (!dropdown?.classList.contains('options-dropdown')) return;

                if (dropdown === activeDropdown) {
                    dropdown.classList.add('hidden');
                    activeDropdown = null;
                } else {
                    closeAllDropdowns();
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('block');
                    activeDropdown = dropdown;
                }
            });
        });

        // Per-row delete
        document.querySelectorAll('.delete-option').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const row  = this.closest('.history-row');
                const id   = row?.dataset.id;
                const name = row?.querySelector('.text-sm.font-medium')?.textContent.trim();

                if (!row || !id) return;

                if (confirm(`Delete record for "${name}"?`)) {
                    closeAllDropdowns();
                    deleteRecord(id, row);
                }
            });
        });
    }

    // ── Selected count & bulk bar ──────────────────────────────────
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.history-checkbox:checked').length;
        selectedCountEl.textContent = checked;
        deleteSelectedBtn.disabled  = checked === 0;

        if (checked > 0) {
            deleteOptionContainer.classList.remove('hidden');
            deleteOptionContainer.classList.add('flex');
        } else {
            deleteOptionContainer.classList.add('hidden');
            deleteOptionContainer.classList.remove('flex');
        }
    }

    deleteSelectedBtn.addEventListener('click', function () {
        const checked = document.querySelectorAll('.history-checkbox:checked');
        if (!checked.length) return;

        const ids = Array.from(checked).map(cb => cb.closest('.history-row')?.dataset.id).filter(Boolean);

        if (confirm(`Delete ${ids.length} record(s)?`)) {
            deleteBulk(ids);
        }
    });

    cancelDeleteBtn.addEventListener('click', function () {
        document.querySelectorAll('.history-checkbox').forEach(cb => cb.checked = false);
        updateSelectedCount();
    });

    // ── Filter ─────────────────────────────────────────────────────
    historyFilter.addEventListener('change', function () {
        const params = {};
        if (this.value !== 'all') params.type = this.value;
        loadHistory({ per_page: 20, ...params });
    });

    // ── Refresh ────────────────────────────────────────────────────
    refreshBtn.addEventListener('click', function () {
        refreshIcon.classList.add('animate-spin-custom');
        historyFilter.value = 'all';

        loadHistory({ per_page: 20 }).then(() => {
            refreshIcon.classList.remove('animate-spin-custom');
            showToast('History refreshed!');
        });
    });

    // ── Close dropdowns on outside click ──────────────────────────
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.options-btn') && !e.target.closest('.options-dropdown')) {
            closeAllDropdowns();
        }
    });

    // ── Toast ──────────────────────────────────────────────────────
    function showToast(message, type = 'success') {
        const color = type === 'error' ? 'bg-red-500' : 'bg-green-500';
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 ${color} text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm`;
        toast.textContent = message;
        toast.style.animation = 'slideIn 0.3s ease-out';
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity    = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 2700);
    }

    loadHistory({ per_page: 20 });
});