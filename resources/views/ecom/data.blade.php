<x-ecom-layout>
    <div class="w-full mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full border-collapse bg-white">
                <thead class="bg-green-600 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Profile</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Id Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Date/Time Voted
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @foreach ($voters_list as $voter)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full overflow-hidden">
                                        <img class="h-full w-full object-cover"
                                            src="{{ $voter->profile_picture_url }}" alt="Profile Picture">
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $voter->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $voter->id_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $voter->date_time_voted }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($voter->status === 'Pending') bg-yellow-100 text-yellow-800
                                @elseif ($voter->status === 'Verified') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800 @endif">
                                    {{ $voter->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-ecom-layout>
