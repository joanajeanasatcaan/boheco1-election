let selectedVoters   = new Set();
let currentVoterId   = null;
let currentEditField = null;
let voterIDs = {
    1: [{ type: "Consumer ID", number: "00989876866" }],
    2: [{ type: "Consumer ID", number: "09877996588" }],
    3: [{ type: "Consumer ID", number: "00976556788" }]
};

const validIDOptions = [
    { id: "philsys",  name: "Philsys ID"     },
    { id: "driver",   name: "Driver License" },
    { id: "passport", name: "Passport"       },
    { id: "umid",     name: "UMID"           },
    { id: "sss",      name: "SSS"            },
    { id: "gsis",     name: "GSIS"           },
    { id: "prc",      name: "PRC ID"         },
    { id: "voter",    name: "Voter's ID"     },
    { id: "nbi",      name: "NBI Clearance"  },
];

let votersData = [];
let nextCursor = null;

// ─── Debounce ─────────────────────────────────────────────────────────────────
let searchDebounceTimer = null;
function debounce(fn, delay) {
    delay = delay || 600;
    return function() {
        var args = arguments;
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(function() { fn.apply(null, args); }, delay);
    };
}

// ─── Render table ─────────────────────────────────────────────────────────────
function renderVotersTable(voters) {
    var table = document.getElementById('votersTable');
    table.innerHTML = '';
    voters.forEach(function(voter) {
        var fullName = [voter.first_name, voter.middle_name, voter.last_name].filter(Boolean).join(' ');
        var badge = voter.status === true
            ? '<span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>'
            : '<span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
        var district = voter.district ? 'District ' + voter.district : 'No district';
        table.insertAdjacentHTML('beforeend',
            '<tr onclick="viewVoterDetails(\'' + voter.member_id + '\')" class="hover:bg-gray-50 transition-colors duration-150 cursor-pointer">' +
            '<td class="px-6 py-4"><input type="checkbox" class="voter-checkbox h-4 w-4 rounded border-gray-300 text-green-600" value="' + voter.member_id + '"></td>' +
            '<td class="px-6 py-4"><div class="text-sm font-medium text-gray-900">' + fullName + '</div><div class="text-sm text-gray-500">' + district + '</div></td>' +
            '<td class="px-6 py-4 font-mono">' + voter.member_id + '</td>' +
            '<td class="px-6 py-4"><div>' + (voter.voted_date || '-') + '</div></td>' +
            '<td class="px-6 py-4"><div>' + (voter.voted_method || '-') + '</div></td>' +
            '<td class="px-6 py-4">' + badge + '</td>' +
            '</tr>'
        );
    });
    bindCheckboxListeners();
    updateSelectAllState();
}

function bindCheckboxListeners() {
    document.querySelectorAll('.voter-checkbox').forEach(function(cb) {
        cb.addEventListener('change', function(e) {
            e.stopPropagation();
            this.checked ? selectedVoters.add(this.value) : selectedVoters.delete(this.value);
            updateSelectAllState();
        });
        cb.addEventListener('click', function(e) { e.stopPropagation(); });
    });
}

function updateSelectAllState() {
    var sa    = document.getElementById('select-all');
    var boxes = document.querySelectorAll('.voter-checkbox');
    var n     = Array.from(boxes).filter(function(cb) { return cb.checked; }).length;
    if (sa) { sa.checked = n === boxes.length && boxes.length > 0; sa.indeterminate = n > 0 && n < boxes.length; }
}

// ─── Load voters ──────────────────────────────────────────────────────────────
async function loadVoters(params) {
    params = params || {};
    try {
        var clean = {};
        Object.keys(params).forEach(function(k) { if (params[k] !== '' && params[k] != null) clean[k] = params[k]; });
        var response = await fetch('/api/ecom/members?' + new URLSearchParams(clean).toString(), {
            method: 'GET', headers: { 'Accept': 'application/json'}, credentials: 'include'
        });
        if (!response.ok) throw new Error('Failed to fetch voters');
        var data   = await response.json();
        votersData = data.data;
        nextCursor = data.meta && data.meta.next_cursor ? data.meta.next_cursor : null;
        renderVotersTable(votersData);
        updateShowingText(votersData.length);
    } catch (err) { console.error(err); showToast('error', 'Error loading voters.'); }
}

