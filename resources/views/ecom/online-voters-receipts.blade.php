<x-ecom-layout>
    <x-slot>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                <h2 class="font-semibold items-center text-xl text-gray-800 leading-tight">
                    Online Voters Receipts
                </h2>
            </div>
                <div>
                    <button onclick="window.print()"
                        class="flex border border-gray-800 items-center justify-end gap-2 bg-gray-400 hover:bg-gray-600 text-gray-800 font-semibold px-4 py-2 rounded-md focus:outline-none">
                        Print All
                    </button>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full p border border-gray-200">
                    <thead class="bg-green-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Profile</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Id Number</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date/Time Voted
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Remarks</th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">


                    </tbody>
                </table>
            </div>
        </div>
    </x-slot>
</x-ecom-layout>