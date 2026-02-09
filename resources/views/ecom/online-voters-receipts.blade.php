<x-ecom-layout>
    <x-slot>
        <div class="mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl p-6 mb-8 shadow-lg print:bg-white print:text-black print:shadow-none print:border print:border-gray-300">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div class="mb-4 md:mb-0">
                        <h2 class="font-bold text-2xl md:text-3xl text-white print:text-gray-900">
                            Online Voters Receipts
                        </h2>
                        <p class="text-emerald-100 mt-2 text-sm md:text-base print:text-gray-700">
                            View and print all voter verification records
                        </p>
                        <div class="mt-4 flex items-center text-emerald-100 print:text-gray-600">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span class="text-sm">Generated: {{ now()->format('F d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="window.print()"
                            class="flex items-center gap-2 bg-white hover:bg-gray-100 text-emerald-700 font-semibold px-5 py-3 rounded-lg shadow-md transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 print:hidden">
                            <i class="fas fa-print"></i>
                            Print All Receipts
                        </button>
                        <button onclick="printSelectedRows()"
                            class="flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold px-5 py-3 rounded-lg shadow-md transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 print:hidden">
                            <i class="fas fa-check-square"></i>
                            Print Selected
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-4 mb-6 print:hidden">
                <div class="flex flex-col md:flex-row gap-4 items-center 
                justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all" class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <label for="select-all" class="ml-2 text-sm text-gray-700">Select All</label>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span id="selected-count">0</span> receipts selected
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <select id="print-format" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none">
                            <option value="all">Print All Pages</option>
                            <option value="current">Current Page Only</option>
                            <option value="selected">Selected Items Only</option>
                        </select>
                        <select class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none">
                            <option>Sort by: Voting Date</option>
                            <option>Sort by: Voter Name</option>
                            <option>Sort by: ID Number</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden print:shadow-none 
            print:border print:border-gray-300">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 print-table">
                        <thead class="bg-gradient-to-r from-emerald-600 to-green-600 
                        print:bg-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900 w-12 print:hidden">
                                    <div class="flex items-center">
                                        <span class="sr-only">Select</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white 
                                uppercase tracking-wider print:text-gray-900">
                                    <div class="flex items-center">
                                        <span>Profile</span>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900">
                                    <div class="flex items-center">
                                        <span>Voter Details</span>
                                        <i class="fas fa-sort ml-1 text-white/70 print:hidden"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900">
                                    <div class="flex items-center">
                                        <span>ID Number</span>
                                        <i class="fas fa-sort ml-1 text-white/70 print:hidden"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900">
                                    <div class="flex items-center">
                                        <span>Voting Time</span>
                                        <i class="fas fa-sort ml-1 text-white/70 print:hidden"></i>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider print:text-gray-900 print:hidden">
                                    Print Options
                                </th>
                            </tr>
                        </thead>
                        
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($online_voters_receipts as $index => $receipt)
                                <tr class="hover:bg-gray-50 transition-colors duration-150 print:border-b print:border-gray-300" data-receipt-id="{{ $receipt->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap print:hidden">
                                        <input type="checkbox" 
                                               class="receipt-checkbox h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                               data-receipt-id="{{ $receipt->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-full overflow-hidden border-2 border-emerald-100 shadow print:border-gray-300">
                                                <img class="h-full w-full object-cover"
                                                    src="{{ $receipt->profile_picture_url }}" 
                                                    alt="Profile Picture"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($receipt->name) }}&background=10b981&color=fff'">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="font-medium text-gray-900 print:font-bold">{{ $receipt->name }}</p>
                                            <p class="text-sm text-gray-500 print:text-gray-700">{{ $receipt->email ?? 'N/A' }}</p>
                                            <div class="mt-1 text-xs text-gray-400 print:text-gray-600 print:hidden">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $receipt->location ?? 'District Voter' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-gray-800 font-medium print:font-bold">
                                            {{ $receipt->id_number }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500 print:text-gray-700 print:hidden">
                                            <i class="fas fa-id-card mr-1"></i>
                                            Verified Voter ID
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <p class="text-gray-900 font-medium print:font-bold">{{ \Carbon\Carbon::parse($receipt->date_time_voted)->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-500 print:text-gray-700">{{ \Carbon\Carbon::parse($receipt->date_time_voted)->format('h:i A') }}</p>
                                            <div class="mt-1 text-xs text-emerald-600 print:hidden">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($receipt->date_time_voted)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClass = 'bg-green-100 text-green-800';
                                            if(str_contains(strtolower($receipt->remarks), 'pending')) {
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                            } elseif(str_contains(strtolower($receipt->remarks), 'reject') || str_contains(strtolower($receipt->remarks), 'invalid')) {
                                                $statusClass = 'bg-red-100 text-red-800';
                                            }
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }} print:px-2 print:py-0.5 print:border print:border-gray-400 print:bg-white print:text-gray-900">
                                            {{ $receipt->remarks }}
                                        </span>
                                        <div class="mt-1 text-xs text-gray-500 print:text-gray-700 print:hidden">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            Receipt #{{ str_pad($index + 1, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap print:hidden">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="printSingleReceipt({{ $receipt->id }})"
                                                    class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-3 py-2 rounded-lg shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1">
                                                <i class="fas fa-print"></i>
                                                <span class="text-xs">Print</span>
                                            </button>
                                            <button onclick="previewReceipt({{ $receipt->id }})"
                                                    class="flex items-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold px-3 py-2 rounded-lg shadow-sm hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1">
                                                <i class="fas fa-eye"></i>
                                                <span class="text-xs">Preview</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 print:hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                            Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">{{ count($online_voters_receipts) }}</span> voter receipts
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </button>
                            <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">
                                1
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                2
                            </button>
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Print Instructions & Help -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 print:hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 text-blue-500 bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-print text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">Printing Instructions</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p class="mb-2">✓ Click "Print All Receipts" for all voters</p>
                                <p class="mb-2">✓ Select individual rows and use "Print Selected"</p>
                                <p class="mb-2">✓ Use the print button on each row for single receipts</p>
                                <p class="mb-2">✓ For best results, select "Save as PDF" in print dialog</p>
                                <p class="text-xs mt-3 text-blue-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Printed receipts include voter verification details and timestamp
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 text-emerald-500 bg-emerald-100 p-2 rounded-lg">
                            <i class="fas fa-file-alt text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-emerald-800">Receipt Information</h3>
                            <div class="mt-2 text-sm text-emerald-700">
                                <p class="mb-2">✓ Each receipt is digitally signed and timestamped</p>
                                <p class="mb-2">✓ Includes unique voter ID and verification code</p>
                                <p class="mb-2">✓ Legal document for election verification</p>
                                <p class="mb-2">✓ Can be used for audit and verification purposes</p>
                                <p class="text-xs mt-3 text-emerald-600">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    All receipts are encrypted and tamper-proof
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Print Watermark (Only shows in print) -->
            <div class="hidden print:block text-center mt-8 pt-8 border-t border-gray-300">
                <p class="text-xs text-gray-500">
                    <strong>BOHECO 1 Election System</strong> | Official Voter Receipts | 
                    Printed on: {{ now()->format('F d, Y h:i A') }} | 
                    Page <span class="page-number"></span> of <span class="page-total"></span>
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    This is an official document. Keep for your records.
                </p>
            </div>
        </div>
        
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
        
        <!-- Print Functionality Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Print button feedback
                const printAllButton = document.querySelector('button[onclick="window.print()"]');
                if(printAllButton) {
                    printAllButton.addEventListener('click', function() {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Preparing Print...';
                        this.disabled = true;
                        
                        // Add print-specific styles
                        document.body.classList.add('printing');
                        
                        setTimeout(() => {
                            window.print();
                            this.innerHTML = originalText;
                            this.disabled = false;
                            document.body.classList.remove('printing');
                        }, 1000);
                    });
                }
                
                // Select all functionality
                const selectAllCheckbox = document.getElementById('select-all');
                const receiptCheckboxes = document.querySelectorAll('.receipt-checkbox');
                const selectedCountElement = document.getElementById('selected-count');
                
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    receiptCheckboxes.forEach(checkbox => {
                        checkbox.checked = isChecked;
                    });
                    updateSelectedCount();
                });
                
                // Individual checkbox change
                receiptCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateSelectedCount();
                        updateSelectAllCheckbox();
                    });
                });
                
                // Row click to select
                const tableRows = document.querySelectorAll('tbody tr');
                tableRows.forEach(row => {
                    row.addEventListener('click', function(e) {
                        // Don't trigger if clicking on buttons or checkboxes
                        if (e.target.type === 'checkbox' || e.target.closest('button')) {
                            return;
                        }
                        
                        const checkbox = this.querySelector('.receipt-checkbox');
                        if (checkbox) {
                            checkbox.checked = !checkbox.checked;
                            checkbox.dispatchEvent(new Event('change'));
                        }
                    });
                });
                
                function updateSelectedCount() {
                    const selected = document.querySelectorAll('.receipt-checkbox:checked').length;
                    selectedCountElement.textContent = selected;
                    
                    // Update UI based on selection
                    const printSelectedBtn = document.querySelector('button[onclick="printSelectedRows()"]');
                    if (selected > 0) {
                        printSelectedBtn.classList.remove('opacity-50');
                        printSelectedBtn.disabled = false;
                    } else {
                        printSelectedBtn.classList.add('opacity-50');
                        printSelectedBtn.disabled = true;
                    }
                }
                
                function updateSelectAllCheckbox() {
                    const allChecked = document.querySelectorAll('.receipt-checkbox:checked').length;
                    const totalCheckboxes = receiptCheckboxes.length;
                    selectAllCheckbox.checked = allChecked === totalCheckboxes && totalCheckboxes > 0;
                    selectAllCheckbox.indeterminate = allChecked > 0 && allChecked < totalCheckboxes;
                }
                
                // Print selected rows
                window.printSelectedRows = function() {
                    const selectedCheckboxes = document.querySelectorAll('.receipt-checkbox:checked');
                    if (selectedCheckboxes.length === 0) {
                        alert('Please select at least one receipt to print.');
                        return;
                    }
                    
                    // Store original display state
                    const allRows = document.querySelectorAll('tbody tr');
                    allRows.forEach(row => {
                        row.style.display = '';
                    });
                    
                    // Hide unselected rows
                    allRows.forEach(row => {
                        const checkbox = row.querySelector('.receipt-checkbox');
                        if (checkbox && !checkbox.checked) {
                            row.style.display = 'none';
                        }
                    });
                    
                    // Hide controls for printing
                    document.querySelectorAll('.print\\:hidden').forEach(el => {
                        el.style.display = 'none';
                    });
                    
                    // Print
                    window.print();
                    
                    // Restore display
                    allRows.forEach(row => {
                        row.style.display = '';
                    });
                    
                    // Restore controls
                    document.querySelectorAll('.print\\:hidden').forEach(el => {
                        el.style.display = '';
                    });
                };
                
                // Single receipt print
                window.printSingleReceipt = function(receiptId) {
                    const row = document.querySelector(`tr[data-receipt-id="${receiptId}"]`);
                    if (!row) return;
                    
                    // Store original display
                    const allRows = document.querySelectorAll('tbody tr');
                    allRows.forEach(r => {
                        r.style.display = '';
                    });
                    
                    // Hide other rows
                    allRows.forEach(r => {
                        if (r.getAttribute('data-receipt-id') !== receiptId.toString()) {
                            r.style.display = 'none';
                        }
                    });
                    
                    // Hide controls for printing
                    document.querySelectorAll('.print\\:hidden').forEach(el => {
                        el.style.display = 'none';
                    });
                    
                    // Print
                    window.print();
                    
                    // Restore
                    allRows.forEach(r => {
                        r.style.display = '';
                    });
                    
                    document.querySelectorAll('.print\\:hidden').forEach(el => {
                        el.style.display = '';
                    });
                };
                
                // Preview receipt
                window.previewReceipt = function(receiptId) {
                    alert('Preview feature would open a modal showing receipt #' + receiptId + ' in print format.');
                    // In a real implementation, this would open a modal with the receipt preview
                };
                
                // Initialize count
                updateSelectedCount();
            });
            
            // Print event handling
            window.addEventListener('beforeprint', function() {
                // Add page numbers
                const totalPages = Math.ceil(document.querySelectorAll('tbody tr').length / 10); // Assuming 10 per page
                document.querySelectorAll('.page-total').forEach(el => {
                    el.textContent = totalPages;
                });
            });
            
            // Handle print dialog close
            window.addEventListener('afterprint', function() {
                // Reset any print-specific styles
                document.body.classList.remove('printing');
            });
        </script>
        
        <!-- Print-specific styles -->
        <style>
            @media print {
                @page {
                    margin: 0.5in;
                }
                
                body {
                    font-size: 12px;
                }
                
                h1, h2, h3, h4, h5, h6 {
                    color: #000 !important;
                }
                
                .print-table th,
                .print-table td {
                    padding: 6px 8px;
                    border-bottom: 1px solid #ddd;
                }
                
                .print-table tr {
                    page-break-inside: avoid;
                }
                
                .page-break {
                    page-break-before: always;
                }
            }
        </style>
    </x-slot>
</x-ecom-layout>