function updateShowingText(count) {
    var el = document.querySelector('.text-sm.text-gray-700');
    if (el) el.innerHTML = 'Showing <span class="font-medium">' + Math.min(1,count) + '</span> to <span class="font-medium">' + count + '</span> of <span class="font-medium">' + count + '</span> voters';
}

// ─── Filter ───────────────────────────────────────────────────────────────────
function filterVoters() {
    var si = document.getElementById('search-input');
    var vf = document.getElementById('voted-filter');
    var sf = document.getElementById('status-filter');
    var params = { per_page: 20 };
    if (si && si.value.trim()) params.search       = si.value.trim();
    if (vf && vf.value)        params.voted_method = vf.value;
    if (sf && sf.value)        params.status       = sf.value;
    loadVoters(params);
}
var debouncedFilter = debounce(filterVoters, 600);

document.addEventListener('DOMContentLoaded', function () {
    var sa = document.getElementById('select-all');
    if (sa) {
        sa.addEventListener('change', function() {
            document.querySelectorAll('.voter-checkbox').forEach(function(cb) {
                cb.checked = sa.checked;
                sa.checked ? selectedVoters.add(cb.value) : selectedVoters.delete(cb.value);
            });
        });
    }
    var si = document.getElementById('search-input');
    if (si) {
        si.addEventListener('input', debouncedFilter);
        si.addEventListener('keydown', function(e) { if (e.key === 'Enter') { clearTimeout(searchDebounceTimer); filterVoters(); } });
    }
    var vf = document.getElementById('voted-filter');
    var sf = document.getElementById('status-filter');
    if (vf) vf.addEventListener('change', filterVoters);
    if (sf) sf.addEventListener('change', filterVoters);
    loadVoters({ per_page: 20 });
});

// ─── Export CSV ───────────────────────────────────────────────────────────────
function exportToCSV() {
    var rows = document.querySelectorAll('#votersTable tr');
    var csv  = 'data:text/csv;charset=utf-8,Name,District,ID Number,Date Voted,Remarks,Status\n';
    rows.forEach(function(row) {
        var c = row.querySelectorAll('td');
        if (!c.length) return;
        var vals = [
            (c[1] && c[1].querySelector('.text-sm.font-medium') ? c[1].querySelector('.text-sm.font-medium').textContent : '').trim(),
            (c[1] && c[1].querySelector('.text-sm.text-gray-500') ? c[1].querySelector('.text-sm.text-gray-500').textContent : '').trim(),
            (c[2] ? c[2].textContent.trim() : ''),
            (c[3] && c[3].querySelector('div') ? c[3].querySelector('div').textContent : '').trim(),
            (c[4] && c[4].querySelector('span') ? c[4].querySelector('span').textContent : '').trim(),
            (c[5] && c[5].querySelector('span') ? c[5].querySelector('span').textContent : '').trim(),
        ];
        csv += vals.map(function(v) { return '"' + v + '"'; }).join(',') + '\n';
    });
    var a = document.createElement('a');
    a.href = encodeURI(csv); a.download = 'voters_list.csv';
    document.body.appendChild(a); a.click(); a.remove();
}

