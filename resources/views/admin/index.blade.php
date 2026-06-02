<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Access Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <form action="{{ route('admin.index') }}" method="GET" class="relative w-full sm:max-w-xs">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search user, NIK, or email..."
                                   class="w-full pl-10 pr-10 py-2 text-sm border border-gray-200 rounded-none focus:border-sky-500 transition-all outline-none">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            @if(request('search'))
                            <a href="{{ route('admin.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" title="Clear search">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                            @endif
                        </form>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 mr-2">Grant All:</span>
                            <button onclick="confirmBulkUpdate('app_drawing', 'Drawing')" class="px-3 py-1.5 text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-500 hover:text-white transition-all">Drawing</button>
                            <button onclick="confirmBulkUpdate('app_inventory', 'Inventory')" class="px-3 py-1.5 text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-500 hover:text-white transition-all">Inventory</button>
                            <button onclick="confirmBulkUpdate('app_npc', 'NPC')" class="px-3 py-1.5 text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-500 hover:text-white transition-all">NPC</button>
                            <button onclick="confirmBulkUpdate('app_dashboard', 'Dashboard')" class="px-3 py-1.5 text-xs font-semibold bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-500 hover:text-white transition-all">Dashboard</button>
                        </div>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-400 bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold">User Information</th>
                                    <th scope="col" class="px-6 py-4 font-bold">NIK</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">Drawing</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">Inventory</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">NPC</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">Dashboard</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($users as $user)
                                <tr class="bg-white hover:bg-sky-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-600">
                                        {{ $user->nik ?? '-' }}
                                    </td>
                                    
                                    @php
                                        $apps = ['app_drawing', 'app_inventory', 'app_npc', 'app_dashboard'];
                                    @endphp

                                    @foreach($apps as $app)
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            <label class="relative flex items-center justify-center cursor-pointer group">
                                                <input type="checkbox" 
                                                       data-user-id="{{ $user->id }}"
                                                       data-app="{{ $app }}"
                                                       class="access-checkbox sr-only peer"
                                                       {{ optional($user->access)->$app ? 'checked' : '' }}>
                                                <div class="w-6 h-6 bg-white border-2 border-gray-200 rounded-none transition-all duration-200 peer-checked:bg-sky-500 peer-checked:border-sky-500 group-hover:border-sky-400"></div>
                                                <svg class="absolute w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </label>
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Attach event listeners to all access checkboxes
            document.querySelectorAll('.access-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const userId = this.getAttribute('data-user-id');
                    const app = this.getAttribute('data-app');
                    const status = this.checked;
                    
                    updateAccess(userId, app, status);
                });
            });
        });

        function confirmBulkUpdate(app, label) {
            if (confirm(`Grant access to ALL users for ${label}?`)) {
                bulkUpdate(app, true);
            }
        }

        function bulkUpdate(app, status) {
            fetch(`/admin/bulk-update-access`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    app: app,
                    status: status ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload to reflect changes
                    window.location.reload();
                } else {
                    alert('Error updating bulk access');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Connection error');
            });
        }

        function updateAccess(userId, app, status) {
            fetch(`/admin/update-access/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    app: app,
                    status: status ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Updated successfully');
                } else {
                    alert('Error updating access');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Connection error');
            });
        }
    </script>
</x-app-layout>
