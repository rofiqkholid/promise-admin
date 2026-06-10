<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide">User Management Console</h2>
    </x-slot>

    <div class="px-3 py-3 md:px-6 md:py-6" x-data="adminConsole()">

        <!-- Toast -->
        <div id="toast" class="fixed bottom-5 right-5 z-50 transform translate-y-20 opacity-0 transition-all duration-300 bg-slate-800 text-white text-xs py-2.5 px-4 font-medium flex items-center gap-2 border-l-4 border-[#0c4da2]">
            <svg class="w-3.5 h-3.5 text-[#0c4da2] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span id="toast-message">Success!</span>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm" @click.away="deleteModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600">Confirm Delete</h3>
                    <button @click="deleteModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <div class="p-5">
                    <p class="text-xs text-gray-600">Are you sure you want to delete <strong class="text-gray-800" x-text="deleteModal.userName"></strong>? This action cannot be undone.</p>
                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="deleteModal.open = false"
                                class="px-4 py-2 text-xs font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="button" @click="confirmDeleteUser()"
                                class="px-4 py-2 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white transition-colors">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div x-show="userModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-md max-h-[90vh] flex flex-col" @click.away="userModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600">Add New User</h3>
                    <button @click="userModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitUserForm()" class="p-5 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Full Name</label>
                        <input type="text" x-model="userModal.form.name" required placeholder="e.g. John Doe"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Email Address</label>
                        <input type="email" x-model="userModal.form.email" required placeholder="e.g. johndoe@promise.com"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">NIK / Employee ID</label>
                            <input type="text" x-model="userModal.form.nik" required placeholder="e.g. NIK123"
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Department</label>
                            <select id="modal-dept-select" x-model="userModal.form.id_dept"
                                    x-init="
                                        $watch('userModal.open', open => {
                                            if (open) {
                                                $nextTick(() => {
                                                    $('#modal-dept-select').select2({ width: '100%', dropdownParent: $('#modal-dept-select').parent() }).on('change', (e) => {
                                                        userModal.form.id_dept = e.target.value;
                                                    });
                                                });
                                            }
                                        });
                                    "
                                    class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none bg-white text-gray-800">
                                <option value="">— No Department —</option>
                                <template x-for="dept in departments" :key="dept.id">
                                    <option :value="dept.id" x-text="dept.name + ' (' + dept.code + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Password</label>
                        <input type="password" x-model="userModal.form.password" required placeholder="••••••••"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div class="flex items-center gap-6 pt-1">
                        <label class="inline-flex items-center cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" x-model="userModal.form.is_active" class="sr-only peer">
                                <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 ml-2">Active Status</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                        <button type="button" @click="userModal.open = false" :disabled="saving"
                                class="px-4 py-2 text-xs font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="saving"
                                class="px-4 py-2 text-xs font-medium bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center gap-1.5 disabled:opacity-50">
                            <template x-if="saving">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="saving ? 'Submitting...' : 'Submit'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 border border-slate-300 min-h-[calc(100vh-120px)]">

            <!-- Left Panel: User List -->
            <div class="lg:col-span-4 border-r border-slate-300 bg-white flex-col lg:h-[calc(100vh-120px)]"
                 :class="selectedUser ? 'hidden lg:flex' : 'flex h-[calc(100vh-120px)] lg:h-[calc(100vh-120px)]'">
                <!-- Filters Row -->
                <div class="min-h-[44px] py-1.5 md:py-0 px-3 border-b border-slate-300 bg-slate-100 flex flex-wrap md:flex-nowrap items-center justify-between gap-1.5 shrink-0 relative z-30">
                    <div class="flex flex-wrap items-center gap-1.5 flex-1">
                        <!-- Title Label -->
                        <span class="text-[10px] font-bold text-slate-500 tracking-wider shrink-0 flex items-center gap-1 mr-1">
                            <i class="fa-solid fa-filter text-[9px]"></i> Filter:
                        </span>

                        <!-- Department Filter -->
                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false; search = ''">
                            <button @click="open = !open; if(!open) search = '';" 
                                    class="h-7 px-2 border rounded-sm flex items-center justify-center gap-1 transition-colors text-[10px]"
                                    :class="selectedDepts.length > 0 ? 'bg-blue-50/50 border-blue-200 text-[#0c4da2] font-semibold' : 'bg-white border-slate-300 text-gray-600 hover:bg-gray-50'"
                                    title="Filter by Department">
                                <i class="fa-solid fa-building text-[10px]"></i>
                                <span class="font-medium">Dept</span>
                                <template x-if="selectedDepts.length > 0">
                                    <span class="ml-0.5 bg-blue-100 text-blue-800 text-[8px] px-1 rounded-full font-bold" x-text="selectedDepts.length"></span>
                                </template>
                            </button>
                            <div x-show="open" 
                                 class="absolute left-0 mt-1 w-48 bg-white border border-slate-300 shadow-md rounded-sm py-1 text-xs custom-scrollbar"
                                 style="max-height: 12rem; overflow-y: auto; z-index: 9999; display: none;">
                                <div class="px-2 pb-1.5 mb-1 border-b border-slate-200 flex justify-between items-center">
                                    <span class="text-[9px] font-bold text-slate-400 ">Dept Filter</span>
                                    <button @click="selectedDepts = []; search = ''; filterUsers();" class="text-[9px] text-rose-500 hover:text-rose-700 font-semibold transition-colors">Reset</button>
                                </div>
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search dept..." class="w-full text-[10px] px-2 py-0.5 border border-slate-200 focus:border-[#0c4da2] focus:ring-0 focus:outline-none h-6 bg-slate-50/50">
                                </div>
                                <template x-for="dept in departments.filter(d => d.code.toLowerCase().includes(search.toLowerCase()) || d.name.toLowerCase().includes(search.toLowerCase()))" :key="dept.id">
                                    <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                        <input type="checkbox" :value="dept.id" x-model="selectedDepts" @change="filterUsers()" class="rounded border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                        <span class="text-[11px] text-gray-700 truncate" x-text="dept.code"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Role Filter -->
                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false; search = ''">
                            <button @click="open = !open; if(!open) search = '';" 
                                    class="h-7 px-2 border rounded-sm flex items-center justify-center gap-1 transition-colors text-[10px]"
                                    :class="selectedRoles.length > 0 ? 'bg-blue-50/50 border-blue-200 text-[#0c4da2] font-semibold' : 'bg-white border-slate-300 text-gray-600 hover:bg-gray-50'"
                                    title="Filter by Role">
                                <i class="fa-solid fa-user-shield text-[10px]"></i>
                                <span class="font-medium">Role</span>
                                <template x-if="selectedRoles.length > 0">
                                    <span class="ml-0.5 bg-blue-100 text-blue-800 text-[8px] px-1 rounded-full font-bold" x-text="selectedRoles.length"></span>
                                </template>
                            </button>
                            <div x-show="open" 
                                 class="absolute left-0 mt-1 w-48 bg-white border border-slate-300 shadow-md rounded-sm py-1 text-xs custom-scrollbar"
                                 style="max-height: 12rem; overflow-y: auto; z-index: 9999; display: none;">
                                <div class="px-2 pb-1.5 mb-1 border-b border-slate-200 flex justify-between items-center">
                                    <span class="text-[9px] font-bold text-slate-400 ">Role Filter</span>
                                    <button @click="selectedRoles = []; search = ''; filterUsers();" class="text-[9px] text-rose-500 hover:text-rose-700 font-semibold transition-colors">Reset</button>
                                </div>
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search role..." class="w-full text-[10px] px-2 py-0.5 border border-slate-200 focus:border-[#0c4da2] focus:ring-0 focus:outline-none h-6 bg-slate-50/50">
                                </div>
                                <template x-for="role in roles.filter(r => r.role_name.toLowerCase().includes(search.toLowerCase()))" :key="role.id">
                                    <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                        <input type="checkbox" :value="role.id" x-model="selectedRoles" @change="filterUsers()" class="rounded border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                        <span class="text-[11px] text-gray-700 truncate" x-text="role.role_name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" 
                                    class="h-7 px-2 border rounded-sm flex items-center justify-center gap-1 transition-colors text-[10px]"
                                    :class="selectedStatuses.length > 0 ? 'bg-blue-50/50 border-blue-200 text-[#0c4da2] font-semibold' : 'bg-white border-slate-300 text-gray-600 hover:bg-gray-50'"
                                    title="Filter by Status">
                                <i class="fa-solid fa-toggle-on text-[10px]"></i>
                                <span class="font-medium">Status</span>
                                <template x-if="selectedStatuses.length > 0">
                                    <span class="ml-0.5 bg-blue-100 text-blue-800 text-[8px] px-1 rounded-full font-bold" x-text="selectedStatuses.length"></span>
                                </template>
                            </button>
                            <div x-show="open" 
                                 class="absolute left-0 mt-1 w-40 bg-white border border-slate-300 shadow-md rounded-sm py-1 text-xs custom-scrollbar"
                                 style="max-height: 12rem; overflow-y: auto; z-index: 9999; display: none;">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-200 flex justify-between items-center">
                                    <span class="text-[9px] font-bold text-slate-400 ">Status Filter</span>
                                    <button @click="selectedStatuses = []; filterUsers();" class="text-[9px] text-rose-500 hover:text-rose-700 font-semibold transition-colors">Reset</button>
                                </div>
                                <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                    <input type="checkbox" value="active" x-model="selectedStatuses" @change="filterUsers()" class="rounded border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                    <span class="text-[11px] text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                    <input type="checkbox" value="inactive" x-model="selectedStatuses" @change="filterUsers()" class="rounded border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                    <span class="text-[11px] text-gray-700">Inactive</span>
                                </label>
                            </div>
                        </div>

                        <!-- Scope Filter -->
                        <div class="relative" x-data="{ open: false, search: '' }" @click.outside="open = false; search = ''">
                            <button @click="open = !open; if(!open) search = '';" 
                                    class="h-7 px-2 border rounded-sm flex items-center justify-center gap-1 transition-colors text-[10px]"
                                    :class="selectedScopes.length > 0 ? 'bg-blue-50/50 border-blue-200 text-[#0c4da2] font-semibold' : 'bg-white border-slate-300 text-gray-600 hover:bg-gray-50'"
                                    title="Filter by Scope">
                                <i class="fa-solid fa-cubes text-[10px]"></i>
                                <span class="font-medium">Scope</span>
                                <template x-if="selectedScopes.length > 0">
                                    <span class="ml-0.5 bg-blue-100 text-blue-800 text-[8px] px-1 rounded-full font-bold" x-text="selectedScopes.length"></span>
                                </template>
                            </button>
                            <div x-show="open" 
                                 class="absolute right-0 mt-1 w-44 bg-white border border-slate-300 shadow-md rounded-sm py-1 text-xs custom-scrollbar"
                                 style="max-height: 12rem; overflow-y: auto; z-index: 9999; display: none;">
                                <div class="px-2 pb-1.5 mb-1 border-b border-slate-200 flex justify-between items-center">
                                    <span class="text-[9px] font-bold text-slate-400 ">Scope Filter</span>
                                    <button @click="selectedScopes = []; search = ''; filterUsers();" class="text-[9px] text-rose-500 hover:text-rose-700 font-semibold transition-colors">Reset</button>
                                </div>
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100">
                                    <input type="text" x-model="search" placeholder="Search scope..." class="w-full text-[10px] px-2 py-0.5 border border-slate-200 focus:border-[#0c4da2] focus:ring-0 focus:outline-none h-6 bg-slate-50/50">
                                </div>
                                <template x-for="scope in scopes.filter(s => s.scope_name.toLowerCase().includes(search.toLowerCase()) || s.id.toLowerCase().includes(search.toLowerCase()))" :key="scope.id">
                                    <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                        <input type="checkbox" :value="scope.id" x-model="selectedScopes" @change="filterUsers()" class="rounded border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                        <span class="text-[11px] text-gray-700 truncate" x-text="scope.scope_name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <button @click="selectedDepts = []; selectedRoles = []; selectedStatuses = []; selectedScopes = []; filterUsers();"
                            x-show="selectedDepts.length > 0 || selectedRoles.length > 0 || selectedStatuses.length > 0 || selectedScopes.length > 0"
                            class="h-7 w-7 border border-slate-300 rounded-sm bg-white text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors shrink-0" 
                            title="Clear All Filters"
                            style="display: none;">
                        <i class="fa-solid fa-filter-circle-xmark text-[11px]"></i>
                    </button>
                </div>
                <!-- Search & Action -->
                <div class="h-[52px] px-3 border-b border-slate-300 bg-slate-50 flex items-center gap-2 shrink-0">
                    <div class="flex-1 flex items-center gap-2 border border-slate-300 bg-white px-3 h-8">
                        <template x-if="!isSearching">
                            <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </template>
                        <template x-if="isSearching">
                            <svg class="animate-spin h-3.5 w-3.5 text-[#0c4da2] shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <input type="text"
                               x-model="searchQuery"
                               @input="filterUsers()"
                               placeholder="Search name, NIK..."
                               class="flex-1 py-1 text-xs outline-none bg-transparent text-gray-700 placeholder-gray-400 border-none focus:ring-0 focus:outline-none">
                    </div>
                    <button @click="openAddUserModal()"
                            class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs shrink-0"
                            title="Add New User">
                        <i class="fa-solid fa-user-plus text-[10px]"></i> Add
                    </button>
                </div>

                <!-- Count Header -->
                <div class="px-4 py-1.5 bg-slate-50 border-b border-slate-200 flex justify-between items-center text-[10px] text-gray-500 font-bold tracking-wider select-none shrink-0">
                    <span>User List</span>
                    <span class="text-slate-400 lowercase normal-case font-medium" x-text="'Showing ' + filteredUsers.length + ' of ' + totalFiltered + ' (' + totalOverall + ' total)'"></span>
                </div>

                <!-- User List -->
                <div class="flex-1 overflow-y-auto custom-scrollbar divide-y divide-gray-100 bg-white"
                     @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 20) { loadMoreUsers() }">
                    <template x-for="user in filteredUsers" :key="user.id">
                        <div @click="selectUser(user)"
                             class="px-4 py-3 cursor-pointer transition-all flex items-start gap-3 border-l-4 border-y"
                             :class="selectedUser && selectedUser.id === user.id ? 'bg-blue-50 border-l-[#0c4da2] border-y-blue-100 relative z-10' : 'border-l-transparent border-y-transparent hover:bg-gray-50/50'">
                            <!-- Profile alphabet initial -->
                            <div class="w-8 h-8 bg-[#0c4da2] text-white flex items-center justify-center shrink-0 font-bold text-xs rounded-xs" x-text="user.name ? user.name.charAt(0) : 'U'">
                            </div>
                            <div class="flex-1 min-w-0" :class="!user.is_active ? 'opacity-70' : ''">
                                <div class="flex justify-between items-start">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <p class="text-xs font-semibold text-gray-900 truncate" x-text="user.name"></p>
                                            <template x-if="!user.is_active">
                                                <span class="text-[8px] font-bold bg-rose-50 text-rose-600 border border-rose-200 px-1.5 py-0.5 shrink-0 tracking-wider rounded-xs">Inactive</span>
                                            </template>
                                        </div>
                                        <p class="text-[10px] text-gray-500 truncate mt-0.5" x-text="user.email"></p>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 shrink-0 ml-3">
                                        <span class="text-[10px] text-gray-500 shrink-0" x-text="user.nik || '-'"></span>
                                        <span class="text-[10px] font-bold text-[#0c4da2] shrink-0" x-text="getDepartmentName(user.id_dept)"></span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <template x-for="scopeId in getUserScopeBadges(user)" :key="scopeId">
                                        <span class="text-[9px] font-medium px-1.5 py-0.5 border"
                                              :class="getScopeColorClass(scopeId)"
                                              x-text="getScopeShortName(scopeId)"></span>
                                    </template>
                                    <template x-if="getUserScopeBadges(user).length === 0">
                                        <span class="text-[9px] text-gray-400 italic">No access assigned</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredUsers.length === 0" class="p-8 text-center text-xs text-gray-400">
                        No users match your search.
                    </div>
                    
                    <!-- Loading / Load More -->
                    <div x-show="nextPageUrl" class="p-3 text-center border-t border-gray-100">
                        <button type="button" @click="loadMoreUsers()" :disabled="isLoadingMore"
                                class="w-full text-xs font-semibold py-2 border border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center justify-center gap-1.5">
                            <template x-if="isLoadingMore">
                                <svg class="animate-spin h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="isLoadingMore ? 'Loading more users...' : 'Load More Users'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: User Config -->
            <div class="lg:col-span-8 flex-col bg-white lg:h-[calc(100vh-120px)]"
                 :class="selectedUser ? 'flex h-[calc(100vh-120px)] lg:h-[calc(100vh-120px)]' : 'hidden lg:flex'">

                <!-- Empty State -->
                <div x-show="!selectedUser" class="flex-1 flex flex-col items-center justify-center text-center bg-gray-50">
                    <div class="w-12 h-12 border-2 border-gray-200 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">No User Selected</p>
                    <p class="text-xs text-gray-400 mt-1 max-w-xs">Select a user from the list on the left to configure their profile and access rights.</p>
                </div>

                <!-- Config Panel -->
                <div x-show="selectedUser" class="flex flex-col h-full lg:overflow-hidden" style="display: none;">

                    <!-- User Header -->
                    <div class="flex flex-col md:h-[96px] border-b border-slate-300 bg-slate-50/50 shrink-0">
                        <!-- Mobile Top Actions Bar (Back & Delete) -->
                        <div class="flex md:hidden items-center justify-between px-4 py-2 border-b border-slate-200/80 bg-white">
                            <!-- Mobile Back Button -->
                            <button @click="selectedUser = null; profileForm.password = '';" class="h-8 px-2.5 rounded-xs border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors gap-1.5 text-xs font-semibold select-none" title="Back to List">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                                <span>Back</span>
                            </button>
                            <!-- Delete Button -->
                            <button @click="triggerDeleteUser(selectedUser)"
                                    class="h-8 px-3 rounded-xs border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center gap-1.5 transition-colors text-xs font-semibold select-none"
                                    title="Delete User">
                                <i class="fa-solid fa-trash-can text-[10px]"></i>
                                <span>Delete</span>
                            </button>
                        </div>

                        <!-- Header Content (Avatar & Name/Details) -->
                        <div class="flex-1 px-4 md:px-6 py-3 md:py-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 md:gap-4 min-w-0 flex-1">
                                <!-- Avatar / Initial -->
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-[#0c4da2] text-white flex items-center justify-center shrink-0 font-bold text-sm md:text-base rounded-xs border border-[#083c80] " x-text="selectedUser ? selectedUser.name.charAt(0) : ''">
                                </div>
                                <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6 min-w-0 flex-1">
                                    <div class="min-w-0">
                                        <h3 class="text-sm md:text-base font-bold text-slate-900 tracking-tight leading-tight truncate" x-text="selectedUser ? selectedUser.name : ''"></h3>
                                        <div class="text-[10px] md:text-[11px] text-slate-500 flex items-center gap-1 mt-0.5 leading-none truncate">
                                            <i class="fa-regular fa-envelope text-slate-400"></i>
                                            <span class="truncate" x-text="selectedUser ? selectedUser.email : ''"></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-row md:flex-col items-center md:items-start gap-3 md:gap-0.5 text-[10px] md:text-[11px] flex-wrap">
                                        <span class="inline-flex items-center gap-1 font-semibold text-slate-500" title="NIK">
                                            <i class="fa-solid fa-id-card text-slate-400"></i>
                                            <span x-text="selectedUser ? selectedUser.nik : ''"></span>
                                        </span>
                                        <span class="inline-flex items-center gap-1 font-bold text-[#0c4da2]" title="Department">
                                            <i class="fa-solid fa-building text-[#0c4da2]"></i>
                                            <span x-text="getDepartmentName(selectedUser ? selectedUser.id_dept : null)"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Desktop Delete Button -->
                            <div class="hidden md:flex items-center gap-2 shrink-0">
                                <button @click="triggerDeleteUser(selectedUser)"
                                        class="h-8 px-3 rounded-xs border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center gap-1.5 transition-colors shrink-0 text-xs font-semibold"
                                        title="Delete User">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Scrollable Body -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-5 space-y-6 bg-gray-50/30">

                        <!-- SECTION 1: Profile Form -->
                        <div class="bg-white border border-gray-200">
                            <div class="px-5 py-3 bg-gray-50/75 border-b border-gray-200 flex items-center gap-2">
                                <h4 class="text-xs font-bold text-gray-700">Profile Information</h4>
                            </div>
                            <form @submit.prevent="saveProfile()" class="p-5">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-6">
                                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Full Name</label>
                                        <input type="text" x-model="profileForm.name" required
                                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Email Address</label>
                                        <input type="email" x-model="profileForm.email" required
                                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">NIK / Employee ID</label>
                                        <input type="text" x-model="profileForm.nik" required
                                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    </div>
                                    <div class="md:col-span-6">
                                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Department</label>
                                        <select id="dept-select" x-model="profileForm.id_dept"
                                                x-init="
                                                    $nextTick(() => {
                                                        $('#dept-select').select2({ width: '100%' }).on('change', (e) => {
                                                            profileForm.id_dept = e.target.value;
                                                        });
                                                    });
                                                    $watch('profileForm.id_dept', value => {
                                                        $('#dept-select').val(value).trigger('change.select2');
                                                    });
                                                "
                                                class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none bg-white text-gray-800">
                                            <option value="">— No Department —</option>
                                            <template x-for="dept in departments" :key="dept.id">
                                                <option :value="dept.id" x-text="dept.name + ' (' + dept.code + ')'"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="md:col-span-6">
                                         <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Reset Password <span class="font-normal text-gray-400 lowercase normal-case">(leave blank to keep current)</span></label>
                                         <input type="password" x-model="profileForm.password" placeholder="••••••••"
                                                class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                     </div>
                                     <div class="md:col-span-3 flex items-center pt-5">
                                         <label class="inline-flex items-center cursor-pointer select-none">
                                             <div class="relative">
                                                 <input type="checkbox" x-model="profileForm.is_active" class="sr-only peer">
                                                 <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                                             </div>
                                             <span class="text-xs font-semibold text-gray-700 ml-2">Active Status</span>
                                         </label>
                                     </div>
                                    <div class="md:col-span-3 flex items-end">
                                        <button type="submit" :disabled="saving"
                                                class="w-full py-2 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1.5 disabled:opacity-50">
                                            <template x-if="saving">
                                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            </template>
                                            <span x-text="saving ? 'Saving...' : 'Save Profile'"></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- SECTION 2: Roles Assignment (Global) -->
                        <div class="bg-white border border-gray-200">
                            <div class="px-5 py-3 bg-gray-50/75 border-b border-gray-200 flex items-center gap-2">
                                <h4 class="text-xs font-bold text-gray-700">Assigned Roles</h4>
                            </div>
                            <div class="p-5 flex flex-wrap gap-1.5">
                                <template x-for="role in roles" :key="role.id">
                                    <label class="inline-flex items-center gap-1.5 px-2.5 py-1.5 border border-gray-300 text-[10px] text-gray-700 cursor-pointer hover:bg-gray-50 transition-colors select-none bg-white">
                                        <input type="checkbox"
                                               :checked="isRoleSelectedGlobal(role.id)"
                                               @change="toggleRoleGlobal(role, $event.target.checked)"
                                               class="h-3 w-3 text-[#0c4da2] border-gray-300 cursor-pointer">
                                        <span x-text="role.role_name" class="font-medium"></span>
                                    </label>
                                </template>
                                <template x-if="roles.length === 0">
                                    <span class="text-[10px] text-gray-400 italic">No roles defined. Create roles first.</span>
                                </template>
                            </div>
                        </div>

                        <!-- SECTION 3: Scope Access -->
                        <div class="bg-white border border-gray-200">
                            <div class="px-5 py-3 bg-gray-50/75 border-b border-gray-200 flex items-center gap-2">
                                <h4 class="text-xs font-bold text-gray-700">Web Application Access & Permissions</h4>
                            </div>

                            <div class="divide-y divide-gray-100">
                            <template x-for="scope in scopes" :key="scope.id">
                                <div>
                                    <!-- Scope Row -->
                                    <div @click="if (isScopeAssigned(scope.id)) expandedScope = (expandedScope === scope.id ? null : scope.id)"
                                         :class="isScopeAssigned(scope.id) ? 'cursor-pointer' : ''"
                                         class="px-3 sm:px-5 py-3 flex items-center justify-between bg-white hover:bg-gray-50 transition-colors select-none gap-2">
                                        <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                                            <div class="relative cursor-default shrink-0 select-none" @click.stop title="Status is controlled by Assigned Roles">
                                                <input type="checkbox"
                                                       :checked="isScopeAssigned(scope.id)"
                                                       disabled
                                                       class="sr-only peer">
                                                <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4 opacity-80"></div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-semibold text-gray-800 truncate" x-text="scope.scope_name"></p>
                                                <p class="text-[10px] text-gray-400 font-mono truncate" x-text="scope.id"></p>
                                            </div>
                                        </div>
                                        <button x-show="isScopeAssigned(scope.id)"
                                                class="text-[9px] sm:text-[10px] font-medium text-[#0c4da2] hover:text-blue-800 flex items-center gap-1 border border-blue-200 px-2 py-0.5 sm:px-2.5 sm:py-1 hover:bg-blue-50/50 transition-colors pointer-events-none shrink-0">
                                            <span x-text="expandedScope === scope.id ? 'Hide' : 'Override' + (window.innerWidth < 640 ? '' : ' Permissions')"></span>
                                            <svg class="w-3 h-3 transition-transform shrink-0" :class="expandedScope === scope.id ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Permissions Override Table -->
                                    <div x-show="isScopeAssigned(scope.id) && expandedScope === scope.id" class="border-t border-gray-200 overflow-x-auto">
                                        <template x-if="isLoadingPermissions">
                                            <div class="py-6 text-center text-xs text-gray-400">Loading...</div>
                                        </template>
                                        <template x-if="!isLoadingPermissions">
                                            <table class="w-full text-left border-collapse text-xs">
                                                <thead>
                                                    <tr class="bg-gray-50 border-b border-gray-200">
                                                        <th class="py-2 px-5 text-[10px] font-semibold tracking-wider text-gray-500 w-1/3">Menu / Feature</th>
                                                        <template x-for="perm in availablePermissions" :key="perm.id">
                                                            <th class="py-2 px-3 text-center text-[10px] font-semibold tracking-wider text-gray-500" x-text="perm.permission_name"></th>
                                                        </template>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    <template x-for="menu in getMenusForScope(scope.id)" :key="menu.id">
                                                        <tr class="hover:bg-gray-50 transition-colors">
                                                            <td class="py-2 px-5 text-xs text-gray-700" :style="'padding-left: ' + (menu.parent_id ? '32px' : '20px')">
                                                                <div class="flex items-center gap-2">
                                                                    <span x-show="menu.parent_id" class="text-gray-300 text-[10px]">└</span>
                                                                    <template x-if="menu.icon">
                                                                        <i :class="menu.icon + ' text-slate-400 text-xs shrink-0'"></i>
                                                                    </template>
                                                                    <span x-text="menu.title"></span>
                                                                </div>
                                                            </td>
                                                                                                                                                                                                                                                <template x-for="perm in availablePermissions" :key="perm.id">
                                                                <td class="py-2 px-3 text-center">
                                                                    <div class="flex items-center justify-center">
                                                                        <template x-if="hasRolePermission(scope.id, menu.id, perm.id)">
                                                                            <!-- Inherited permission: Always checked & disabled -->
                                                                            <div class="inline-flex items-center cursor-not-allowed">
                                                                                <div class="relative">
                                                                                    <input type="checkbox" checked disabled class="sr-only peer">
                                                                                    <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-blue-200 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-200 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4 opacity-75" title="Inherited from Role (Cannot be disabled)"></div>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                        <template x-if="!hasRolePermission(scope.id, menu.id, perm.id)">
                                                                            <!-- Toggleable override permission -->
                                                                            <label class="inline-flex items-center cursor-pointer">
                                                                                <div class="relative">
                                                                                    <input type="checkbox"
                                                                                           :checked="getOverrideStatus(scope.id, menu.id, perm.id) === 'ALLOW'"
                                                                                           @change="updateOverride(scope.id, menu.id, perm.id, $event.target.checked ? 'ALLOW' : 'INHERIT')"
                                                                                           class="sr-only peer">
                                                                                    <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                                                                                </div>
                                                                            </label>
                                                                        </template>
                                                                    </div>
                                                                </td>
                                                            </template>
                                                        </tr>
                                                    </template>
                                                    <template x-if="getMenusForScope(scope.id).length === 0">
                                                        <tr>
                                                            <td :colspan="availablePermissions.length + 1" class="py-6 text-center text-xs text-gray-400 italic">
                                                                No menus configured for this scope.
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    </div><!-- /scrollable body -->
                </div><!-- /config panel -->
            </div><!-- /right panel -->
        </div><!-- /grid -->
    </div>

    <script>
        function adminConsole() {
                        return {
                searchQuery: '',
                selectedDepts: [],
                selectedRoles: [],
                selectedStatuses: [],
                selectedScopes: [],
                users: @json($users->items()),
                filteredUsers: [],
                totalFiltered: {{ $users->total() }},
                totalOverall: {{ $totalOverall }},
                nextPageUrl: '{{ $users->nextPageUrl() }}',
                isLoadingMore: false,
                isSearching: false,
                searchTimeout: null,
                selectedUser: null,
                scopes: @json($scopes),
                roles: @json($roles),
                departments: @json($departments),
                userRolesMap: @json($userScopeRoles),

                availableMenus: [],
                availablePermissions: [],
                userRolePermissions: [],
                userOverrides: [],
                isLoadingPermissions: false,
                expandedScope: null,

                profileForm: { user_id: '', name: '', email: '', nik: '', id_dept: '', password: '', is_active: 1 },
                userModal: { open: false, form: { name: '', email: '', nik: '', id_dept: '', password: '', is_active: 1 } },
                deleteModal: { open: false, userId: null, userName: '' },
                saving: false,

                init() { this.filteredUsers = this.users; },

                filterUsers() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.searchUsers();
                    }, 300);
                },

                                searchUsers() {
                    this.isSearching = true;
                    let params = new URLSearchParams();
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    if (this.selectedDepts.length > 0) params.append('depts', this.selectedDepts.join(','));
                    if (this.selectedRoles.length > 0) params.append('roles', this.selectedRoles.join(','));
                    if (this.selectedStatuses.length > 0) params.append('statuses', this.selectedStatuses.join(','));
                    if (this.selectedScopes.length > 0) params.append('scopes', this.selectedScopes.join(','));

                    fetch(`{{ url('/admin/users') }}?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.users = data.users;
                        this.filteredUsers = data.users;
                        this.nextPageUrl = data.next_page_url;
                        this.totalFiltered = data.total;
                        this.totalOverall = data.total_overall;
                        Object.assign(this.userRolesMap, data.user_scope_roles);
                        this.isSearching = false;
                    })
                    .catch(() => { this.isSearching = false; });
                },

                loadMoreUsers() {
                    if (!this.nextPageUrl || this.isLoadingMore) return;
                    this.isLoadingMore = true;
                    let url = this.nextPageUrl;
                    try {
                        const parsedUrl = new URL(url);
                        url = parsedUrl.pathname + parsedUrl.search;
                    } catch (e) {}
                    fetch(url, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.users = [...this.users, ...data.users];
                        this.filteredUsers = this.users;
                        this.nextPageUrl = data.next_page_url;
                        this.totalFiltered = data.total;
                        this.totalOverall = data.total_overall;
                        Object.assign(this.userRolesMap, data.user_scope_roles);
                        this.isLoadingMore = false;
                    })
                    .catch(() => { this.isLoadingMore = false; });
                },

                openAddUserModal() {
                    this.saving = false;
                    this.userModal.form = { name: '', email: '', nik: '', id_dept: '', password: '', is_active: 1 };
                    this.userModal.open = true;
                    // Reset Select2 value
                    $('#modal-dept-select').val('').trigger('change.select2');
                },

                submitUserForm() {
                    this.saving = true;
                    fetch('{{ url('/admin/users') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.userModal.form)
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.saving = false;
                        if (data.success) {
                            this.users.unshift(data.user);
                            this.filteredUsers = this.users;
                            this.totalFiltered++;
                            this.totalOverall++;
                            this.userModal.open = false;
                            this.showToast('User created successfully');
                            this.selectUser(data.user);
                        } else {
                            alert(data.message || 'Error occurred');
                        }
                    })
                    .catch(() => {
                        this.saving = false;
                        this.showToast('Operation failed', 'error');
                    });
                },

                triggerDeleteUser(user) {
                    this.deleteModal.userId = user.id;
                    this.deleteModal.userName = user.name;
                    this.deleteModal.open = true;
                },

                confirmDeleteUser() {
                    const userId = this.deleteModal.userId;
                    fetch(`{{ url('/admin/users') }}/${userId}`, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.users = this.users.filter(u => u.id != userId);
                            this.filteredUsers = this.users;
                            this.totalFiltered--;
                            this.totalOverall--;
                            this.selectedUser = null;
                            this.deleteModal.open = false;
                            this.showToast('User deleted successfully');
                        } else {
                            alert(data.message || 'Error occurred');
                        }
                    });
                },

                selectUser(user) {
                    this.selectedUser = user;
                    this.profileForm = { user_id: user.id, name: user.name || '', email: user.email || '', nik: user.nik || '', id_dept: user.id_dept || '', password: '', is_active: user.is_active ? 1 : 0 };
                    this.expandedScope = null;
                    this.isLoadingPermissions = true;
                    fetch(`{{ url('/admin/user-permissions') }}/${user.id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.availableMenus = data.menus;
                            this.availablePermissions = data.permissions;
                            this.userRolePermissions = data.role_permissions;
                            this.userOverrides = data.overrides;
                            this.userRolesMap = { ...this.userRolesMap, [user.id]: data.assignments };
                            this.isLoadingPermissions = false;
                        }).catch(() => { this.isLoadingPermissions = false; });
                },

                getDepartmentName(deptId) {
                    if (!deptId) return 'No Dept';
                    const dept = this.departments.find(d => d.id == deptId);
                    return dept ? dept.code : 'No Dept';
                },
                getUserAssignments(userId) { return this.userRolesMap[userId] || []; },
                getUserScopeBadges(user) {
                    let assignments = this.getUserAssignments(user.id);
                    return assignments.map(a => a.scope_id).filter((v, i, self) => self.indexOf(v) === i);
                },
                isScopeAssigned(scopeId) {
                    if (!this.selectedUser) return false;
                    return this.getUserAssignments(this.selectedUser.id).some(a => a.scope_id === scopeId);
                },
                // --- Global Role Helpers ---
                getRoleScope(role) {
                    return role.scope_id;
                },
                isRoleSelectedGlobal(roleId) {
                    if (!this.selectedUser) return false;
                    return this.getUserAssignments(this.selectedUser.id).some(a => a.role_id == roleId);
                },
                toggleRoleGlobal(role, checked) {
                    const scopeId = this.getRoleScope(role);
                    this.sendScopeRoleUpdate(scopeId, role.id, checked);
                },

                // --- Scope Badge Helpers (user list) ---
                getScopeShortName(scopeId) {
                    return { 'app_drawing': 'Drawing', 'app_inventory': 'Inventory', 'app_npc': 'NPC', 'app_dashboard': 'Dashboard' }[scopeId] || scopeId;
                },
                getScopeColorClass(scopeId) {
                    return { 'app_drawing': 'bg-blue-50/50 text-[#0c4da2] border-blue-200', 'app_inventory': 'bg-emerald-50 text-emerald-700 border-emerald-200', 'app_npc': 'bg-amber-50 text-amber-700 border-amber-200', 'app_dashboard': 'bg-purple-50 text-purple-700 border-purple-200' }[scopeId] || 'bg-gray-50 text-gray-700 border-gray-200';
                },

                // --- Permission Override Helpers ---
                getMenusForScope(scopeId) {
                    if (!this.availableMenus) return [];
                    return this.availableMenus.filter(m => m.scope_id === scopeId);
                },
                hasRolePermission(scopeId, menuId, permissionId) {
                    return this.userRolePermissions.some(rp => rp.scope_id === scopeId && rp.menu_id == menuId && rp.permission_id == permissionId);
                },
                getOverrideStatus(scopeId, menuId, permissionId) {
                    const found = this.userOverrides.find(o => o.scope_id === scopeId && o.menu_id == menuId && o.permission_id == permissionId);
                    return found ? found.access_type : 'INHERIT';
                },
                getEffectivePermission(scopeId, menuId, permissionId) {
                    const override = this.getOverrideStatus(scopeId, menuId, permissionId);
                    if (override !== 'INHERIT') return override;
                    return this.hasRolePermission(scopeId, menuId, permissionId) ? 'ALLOW' : 'DENY';
                },
                getEffectiveBgClass(scopeId, menuId, permissionId) {
                    return this.getEffectivePermission(scopeId, menuId, permissionId) === 'ALLOW'
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-300'
                        : 'bg-rose-50 text-rose-700 border-rose-300';
                },

                saveProfile() {
                    this.saving = true;
                    fetch('{{ url('/admin/update-profile') }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.profileForm)
                    }).then(r => r.json()).then(data => {
                        this.saving = false;
                        if (data.success) {
                            const idx = this.users.findIndex(u => u.id == this.selectedUser.id);
                            if (idx !== -1) Object.assign(this.users[idx], { name: this.profileForm.name, email: this.profileForm.email, nik: this.profileForm.nik, id_dept: this.profileForm.id_dept, is_active: this.profileForm.is_active ? 1 : 0 });
                            Object.assign(this.selectedUser, { name: this.profileForm.name, email: this.profileForm.email, nik: this.profileForm.nik, id_dept: this.profileForm.id_dept, is_active: this.profileForm.is_active ? 1 : 0 });
                            this.showToast('Profile updated successfully');
                        } else { alert(data.message || 'Error'); }
                    }).catch(() => {
                        this.saving = false;
                        alert('Operation failed');
                    });
                },


                sendScopeRoleUpdate(scopeId, roleId, status) {
                    const userId = this.selectedUser.id;
                    fetch('{{ url('/admin/update-scope-role') }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ user_id: userId, scope_id: scopeId, role_id: roleId, status: status ? 1 : 0 })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            fetch(`{{ url('/admin/user-permissions') }}/${userId}`).then(r => r.json()).then(d => {
                                this.userRolePermissions = d.role_permissions;
                                this.userOverrides = d.overrides;
                                this.userRolesMap = { ...this.userRolesMap, [userId]: d.assignments };
                            });
                            this.showToast('Access updated');
                        }
                    });
                },
                updateOverride(scopeId, menuId, permissionId, accessType) {
                    fetch('{{ url('/admin/update-user-permission') }}', {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ user_id: this.selectedUser.id, scope_id: scopeId, menu_id: menuId, permission_id: permissionId, access_type: accessType })
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            const idx = this.userOverrides.findIndex(o => o.scope_id === scopeId && o.menu_id == menuId && o.permission_id == permissionId);
                            const item = { scope_id: scopeId, menu_id: menuId, permission_id: permissionId, access_type: accessType };
                            if (idx !== -1) this.userOverrides[idx] = item; else this.userOverrides.push(item);
                            this.showToast('Override saved');
                        }
                    });
                },
                showToast(msg) {
                    const t = document.getElementById('toast');
                    document.getElementById('toast-message').innerText = msg;
                    t.classList.remove('opacity-0', 'translate-y-20');
                    setTimeout(() => t.classList.add('opacity-0', 'translate-y-20'), 3000);
                }
            };
        }
    </script>
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</x-app-layout>
