{{-- resources/views/voter/scan.blade.php --}}
{{-- Route: GET /voter/scan/{member_id}  (web.php: Route::get('/voter/scan/{member_id}', fn($id) => view('voter.scan', ['member_id' => $id])); ) --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voter Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }

        @keyframes spin-slow { to { transform: rotate(360deg); } }
        @keyframes pop-in    { 0% { transform: scale(0.7); opacity:0; } 80% { transform: scale(1.05); } 100% { transform: scale(1); opacity:1; } }
        @keyframes fade-up   { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

        .spin-slow  { animation: spin-slow 1.4s linear infinite; }
        .pop-in     { animation: pop-in  0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .fade-up    { animation: fade-up 0.4s ease forwards; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-100 flex items-center justify-center p-4">

    {{-- Loading state --}}
    <div id="stateLoading" class="flex flex-col items-center text-center space-y-4">
        <div class="relative w-20 h-20">
            <div class="absolute inset-0 rounded-full border-4 border-green-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-green-500 spin-slow"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
        <p class="text-gray-600 font-medium">Verifying voter...</p>
        <p class="text-sm text-gray-400">Please wait</p>
    </div>

    {{-- Success state --}}
    <div id="stateSuccess" class="hidden w-full max-w-sm">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden pop-in">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Verified!</h1>
                <p class="text-green-100 text-sm">Voter successfully verified</p>
            </div>
            <div class="p-6 space-y-4 fade-up">
                <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Voter</span>
                        <span id="successName" class="text-sm font-semibold text-gray-900"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Member ID</span>
                        <span id="successId" class="text-sm font-mono text-gray-700"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Verified at</span>
                        <span id="successTime" class="text-sm text-gray-700"></span>
                    </div>
                    <div id="spouseRow" class="hidden flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Spouse</span>
                        <span id="successSpouse" class="text-sm text-gray-700"></span>
                    </div>
                </div>
                <div class="bg-green-50 border border-green-100 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-green-800 font-medium">Household verified successfully.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Already verified state --}}
    <div id="stateAlready" class="hidden w-full max-w-sm">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden pop-in">
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Already Verified</h1>
                <p class="text-blue-100 text-sm">This household was verified earlier</p>
            </div>
            <div class="p-6 fade-up">
                <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-900 font-medium">Duplicate scan detected</p>
                        <p id="alreadyTime" class="text-xs text-blue-600 mt-0.5"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Error state --}}
    <div id="stateError" class="hidden w-full max-w-sm">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden pop-in">
            <div class="bg-gradient-to-br from-red-500 to-rose-600 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Verification Failed</h1>
                <p class="text-red-100 text-sm">Could not verify this voter</p>
            </div>
            <div class="p-6 space-y-4 fade-up">
                <p id="errorMessage" class="text-sm text-gray-700 text-center"></p>
                <div id="missingFields" class="hidden bg-red-50 border border-red-100 rounded-xl p-4">
                    <p class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-2">Missing Information</p>
                    <ul id="missingList" class="space-y-1 text-sm text-red-600 list-disc list-inside"></ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        const MEMBER_ID = @json($member_id);
        const CSRF      = document.querySelector('meta[name="csrf-token"]').content;

        function show(id) {
            ['stateLoading','stateSuccess','stateAlready','stateError'].forEach(function(s) {
                document.getElementById(s).classList.add('hidden');
            });
            document.getElementById(id).classList.remove('hidden');
        }

        function formatDate(iso) {
            if (!iso) return '';
            return new Date(iso).toLocaleString();
        }

        async function verifyScan() {
            try {
                const response = await fetch('/api/ecom/voter-verification/verify', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    credentials: 'include',
                    body: JSON.stringify({ member_id: MEMBER_ID })
                });

                const data = await response.json();

                if (response.ok) {
                    // Success
                    document.getElementById('successName').textContent = data.member ? data.member.name : '-';
                    document.getElementById('successId').textContent   = data.member ? data.member.id   : MEMBER_ID;
                    document.getElementById('successTime').textContent = formatDate(data.verified_at);

                    if (data.spouse && data.spouse.name) {
                        document.getElementById('spouseRow').classList.remove('hidden');
                        document.getElementById('successSpouse').textContent = data.spouse.name;
                    }

                    show('stateSuccess');

                } else if (response.status === 409) {
                    // Already verified
                    document.getElementById('alreadyTime').textContent = data.verified_at
                        ? 'Originally verified on ' + formatDate(data.verified_at)
                        : '';
                    show('stateAlready');

                } else if (response.status === 422) {
                    // Incomplete data
                    document.getElementById('errorMessage').textContent = data.message || 'Voter data is incomplete.';
                    if (data.missing_fields && data.missing_fields.length) {
                        document.getElementById('missingFields').classList.remove('hidden');
                        var list = document.getElementById('missingList');
                        list.innerHTML = '';
                        data.missing_fields.forEach(function(f) {
                            var li = document.createElement('li');
                            li.textContent = f.replace(/_/g, ' ');
                            list.appendChild(li);
                        });
                    }
                    show('stateError');

                } else if (response.status === 404) {
                    document.getElementById('errorMessage').textContent = data.message || 'Voter not found.';
                    show('stateError');

                } else {
                    document.getElementById('errorMessage').textContent = 'An unexpected error occurred.';
                    show('stateError');
                }

            } catch (err) {
                console.error(err);
                document.getElementById('errorMessage').textContent = 'Network error. Please try again.';
                show('stateError');
            }
        }

        // Fire the verify call as soon as the page loads
        verifyScan();
    </script>
</body>
</html>