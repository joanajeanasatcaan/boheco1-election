// ─── Loading screen ───────────────────────────────────────────────────────────
function showLoadingScreen() {
    const loader = document.createElement('div');
    loader.id    = 'schedule-loader';
    loader.style.cssText = 'position:fixed;inset:0;z-index:9999;background:#f9fafb;display:flex;align-items:center;justify-content:center;';
    loader.innerHTML = `
        <div class="text-center px-6">
            <div class="flex justify-center mb-6">
                <div style="position:relative;width:80px;height:80px;">
                    <svg style="animation:spin 1.2s linear infinite;width:80px;height:80px;color:#16a34a;" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="2" stroke="#dcfce7"/>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke-width="2.5" stroke="#16a34a" stroke-linecap="round"/>
                    </svg>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:28px;height:28px;color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <h2 style="font-size:1.25rem;font-weight:700;color:#111827;margin-bottom:8px;">Verifying Access</h2>
            <p style="font-size:0.875rem;color:#6b7280;">Checking election schedule...</p>
            <div style="margin-top:24px;display:flex;justify-content:center;gap:6px;">
                <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite 0.2s;"></span>
                <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:bounce 1s infinite 0.4s;"></span>
            </div>
        </div>
        <style>
            @keyframes spin   { to { transform: rotate(360deg); } }
            @keyframes bounce { 0%,100% { transform:translateY(0); opacity:.5; } 50% { transform:translateY(-8px); opacity:1; } }
        </style>
    `;
    document.body.appendChild(loader);
}

function hideLoadingScreen() {
    const loader = document.getElementById('schedule-loader');
    if (!loader) return;
    loader.style.transition = 'opacity 0.3s ease';
    loader.style.opacity    = '0';
    setTimeout(function() { loader.remove(); }, 300);
}

// ─── Schedule Gate ────────────────────────────────────────────────────────────
async function checkScheduleAccess() {
    try {
        const res = await fetch('/api/admin/schedules', {
            headers: { 'Accept': 'application/json' },
            credentials: 'include',
        });
        if (!res.ok) throw new Error('Failed to fetch schedule');
        const data      = await res.json();
        const schedules = data.data ?? [];

        const today    = new Date();
        today.setHours(0, 0, 0, 0);
        const todayStr = today.getFullYear() + '-'
            + String(today.getMonth() + 1).padStart(2, '0') + '-'
            + String(today.getDate()).padStart(2, '0');

        const upcoming = schedules
            .map(function(s) { return { ...s, _date: new Date(s.scheduled_date) }; })
            .filter(function(s) { s._date.setHours(0,0,0,0); return s._date >= today; })
            .sort(function(a, b) { return a._date - b._date; });

        const todaySchedule = schedules.find(function(s) {
            return s.scheduled_date === todayStr;
        });

        if (!todaySchedule) {
            hideLoadingScreen();
            showLockedScreen(upcoming.length ? upcoming[0] : null, null, 'no_schedule');
            return false;
        }

        const authUser     = document.getElementById('auth-user');
        const rawDistrict  = authUser ? authUser.dataset.district : '';
        const userDistrict = rawDistrict
            ? (rawDistrict.startsWith('District') ? rawDistrict : 'District ' + rawDistrict)
            : '';

        if (userDistrict && userDistrict !== todaySchedule.district) {
            hideLoadingScreen();
            showLockedScreen(upcoming.length ? upcoming[0] : null, todaySchedule, 'wrong_district');
            return false;
        }

        hideLoadingScreen(); 
        return true;

    } catch (err) {
        console.error('Schedule check failed:', err);
        hideLoadingScreen();
        showLockedScreen(null, null, 'error');
        return false;
    }
}

async function showLockedScreen(nextSchedule, todaySchedule, reason) {
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '',
            },
            credentials: 'include',
        });
    } catch (err) {
        console.warn('Logout failed:', err);
    }

    let message = 'You are not authorized to access this system at this time.';
    if (reason === 'no_schedule') {
        message = 'There is no election scheduled for today.';
    } else if (reason === 'wrong_district') {
        const scheduledFor = todaySchedule ? ` Today's election is for <strong>${todaySchedule.district}</strong>.` : '';
        message = `Today's election is not scheduled for your district.${scheduledFor}`;
    } else if (reason === 'error') {
        message = 'Unable to verify election schedule. Please contact your administrator.';
    }

    let nextInfo = '';
    if (nextSchedule) {
        const nextDate  = new Date(nextSchedule.scheduled_date);
        const formatted = nextDate.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        nextInfo = `
            <div class="mt-4 p-4 bg-blue-50 border border-blue-100 rounded-xl text-left">
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">Next Scheduled Election</p>
                <p class="text-sm font-semibold text-blue-800">${nextSchedule.district}</p>
                <p class="text-sm text-blue-600">${formatted}</p>
            </div>`;
    } else {
        nextInfo = `
            <div class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-xl text-left">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">No upcoming schedules set</p>
            </div>`;
    }

    document.body.innerHTML = `
        <div class="min-h-screen bg-gray-50 flex items-center justify-center" style="font-family:'Segoe UI',sans-serif;">
            <div class="text-center max-w-md mx-auto px-6 py-10">
                <div class="mb-6 flex justify-center">
                    <div style="width:96px;height:96px;border-radius:50%;background:#fef2f2;display:flex;align-items:center;justify-content:center;">
                        <svg style="width:48px;height:48px;color:#f87171;" fill="none" stroke="#f87171" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <h2 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:8px;">Access Restricted</h2>
                <p style="font-size:0.875rem;color:#6b7280;line-height:1.6;">${message}</p>
                ${nextInfo}
                <div style="margin-top:24px;padding:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;text-align:left;">
                    <p style="font-size:0.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Today</p>
                    <p style="font-size:0.875rem;font-weight:500;color:#374151;">${new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>
                <p style="margin-top:16px;font-size:0.75rem;color:#9ca3af;">You have been logged out. Please contact your administrator.</p>
                <a href="/login" style="margin-top:16px;display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#16a34a;color:white;font-size:0.875rem;font-weight:500;border-radius:12px;text-decoration:none;transition:background 0.2s;"
                    onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Back to Login
                </a>
            </div>
        </div>
    `;
}

// ─── Profile dropdown ─────────────────────────────────────────────────────────
const btn      = document.getElementById('profileButton');
const dropdown = document.getElementById('profileDropdown');

if (btn) {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        dropdown.classList.toggle('animate-fade-in');
    });
}

document.addEventListener('click', (e) => {
    if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

// ─── Init ─────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', async function () {
    const allowed = await checkScheduleAccess();
    
    if (allowed) {
        window.__scheduleAccessGranted = true;
        document.dispatchEvent(new CustomEvent('scheduleAccessGranted'));
    }
    // ❌ Don't reference `allowed` outside this block
});