// ─── Voter detail modal ───────────────────────────────────────────────────────
function viewVoterDetails(voterId) {
    currentVoterId = voterId;
    var modal        = document.getElementById('voterModal');
    var modalContent = document.getElementById('modalContent');
    var voter        = votersData.find(function(v) { return v.member_id === voterId; });
    if (!voter) return;

    var voterIDList = voterIDs[voterId] || [];
    var fullName    = [voter.first_name, voter.middle_name, voter.last_name].filter(Boolean).join(' ');

    var idListHtml = voterIDList.map(function(id, index) {
        return '<div class="bg-gray-50 p-3 rounded-lg border border-gray-200">'
            + '<div class="flex justify-between items-start">'
            + '<div><span class="text-sm font-medium text-gray-700">' + id.type + '</span>'
            + '<p class="text-xs text-gray-500">' + id.number + '</p>'
            + (id.dateAdded ? '<p class="text-xs text-gray-400 mt-1">Added: ' + id.dateAdded + '</p>' : '')
            + '</div>'
            + '<button onclick="removeID(\'' + voterId + '\',' + index + ')" class="text-gray-400 hover:text-red-600 p-1">'
            + '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>'
            + '</svg></button></div></div>';
    }).join('');

    var votedMethod = voter.voted_method
        ? '<span class="px-2 py-1 text-xs font-medium ' + (voter.voted_method === 'Voted Online' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') + ' rounded-full">' + voter.voted_method + '</span>'
        : 'Not yet voted';

    // Voting record — shown only if voter has voted
    var votingRecord = voter.voted_date
        ? '<div class="mt-6 p-4 bg-green-50 rounded-xl">'
            + '<h5 class="text-sm font-medium text-green-800 mb-2">Voting Record</h5>'
            + '<div class="grid grid-cols-2 gap-3">'
            + '<div><p class="text-xs text-green-600">Date Voted</p><p class="text-sm font-medium text-green-900">' + voter.voted_date + '</p></div>'
            + '<div><p class="text-xs text-green-600">Time Voted</p><p class="text-sm font-medium text-green-900">' + (voter.voted_time || '-') + '</p></div>'
            + '</div></div>'
        : '';

    // QR section — always visible when voter is verified, renders inline inside the modal
    var safeFullName = [voter.first_name, voter.middle_name, voter.last_name].filter(Boolean).join(' ').replace(/'/g, '');
    var qrSection = voter.status === true
        ? '<div class="mt-4 border border-gray-200 rounded-xl overflow-hidden">'
            + '<button onclick="toggleQrSection(\'' + voter.member_id + '\')" id="qrToggleBtn"'
            + ' class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors">'
            + '<div class="flex items-center gap-2">'
            + '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.243m-6.243 0H9m0 0h-.01M9 12v4m0-4V9m0 0h.01M5 15h2M5 9h2m0 0H9m-2 0V7m12 2h.01M17 9V7m0 2v4m0-4h-2M17 15h.01"/></svg>'
            + '<span class="text-sm font-medium text-gray-700">QR Code</span>'
            + '</div>'
            + '<svg id="qrChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>'
            + '</button>'
            + '<div id="qrBody" class="hidden">'
            + '<div class="flex flex-col items-center text-center p-4 space-y-2">'
            + '<div id="qrCanvasWrapper" class="p-3 border-2 border-green-100 rounded-xl bg-white shadow-sm"></div>'
            + '<p class="text-xs text-gray-400 font-mono">' + voter.member_id + '</p>'
            + '<p class="text-xs text-gray-400">Scan with tablet to verify at the polling station</p>'
            + '</div>'
            + '<div class="flex gap-2 px-4 pb-4">'
            + '<button onclick="downloadQr(\'' + voter.member_id + '\', \'' + safeFullName + '\')"'
            + ' class="flex-1 flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-xs font-medium transition-colors">'
            + '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>'
            + 'Download</button>'
            + '<button onclick="triggerQrPrint()"'
            + ' class="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-medium transition-colors">'
            + '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>'
            + 'Print</button>'
            + '</div>'
            + '</div>'
            + '</div>'
        : '';

    var verifiedStatusClass = voter.status === true ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
    var verifiedStatusText  = voter.status === true ? 'Verified' : 'Not Verified';

    var editBtn = function(fn, id) {
        return '<button onclick="' + fn + '(\'' + id + '\')" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100">'
            + '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'
            + '</svg></button>';
    };

    modalContent.innerHTML =
        '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">'

        // LEFT column
        + '<div class="space-y-4">'
        + '<div class="flex items-center space-x-4">'
        + '<div class="relative">'
        + '<div class="h-20 w-20 rounded-full border-4 border-green-100 bg-gray-200 flex items-center justify-center overflow-hidden"></div>'
        + '<button onclick="uploadProfilePicture(\'' + voter.member_id + '\')" class="absolute bottom-0 right-0 bg-gray-300 text-gray-400 p-1 rounded-full hover:bg-green-600 transition-colors">'
        + '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
        + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>'
        + '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>'
        + '</svg></button></div>'
        + '<div><h4 class="text-xl font-bold text-gray-900">' + fullName + '</h4>'
        + '<p class="text-gray-600">District ' + voter.district + '</p>'
        + '<span class="mt-1 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ' + verifiedStatusClass + '">' + verifiedStatusText + '</span>'
        + '</div></div>'

        + '<div class="space-y-3">'
        + '<h5 class="text-sm font-medium text-gray-500">Personal Information</h5>'
        + '<div class="flex items-center justify-between">'
        + '<div><p class="text-xs text-gray-500">ID Number</p><p class="text-sm font-medium">' + voter.member_id + '</p></div>'
        + '<button onclick="editIDNumber(\'' + voter.member_id + '\')" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg></button>'
        + '</div>'
        + '<div class="space-y-2">' + idListHtml + '</div>'
        + '<div class="flex items-center justify-between">'
        + '<div><p class="text-xs text-gray-500">Birthdate</p><p class="text-sm font-medium">' + (voter.birth_date || '-') + '</p></div>'
        + editBtn('editBirthdate', voter.member_id)
        + '</div>'
        + '<div><p class="text-xs text-gray-500">Spouse</p><p class="text-l font-medium text-blue-900">' + (voter.spouse && voter.spouse.full_name ? voter.spouse.full_name : 'Not in a relationship') + '</p></div>'
        + '</div></div>'

        // RIGHT column
        + '<div class="space-y-4">'
        + '<h5 class="text-sm font-medium text-gray-500 mb-2">Contact Information</h5>'
        + '<div class="space-y-2">'
        + '<div class="flex items-center justify-between">'
        + '<div class="flex items-center space-x-2"><svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
        + '<span class="text-sm">' + (voter.email || 'No email') + '</span></div>'
        + editBtn('editEmail', voter.member_id) + '</div>'
        + '<div class="flex items-center justify-between">'
        + '<div class="flex items-center space-x-2"><svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>'
        + '<span class="text-sm">' + (voter.contact_number || 'No number') + '</span></div>'
        + editBtn('editPhone', voter.member_id) + '</div>'
        + '</div>'
        + '<div class="flex items-center justify-between"><h5 class="text-sm font-medium text-gray-500">Address</h5>' + editBtn('editAddress', voter.member_id) + '</div>'
        + '<p class="text-sm">' + (voter.address || '') + '</p>'
        + '<div><h5 class="text-sm font-medium text-gray-500 mb-2">Voting Information</h5>'
        + '<div class="grid grid-cols-2 gap-3">'
        + '<div><p class="text-xs text-gray-500">Voting Method</p><p class="text-sm font-medium">' + votedMethod + '</p></div>'
        + '<div><p class="text-xs text-gray-500">Verification Date</p><p class="text-sm font-medium">' + (voter.date_verified_time || 'Not verified') + '</p><p class="text-sm font-medium">' + (voter.date_verified_day || '') + '</p></div>'
        + '</div></div>'
        + '</div>'

        + '</div>' // end grid
        + votingRecord
        + qrSection;

    // Verify button state
    var vb = document.getElementById('verifyButton');
    if (vb) {
        if (voter.status === true) {
            vb.textContent = 'Verified'; 
            vb.disabled = true;
            // Remove all possible background classes
            vb.classList.remove('bg-green-500', 'hover:bg-green-600', 'bg-green-600', 'bg-blue-500', 'bg-blue-600');
            // Add gray background for verified state
            vb.classList.add('bg-gray-400', 'cursor-not-allowed', 'text-white');
        } else {
            vb.textContent = 'Verify'; 
            vb.disabled = false;
            // Remove gray classes
            vb.classList.remove('bg-gray-400', 'cursor-not-allowed');
            // Add green background for verify state
            vb.classList.add('bg-green-500', 'hover:bg-green-600', 'text-white');
        }
    }

    modal.classList.remove('hidden');
    modal.classList.add('block');
}

// ─── QR Code (URL-based — tablet browser opens verify page on scan) ───────────
// QR encodes: https://yoursite.com/voter/scan/{member_id}
// Tablet scans -> opens that URL -> page auto-calls verify API
const APP_URL = window.location.origin;

function printQrCode(voterId) {
    // QR is now rendered inline inside the voter modal — just toggle open the QR section
    toggleQrSection(voterId);
}

function toggleQrSection(voterId) {
    var body    = document.getElementById('qrBody');
    var chevron = document.getElementById('qrChevron');
    if (!body) return;

    var isOpen = !body.classList.contains('hidden');

    if (isOpen) {
        // Collapse
        body.classList.add('hidden');
        if (chevron) chevron.style.transform = '';
    } else {
        // Expand and generate QR if not yet rendered
        body.classList.remove('hidden');
        if (chevron) chevron.style.transform = 'rotate(180deg)';

        var wrapper = document.getElementById('qrCanvasWrapper');
        if (wrapper && wrapper.children.length === 0) {
            var voter   = votersData.find(function(v) { return v.member_id === voterId; });
            if (!voter) return;
            var scanUrl = APP_URL + '/voter/scan/' + encodeURIComponent(voter.member_id);

            loadQrLibrary(function() {
                new QRCode(wrapper, {
                    text:         scanUrl,
                    width:        180,
                    height:       180,
                    colorDark:    '#166534',
                    colorLight:   '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H,
                });
            });
        }
    }
}

function loadQrLibrary(callback) {
    if (window.QRCode) { callback(); return; }
    const script   = document.createElement('script');
    script.src     = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
    script.onload  = callback;
    script.onerror = function () { showToast('error', 'Failed to load QR library. Check your connection.'); };
    document.head.appendChild(script);
}

function downloadQr(memberId, fullName) {
    // qrcodejs renders an <img> — grab its src
    const img = document.querySelector('#qrCanvasWrapper img');
    if (!img) return;
    const a      = document.createElement('a');
    a.download   = 'qr-' + memberId + '-' + fullName.replace(/\s+/g, '_') + '.png';
    a.href       = img.src;
    a.click();
}

function triggerQrPrint() {
    const img       = document.querySelector('#qrCanvasWrapper img');
    const printArea = document.getElementById('qrPrintArea');
    if (!img || !printArea) return;

    const name = printArea.querySelector('p.font-semibold')?.textContent    ?? '';
    const dist = printArea.querySelectorAll('p')[1]?.textContent             ?? '';
    const id   = printArea.querySelectorAll('p')[2]?.textContent             ?? '';

    const win = window.open('', '_blank', 'width=420,height=580');
    win.document.write('<!DOCTYPE html><html><head><title>Voter QR Code</title><style>'
        + '* { box-sizing: border-box; margin:0; padding:0; }'
        + 'body { font-family: "Segoe UI", sans-serif; display:flex; justify-content:center; align-items:center; min-height:100vh; background:#f9fafb; }'
        + '.card { text-align:center; padding:40px 32px; background:#fff; border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 4px 24px rgba(0,0,0,.08); }'
        + '.badge { display:inline-block; margin-bottom:20px; background:#dcfce7; color:#166534; padding:4px 14px; border-radius:999px; font-size:11px; font-weight:600; letter-spacing:.5px; text-transform:uppercase; }'
        + 'img { border-radius:8px; border:3px solid #dcfce7; }'
        + 'h2 { margin-top:18px; font-size:18px; color:#111827; font-weight:700; }'
        + '.district { color:#6b7280; font-size:13px; margin-top:4px; }'
        + '.id { font-family:monospace; font-size:11px; color:#9ca3af; margin-top:8px; }'
        + '.footer { margin-top:20px; font-size:10px; color:#d1d5db; }'
        + '@media print { body { background:#fff; } .card { box-shadow:none; border:none; } }'
        + '</style></head><body>'
        + '<div class="card">'
        + '<div class="badge">&#10003; Verified Voter</div><br>'
        + '<img src="' + img.src + '" width="200" height="200"/>'
        + '<h2>' + name + '</h2>'
        + '<p class="district">' + dist + '</p>'
        + '<p class="id">' + id + '</p>'
        + '<p class="footer">Scan to verify at the polling station</p>'
        + '</div>'
        + '<script>window.onload=function(){window.print();window.close();}<\/script>'
        + '</body></html>');
    win.document.close();
}

// ─── Modal helpers ────────────────────────────────────────────────────────────
function uploadProfilePicture() { alert('Upload profile picture code here'); }

function closeModal() {
    document.getElementById('voterModal').classList.remove('block');
    document.getElementById('voterModal').classList.add('hidden');
    currentVoterId = null;
}
function closeIDModal() {
    var m = document.getElementById('editIDModal');
    if (m) { m.classList.remove('block'); m.classList.add('hidden'); }
    currentEditField = null;
}
function closeEditModal() {
    document.getElementById('editModal').classList.remove('block');
    document.getElementById('editModal').classList.add('hidden');
    currentEditField = null;
}

// ─── Edit ID ──────────────────────────────────────────────────────────────────
function editIDNumber(voterId) {
    currentEditField = voterId;
    var m = document.getElementById('editIDModal');
    var c = document.getElementById('editIDModalContent');
    c.innerHTML = '<div class="space-y-4"><p class="text-sm text-gray-600">Select a valid ID type to add:</p>'
        + '<div class="grid grid-cols-2 gap-3">'
        + validIDOptions.map(function(id) {
            return '<button onclick="selectIDType(\'' + id.id + '\',\'' + id.name + '\')" class="flex items-center p-3 border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all text-left"><p class="text-sm font-medium text-gray-900">' + id.name + '</p></button>';
        }).join('')
        + '</div><div id="idInputSection" class="hidden space-y-3"><label class="block text-sm font-medium text-gray-700" id="idTypeLabel"></label>'
        + '<input type="text" id="idNumberInput" placeholder="Enter ID Number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">'
        + '</div></div>';
    m.classList.remove('hidden');
    m.classList.add('block');
}

function selectIDType(id, name) {
    document.getElementById('idTypeLabel').textContent = name + ' Number';
    document.getElementById('idInputSection').classList.remove('hidden');
    setTimeout(function() { document.getElementById('idNumberInput').focus(); }, 100);
}

function saveID() {
    var inp   = document.getElementById('idNumberInput');
    var label = document.getElementById('idTypeLabel');
    if (!inp || !label) { alert('Error: Could not find input elements'); return; }
    var num  = inp.value.trim();
    var type = label.textContent.replace(' Number','').trim();
    if (!num)              { alert('Please enter an ID number'); return; }
    if (!currentEditField) { alert('Error: No voter selected');  return; }
    if (!voterIDs[currentEditField]) voterIDs[currentEditField] = [];
    voterIDs[currentEditField].push({ type: type, number: num, dateAdded: new Date().toISOString().split('T')[0] });
    showToast('success', type + ' added successfully');
    closeIDModal();
    if (currentVoterId) viewVoterDetails(currentVoterId);
}

function removeID(voterId, index) {
    if (!confirm('Are you sure you want to remove this ID?')) return;
    if (voterIDs[voterId] && voterIDs[voterId][index]) {
        var removed = voterIDs[voterId][index];
        voterIDs[voterId].splice(index, 1);
        showToast('success', 'Removed ' + removed.type);
        viewVoterDetails(voterId);
    }
}

// ─── Edit fields ──────────────────────────────────────────────────────────────
function editBirthdate(voterId) {
    currentEditField = 'birthdate';
    var voter = votersData.find(function(v) { return v.member_id === voterId; });
    openEditModal('Edit Birthdate', 'Enter new birthdate (YYYY-MM-DD):', voter ? voter.birth_date || '' : '');
}
function editEmail(voterId) {
    currentEditField = 'email';
    var voter = votersData.find(function(v) { return v.member_id === voterId; });
    openEditModal('Edit Email', 'Enter new email:', voter ? voter.email || '' : '');
}
function editPhone(voterId) {
    currentEditField = 'phone';
    var voter = votersData.find(function(v) { return v.member_id === voterId; });
    openEditModal('Edit Phone Number', 'Enter new phone number:', voter ? voter.contact_number || '' : '');
}
function editAddress(voterId) {
    currentEditField = 'address';
    var voter = votersData.find(function(v) { return v.member_id === voterId; });
    openEditModal('Edit Address', 'Enter new address:', voter ? voter.address || '' : '');
}
function openEditModal(title, label, currentValue) {
    document.getElementById('editModalTitle').textContent = title;
    document.getElementById('editModalContent').innerHTML =
        '<div class="space-y-3"><label class="block text-sm font-medium text-gray-700">' + label + '</label>'
        + '<input type="text" id="editInput" value="' + currentValue + '" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></div>';
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('block');
    setTimeout(function() { document.getElementById('editInput').focus(); }, 100);
}
function saveEdit() {
    var val = document.getElementById('editInput').value.trim();
    if (!val) { alert('Please enter a value'); return; }
    showToast('success', 'Updated ' + currentEditField);
    if (currentVoterId) viewVoterDetails(currentVoterId);
    closeEditModal();
}

// ─── Verify voter ─────────────────────────────────────────────────────────────
async function verifyVoter() {
    if (!currentVoterId) return;
    if (!confirm('Are you sure you want to verify this voter?')) return;

    var btn          = document.getElementById('verifyButton');
    var originalText = btn.textContent;
    btn.disabled     = true;
    btn.textContent  = 'Verifying...';
    btn.classList.remove('bg-green-500','hover:bg-green-600');
    btn.classList.add('bg-gray-300','cursor-not-allowed');

    try {
        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var response = await fetch('/api/ecom/voters/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : '' },
            credentials: 'include',
            body: JSON.stringify({ member_id: currentVoterId })
        });
        var data = await response.json();

        if (response.ok) {
            var idx = votersData.findIndex(function(v) { return v.member_id === currentVoterId; });
            if (idx !== -1) {
                votersData[idx].status             = true;
                votersData[idx].date_verified      = data.verified_at || new Date().toISOString().split('T')[0];
                votersData[idx].date_verified_day  = data.verified_at ? new Date(data.verified_at).toLocaleDateString()  : '';
                votersData[idx].date_verified_time = data.verified_at ? new Date(data.verified_at).toLocaleTimeString() : '';
            }
            showToast('success', data.message || 'Voter verified successfully.');
            renderVotersTable(votersData);
            viewVoterDetails(currentVoterId);

        } else if (response.status === 409) {
            showToast('warning', data.message || 'This household is already verified.');
            var idx = votersData.findIndex(function(v) { return v.member_id === currentVoterId; });
            if (idx !== -1) votersData[idx].status = true;
            viewVoterDetails(currentVoterId);

        } else if (response.status === 422) {
            var missing = data.missing_fields ? data.missing_fields.join(', ') : '';
            showToast('error', (data.message || 'Incomplete data.') + ' Missing: ' + missing);
            restoreVerifyBtn(btn, originalText);

        } else if (response.status === 404) {
            showToast('error', data.message || 'Voter not found.');
            restoreVerifyBtn(btn, originalText);
        } else {
            showToast('error', 'An unexpected error occurred.');
            restoreVerifyBtn(btn, originalText);
        }
    } catch (err) {
        console.error(err);
        showToast('error', 'Network error. Please check your connection.');
        restoreVerifyBtn(btn, originalText);
    }
}

function restoreVerifyBtn(btn, text) {
    btn.disabled    = false;
    btn.textContent = text;
    btn.classList.remove('bg-gray-300','cursor-not-allowed');
    btn.classList.add('bg-green-500','hover:bg-green-600');
}

// ─── Toast ────────────────────────────────────────────────────────────────────
function showToast(type, message) {
    var old = document.getElementById('verificationToast');
    if (old) old.remove();
    var colors = { success: 'bg-green-500', warning: 'bg-yellow-500', error: 'bg-red-500' };
    var icons  = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
        error:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
    };
    var toast       = document.createElement('div');
    toast.id        = 'verificationToast';
    toast.className = 'fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-5 py-4 rounded-xl text-white shadow-xl ' + colors[type] + ' transition-all duration-300 opacity-0 translate-y-2';
    toast.innerHTML = '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">' + icons[type] + '</svg><span class="text-sm font-medium">' + message + '</span>';
    document.body.appendChild(toast);
    requestAnimationFrame(function() { toast.classList.replace('opacity-0','opacity-100'); toast.classList.replace('translate-y-2','translate-y-0'); });
    setTimeout(function() { toast.classList.replace('opacity-100','opacity-0'); setTimeout(function() { toast.remove(); }, 300); }, 4000);
}
// Alias used by old calls
var showVerificationToast = showToast;

// ─── Global exports ───────────────────────────────────────────────────────────
window.viewVoterDetails      = viewVoterDetails;
window.closeModal            = closeModal;
window.closeIDModal          = closeIDModal;
window.closeEditModal        = closeEditModal;
window.exportToCSV           = exportToCSV;
window.verifyVoter           = verifyVoter;
window.saveID                = saveID;
window.saveEdit              = saveEdit;
window.editIDNumber          = editIDNumber;
window.editBirthdate         = editBirthdate;
window.editEmail             = editEmail;
window.editPhone             = editPhone;
window.editAddress           = editAddress;
window.uploadProfilePicture  = uploadProfilePicture;
window.printQrCode           = printQrCode;
window.toggleQrSection        = toggleQrSection;
window.downloadQr            = downloadQr;
window.triggerQrPrint        = triggerQrPrint;
window.removeID              = removeID;
window.selectIDType          = selectIDType;
window.showToast             = showToast;
window.showVerificationToast = showToast;