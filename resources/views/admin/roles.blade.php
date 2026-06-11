<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide">Roles & Permission Matrix</h2>
    </x-slot>

    <div class="px-3 py-3 md:px-6 md:py-6" x-data="roleConsole()">

        <!-- Toast -->
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="fixed bottom-5 right-5 z-50 flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium border-l-4"
             :class="toast.type === 'success' ? 'bg-white text-gray-800 border-emerald-500 border border-l-4' : 'bg-white text-gray-800 border-rose-500 border border-l-4'"
             style="display: none;">
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModal.open"
             class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm" @click.away="deleteModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600">Confirm Delete</h3>
                    <button @click="deleteModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <div class="p-5">
                    <p class="text-xs text-gray-600">Are you sure you want to delete <strong class="text-gray-800" x-text="deleteModal.itemName"></strong>? This action cannot be undone.</p>
                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button" @click="deleteModal.open = false"
                                class="px-4 py-2 text-xs font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="button" @click="confirmDelete()"
                                class="px-4 py-2 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white transition-colors">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Role Modal -->
        <div x-show="roleModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm max-h-[90vh] flex flex-col" @click.away="roleModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600" x-text="roleModal.mode === 'create' ? 'Add New Role' : 'Edit Role'"></h3>
                    <button @click="roleModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitRoleForm()" class="p-5 overflow-y-auto flex-1 space-y-4">
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Role Name</label>
                        <input type="text" x-model="roleModal.form.role_name" required placeholder="e.g. Inv Operator"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Application Scope</label>
                        <select x-model="roleModal.form.scope_id"
                                class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                            <option value="">— Global / All Scopes —</option>
                            <template x-for="sc in scopes" :key="sc.id">
                                <option :value="sc.id" x-text="sc.scope_name" :selected="roleModal.form.scope_id === sc.id"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Description</label>
                        <textarea x-model="roleModal.form.description" placeholder="e.g. Access permissions for drawing users"
                                  class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800" rows="2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="roleModal.open = false" :disabled="savingForm"
                                class="px-4 py-2 text-xs font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="savingForm"
                                class="px-4 py-2 text-xs font-medium bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center gap-1.5 disabled:opacity-50">
                            <template x-if="savingForm">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="savingForm ? 'Submitting...' : 'Submit'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Manage Permissions Modal -->
        <div x-show="permissionModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-lg flex flex-col" style="max-height: 85vh;" @click.away="permissionModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between shrink-0">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600">Manage Permission Actions</h3>
                    <button @click="permissionModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Name</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Description</th>
                                <th class="px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="p in permissions" :key="p.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-mono font-medium text-gray-800" x-text="p.permission_name"></td>
                                    <td class="px-4 py-2 text-gray-500" x-text="p.description || '—'"></td>
                                    <td class="px-4 py-2 text-right">
                                        <button @click="triggerDeletePermission(p)" class="h-6 w-6 rounded-xs border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors ml-auto" title="Delete Action">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 p-4 shrink-0">
                    <p class="text-[10px] font-semibold tracking-wider text-gray-500 mb-2.5">Add New Permission</p>
                    <form @submit.prevent="submitPermissionForm()" class="flex flex-col sm:flex-row gap-2">
                        <input type="text" x-model="permissionModal.form.permission_name" required placeholder="e.g. approve" :disabled="savingForm"
                               class="flex-1 text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors disabled:opacity-50">
                        <input type="text" x-model="permissionModal.form.description" placeholder="Description" :disabled="savingForm"
                               class="flex-1 text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors disabled:opacity-50">
                        <button type="submit" :disabled="savingForm" class="px-4 py-2 text-xs font-medium bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors whitespace-nowrap flex items-center gap-1.5 disabled:opacity-50">
                            <template x-if="savingForm">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="savingForm ? 'Adding...' : 'Add'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 border border-slate-300 min-h-[calc(100vh-120px)]">

            <!-- Left Panel: Roles List -->
            <div class="lg:col-span-4 border-r border-slate-300 bg-white flex-col lg:h-[calc(100vh-120px)]"
                 :class="selectedRole ? 'hidden lg:flex' : 'flex h-[calc(100vh-120px)] lg:h-[calc(100vh-120px)]'">
                
                <div class="h-[56px] px-3 border-b border-slate-300 bg-slate-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-1.5 flex-1 min-w-0 mr-2">
                        <span class="text-[10px] font-bold text-slate-500 tracking-wider shrink-0 hidden sm:inline mr-1">Filter:</span>
                        
                        <!-- Scope Filter Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" 
                                    class="h-7 px-2 border rounded-sm flex items-center justify-center gap-1 transition-colors text-[10px]"
                                    :class="selectedScopeFilter.length > 0 ? 'bg-blue-50/50 border-blue-200 text-[#0c4da2] font-semibold' : 'bg-white border-slate-300 text-gray-600 hover:bg-gray-50'"
                                    title="Filter by Scope">
                                <i class="fa-solid fa-cubes text-[10px]"></i>
                                <span class="font-medium">Scope</span>
                                <template x-if="selectedScopeFilter.length > 0">
                                    <span class="ml-0.5 bg-blue-100 text-blue-800 text-[8px] px-1 rounded-full font-bold" x-text="selectedScopeFilter.length"></span>
                                </template>
                            </button>
                            <div x-show="open" 
                                 class="absolute left-0 mt-1 w-44 bg-white border border-slate-300 shadow-md rounded-sm py-1 text-xs custom-scrollbar"
                                 style="max-height: 12rem; overflow-y: auto; z-index: 9999; display: none;">
                                <div class="px-2 pb-1.5 mb-1 border-b border-slate-200 flex justify-between items-center">
                                    <span class="text-[9px] font-bold text-slate-400 ">Scope Filter</span>
                                    <button @click="selectedScopeFilter = []; filterRoles(); open = false;" class="text-[9px] text-rose-500 hover:text-rose-700 font-semibold transition-colors">Reset</button>
                                </div>
                                <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                    <input type="checkbox" value="global" x-model="selectedScopeFilter" @change="filterRoles()" class="rounded border-slate-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                    <span class="text-[11px] text-gray-700">Global</span>
                                </label>
                                <template x-for="sc in scopes" :key="sc.id">
                                    <label class="flex items-center px-3 py-1.5 hover:bg-gray-50 cursor-pointer select-none">
                                        <input type="checkbox" :value="sc.id" x-model="selectedScopeFilter" @change="filterRoles()" class="rounded border-slate-300 text-[#0c4da2] focus:ring-[#0c4da2] h-3.5 w-3.5 mr-2">
                                        <span class="text-[11px] text-gray-700 truncate" x-text="sc.scope_name.replace(' Management', '')"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button @click="openManagePermissionsModal()"
                                class="px-3 h-8 text-xs font-semibold border border-slate-300 text-slate-600 bg-white hover:bg-slate-50 transition-colors flex items-center justify-center gap-1 rounded-xs">
                            Permissions
                        </button>
                        <button @click="openAddRoleModal()"
                                class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs">
                            <i class="fa-solid fa-plus text-[9px]"></i> Add
                        </button>
                    </div>
                </div>

                <!-- Search -->
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
                               @input="filterRoles()"
                               placeholder="Search role name..."
                               class="flex-1 py-1 text-xs outline-none bg-transparent text-gray-700 placeholder-gray-400 border-none focus:ring-0 focus:outline-none">
                    </div>
                </div>

                <!-- Count Header -->
                <div class="h-[40px] px-4 bg-slate-50 border-b border-slate-300 flex justify-between items-center text-[10px] text-gray-500 font-bold tracking-wider select-none shrink-0">
                    <span>Role List</span>
                    <span class="text-slate-400 lowercase normal-case font-medium" x-text="roles.length + ' roles listed'"></span>
                </div>

                <!-- Role List -->
                <div class="flex-1 overflow-y-auto custom-scrollbar divide-y divide-gray-100 bg-white"
                     @scroll="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 20) { loadMoreRoles() }">
                    <template x-for="role in roles" :key="role.id">
                        <div @click="selectRole(role)"
                             class="px-4 py-3 cursor-pointer transition-all flex items-start justify-between border-l-4 border-y group"
                             :class="selectedRole && selectedRole.id === role.id ? 'bg-blue-50 border-l-[#0c4da2] border-y-blue-100 relative z-10' : 'border-l-transparent border-y-transparent hover:bg-gray-50/50'">
                            <div class="flex items-start gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 bg-[#0c4da2] text-white flex items-center justify-center shrink-0 font-bold text-xs rounded-xs border border-[#083c80]">
                                    <i class="fa-solid fa-user-shield text-[11px]"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-gray-900 truncate" x-text="role.role_name"></p>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5" x-text="role.description || '-'"></p>
                                    <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                        <span class="text-[9px] text-gray-400 font-mono font-medium" x-text="'ID: ' + role.id"></span>
                                        <span class="text-[9px] font-semibold px-1.5 py-0.5 border"
                                              :class="getRoleScopeBadgeClass(role.scope_id)"
                                              x-text="getRoleScopeName(role.scope_id)"></span>
                                        <span class="text-[9px] text-slate-500 font-semibold flex items-center gap-1 bg-slate-100/80 px-1.5 py-0.5 border border-slate-200 rounded-xs" title="Total Users assigned to this Role">
                                            <i class="fa-solid fa-users text-[8px] text-slate-400"></i>
                                            <span x-text="(role.total_users || 0) + ' Users'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Actions Hover -->
                            <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity shrink-0 ml-3">
                                 <button @click.stop="openEditRoleModal(role)" 
                                         class="w-7 h-7 bg-blue-50 border border-blue-200 text-[#0c4da2] hover:bg-[#0c4da2] hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer"
                                         title="Edit Role">
                                     <i class="fa-solid fa-pen text-[10px]"></i>
                                 </button>
                                 <button @click.stop="triggerDeleteRole(role)" 
                                         class="w-7 h-7 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer"
                                         title="Delete Role">
                                     <i class="fa-solid fa-trash-can text-[10px]"></i>
                                 </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="roles.length === 0" class="p-8 text-center text-xs text-gray-400">No roles defined yet.</div>
                    
                    <!-- Loading / Load More -->
                    <div x-show="nextPageUrl" class="p-3 text-center border-t border-gray-100">
                        <button type="button" @click="loadMoreRoles()" :disabled="isLoadingMore"
                                class="w-full text-xs font-semibold py-2 border border-slate-300 text-slate-600 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center justify-center gap-1.5">
                            <template x-if="isLoadingMore">
                                <svg class="animate-spin h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="isLoadingMore ? 'Loading more roles...' : 'Load More Roles'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Permission Matrix -->
            <div class="lg:col-span-8 flex-col bg-white lg:h-[calc(100vh-120px)]"
                 :class="selectedRole ? 'flex h-[calc(100vh-120px)] lg:h-[calc(100vh-120px)]' : 'hidden lg:flex'">

                <!-- Empty State -->
                <div x-show="!selectedRole" class="flex-1 flex flex-col items-center justify-center text-center bg-gray-50">
                    <div class="w-12 h-12 border-2 border-gray-200 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-700">Select a Role</p>
                    <p class="text-xs text-gray-400 mt-1 max-w-xs">Choose a role from the catalog to configure its menu-level permissions per application scope.</p>
                </div>

                <!-- Matrix Panel -->
                <div x-show="selectedRole" class="flex flex-col h-full lg:overflow-hidden" style="display: none;">

                    <!-- Mobile Top Actions Bar (Back) -->
                    <div class="flex lg:hidden items-center justify-between px-4 py-2 border-b border-slate-200 bg-white">
                        <button @click="selectedRole = null" class="h-8 px-2.5 rounded-xs border border-slate-300 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-colors gap-1.5 text-xs font-semibold select-none" title="Back to List">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <span>Back</span>
                        </button>
                    </div>

                    <!-- Panel Header -->
                    <div class="h-[56px] border-b border-slate-300 bg-slate-50 flex flex-col justify-center px-4 shrink-0">
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm md:text-base font-bold text-slate-800 truncate" x-text="selectedRole ? selectedRole.role_name : ''"></h3>
                                    <span class="text-[10px] font-semibold text-slate-500 bg-slate-200/60 px-2 py-0.5 rounded-xs shrink-0" x-show="selectedRole" x-text="(selectedRole.total_users || 0) + ' Users'"></span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5" x-text="selectedRole && selectedRole.description ? selectedRole.description : 'No description provided'"></p>
                            </div>
                            <div class="text-[10px] font-bold text-[#0c4da2] flex items-center gap-1 shrink-0">
                                <i class="fa-solid fa-cubes"></i>
                                <span x-text="getRoleScopeName(selectedRole ? selectedRole.scope_id : null)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Scope Tabs -->
                    <div class="h-[52px] border-b border-slate-300 flex items-center bg-white shrink-0 overflow-x-auto">
                        <template x-for="sc in scopes.filter(s => !selectedRole || !selectedRole.scope_id || selectedRole.scope_id === s.id)" :key="sc.id">
                            <button @click="changeScope(sc.id)"
                                    class="h-full px-4 text-[11px] font-semibold tracking-wider border-b-2 transition-colors whitespace-nowrap flex items-center gap-1.5"
                                    :class="currentScopeId === sc.id
                                        ? 'text-[#0c4da2] border-[#0c4da2] bg-blue-50/50 font-semibold'
                                        : 'text-gray-500 border-transparent hover:text-gray-800 hover:border-gray-300'">
                                <span x-text="sc.scope_name"></span>
                                <template x-if="currentScopeId === sc.id">
                                    <span class="bg-blue-100 text-blue-800 text-[9px] px-1.5 py-0.5 rounded-full font-bold leading-none" x-text="isLoadingPermissions ? '...' : rolePermissions.length"></span>
                                </template>
                            </button>
                        </template>
                    </div>

                    <!-- Table -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar overflow-x-auto">
                        <template x-if="isLoadingPermissions">
                            <div class="py-12 text-center text-xs text-gray-400">
                                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-[#0c4da2]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Loading permissions...</span>
                            </div>
                        </template>
                        <template x-if="!isLoadingPermissions">
                            <table class="w-full border-collapse text-xs">
                                <thead class="sticky top-0 bg-slate-50 border-b border-slate-300 z-10">
                                    <tr class="h-[40px]">
                                        <th class="text-left px-5 text-[10px] font-bold tracking-wider text-gray-500 w-1/3">Menu / Page</th>
                                        <template x-for="p in permissions" :key="p.id">
                                            <th class="px-3 text-center text-[10px] font-semibold tracking-wider text-gray-500" x-text="p.permission_name"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="menu in getScopeMenus()" :key="menu.id">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-2.5 text-xs text-gray-700 whitespace-nowrap"
                                                :style="'padding-left: ' + (menu.parent_id ? '32px' : '20px')">
                                                <div class="flex items-center gap-2">
                                                    <span x-show="menu.parent_id" class="text-gray-300 text-[10px]">└</span>
                                                    <template x-if="menu.icon">
                                                        <i :class="menu.icon + ' text-slate-400 text-xs shrink-0'"></i>
                                                    </template>
                                                    <span x-text="menu.title"></span>
                                                </div>
                                            </td>
                                             <template x-for="p in permissions" :key="p.id">
                                                 <td class="px-3 py-2.5 text-center">
                                                     <label class="inline-flex items-center cursor-pointer">
                                                         <div class="relative">
                                                             <input type="checkbox"
                                                                    :checked="hasMatrixPermission(menu.id, p.id)"
                                                                    @change="toggleMatrixPermission(menu.id, p.id)"
                                                                    class="sr-only peer">
                                                             <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                                                         </div>
                                                     </label>
                                                 </td>
                                             </template>
                                        </tr>
                                    </template>
                                    <template x-if="getScopeMenus().length === 0">
                                        <tr>
                                            <td :colspan="1 + permissions.length" class="py-8 text-center text-xs text-gray-400 italic">
                                                No menus configured for this scope.
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </template>
                    </div>

                    <!-- Save Footer -->
                    <div class="px-5 py-3 border-t border-gray-200 bg-gray-50 flex justify-end shrink-0">
                        <button @click="savePermissions()"
                                :disabled="saving"
                                class="px-4 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors disabled:opacity-50 flex items-center justify-center gap-2 rounded-xs">
                            <template x-if="saving">
                                <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            Save Permission Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function roleConsole() {
            return {
                roles: @json($roles->items()),
                scopes: @json($scopes),
                permissions: @json($permissions),
                menusByScope: @json($menusByScope),

                searchQuery: '',
                selectedScopeFilter: [],
                nextPageUrl: '{{ $roles->nextPageUrl() }}',
                isLoadingMore: false,
                isSearching: false,
                searchTimeout: null,

                selectedRole: null,
                currentScopeId: 'app_drawing',
                rolePermissions: [],
                isLoadingPermissions: false,
                saving: false,
                savingForm: false,
                toast: { show: false, message: '', type: 'success' },
                roleModal: { open: false, mode: 'create', form: { id: null, role_name: '', scope_id: '', description: '' } },
                deleteModal: { open: false, type: '', id: null, itemName: '' },
                permissionModal: { open: false, form: { permission_name: '', description: '' } },

                init() {
                    if (this.scopes.length > 0) this.currentScopeId = this.scopes[0].id;
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 4000);
                },

                filterRoles() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.searchRoles();
                    }, 300);
                },

                searchRoles() {
                    this.isSearching = true;
                    fetch(`{{ url('/admin/roles') }}?search=${encodeURIComponent(this.searchQuery)}&scope=${encodeURIComponent(this.selectedScopeFilter.join(','))}`, {
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.roles = data.roles;
                        this.nextPageUrl = data.next_page_url;
                        this.isSearching = false;
                    })
                    .catch(() => { this.isSearching = false; });
                },

                loadMoreRoles() {
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
                        this.roles = [...this.roles, ...data.roles];
                        this.nextPageUrl = data.next_page_url;
                        this.isLoadingMore = false;
                    })
                    .catch(() => { this.isLoadingMore = false; });
                },

                getScopeMenus() { return this.menusByScope[this.currentScopeId] || []; },
                selectRole(role) { 
                    this.selectedRole = role; 
                    this.rolePermissions = []; // Clear immediately to avoid stale state
                    if (role.scope_id) {
                        this.currentScopeId = role.scope_id;
                    }
                    this.fetchRolePermissions(); 
                },
                changeScope(scopeId) { 
                    this.currentScopeId = scopeId; 
                    this.rolePermissions = []; // Clear immediately to avoid stale state
                    if (this.selectedRole) this.fetchRolePermissions(); 
                },

                fetchRolePermissions() {
                    if (!this.selectedRole) return;
                    this.isLoadingPermissions = true;
                    fetch(`{{ url('/admin/roles') }}/${this.selectedRole.id}/permissions/${this.currentScopeId}`)
                        .then(r => r.json()).then(data => { 
                            this.rolePermissions = data.role_permissions; 
                            this.isLoadingPermissions = false;
                        })
                        .catch(() => {
                            this.isLoadingPermissions = false;
                            this.showToast('Failed to fetch permissions', 'error');
                        });
                },

                hasMatrixPermission(menuId, permissionId) {
                    return this.rolePermissions.some(rp => rp.menu_id == menuId && rp.permission_id == permissionId);
                },
                toggleMatrixPermission(menuId, permissionId) {
                    const idx = this.rolePermissions.findIndex(rp => rp.menu_id == menuId && rp.permission_id == permissionId);
                    if (idx > -1) this.rolePermissions.splice(idx, 1);
                    else this.rolePermissions.push({ menu_id: menuId, permission_id: permissionId });
                },

                savePermissions() {
                    if (!this.selectedRole) return;
                    this.saving = true;
                    fetch('{{ url('/admin/roles/permissions') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ role_id: this.selectedRole.id, scope_id: this.currentScopeId, permissions: this.rolePermissions })
                    }).then(r => r.json()).then(data => {
                        this.saving = false;
                        this.showToast(data.message, data.success ? 'success' : 'error');
                    }).catch(() => { this.saving = false; this.showToast('Failed to save', 'error'); });
                },

                openAddRoleModal() {
                    this.savingForm = false;
                    this.roleModal = { open: true, mode: 'create', form: { id: null, role_name: '', scope_id: '', description: '' } };
                },
                openEditRoleModal(role) {
                    this.savingForm = false;
                    this.roleModal = { open: true, mode: 'edit', form: { id: role.id, role_name: role.role_name, scope_id: role.scope_id || '', description: role.description || '' } };
                },

                submitRoleForm() {
                    const isCreate = this.roleModal.mode === 'create';
                    
                    this.savingForm = true;
                    const url = isCreate 
                        ? '{{ url('/admin/roles') }}' 
                        : `{{ url('/admin/roles') }}/${this.roleModal.form.id}?_method=PUT`;
                    const headers = { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                    };
                    if (!isCreate) {
                        headers['X-HTTP-Method-Override'] = 'PUT';
                    }

                    fetch(url, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({ 
                            role_name: this.roleModal.form.role_name,
                            scope_id: this.roleModal.form.scope_id || null,
                            description: this.roleModal.form.description || null
                        })
                    }).then(r => r.json()).then(data => {
                        this.savingForm = false;
                        if (data.success) { this.showToast(data.message); this.roleModal.open = false; window.location.reload(); }
                        else this.showToast(data.message || 'Error', 'error');
                    }).catch(() => {
                        this.savingForm = false;
                        this.showToast('Operation failed', 'error');
                    });
                },

                getRoleScopeName(scopeId) {
                    if (!scopeId) return 'Global';
                    const sc = this.scopes.find(s => s.id === scopeId);
                    return sc ? sc.scope_name.replace(' Management', '') : scopeId;
                },
                getScopeShortName(scopeId) {
                    if (scopeId === 'global') return 'Global';
                    return { 'app_drawing': 'Drawing', 'app_inventory': 'Inventory', 'app_npc': 'NPC', 'app_dashboard': 'Dashboard' }[scopeId] || scopeId;
                },
                getRoleScopeBadgeClass(scopeId) {
                    if (!scopeId) return 'bg-slate-100 text-slate-700 border-slate-200';
                    return {
                        'app_drawing': 'bg-blue-50 text-blue-700 border-blue-200',
                        'app_inventory': 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'app_npc': 'bg-amber-50 text-amber-700 border-amber-200',
                        'app_dashboard': 'bg-purple-50 text-purple-700 border-purple-200'
                    }[scopeId] || 'bg-gray-50 text-gray-700 border-gray-200';
                },



                openManagePermissionsModal() {
                    this.savingForm = false;
                    this.permissionModal = { open: true, form: { permission_name: '', description: '' } };
                },

                triggerDeleteRole(role) {
                    this.deleteModal.type = 'role';
                    this.deleteModal.id = role.id;
                    this.deleteModal.itemName = role.role_name;
                    this.deleteModal.open = true;
                },

                triggerDeletePermission(permission) {
                    this.deleteModal.type = 'permission';
                    this.deleteModal.id = permission.id;
                    this.deleteModal.itemName = permission.permission_name;
                    this.deleteModal.open = true;
                },

                confirmDelete() {
                    const type = this.deleteModal.type;
                    const id = this.deleteModal.id;
                    const baseUrl = type === 'role' ? `{{ url('/admin/roles') }}/${id}` : `{{ url('/admin/permissions') }}/${id}`;
                    const url = `${baseUrl}?_method=DELETE`;
                    fetch(url, { 
                        method: 'POST', 
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'DELETE'
                        } 
                    })
                        .then(r => r.json()).then(data => {
                            if (data.success) { this.showToast(data.message); this.deleteModal.open = false; window.location.reload(); }
                            else this.showToast(data.message, 'error');
                        }).catch(() => this.showToast('Deletion failed', 'error'));
                },

                submitPermissionForm() {
                    this.savingForm = true;
                    fetch('{{ url('/admin/permissions') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(this.permissionModal.form)
                    }).then(r => r.json()).then(data => {
                        this.savingForm = false;
                        if (data.success) { this.showToast(data.message); this.permissionModal.open = false; window.location.reload(); }
                        else this.showToast(data.message || 'Error', 'error');
                    }).catch(() => {
                        this.savingForm = false;
                        this.showToast('Failed to add permission', 'error');
                    });
                }
            };
        }
    </script>
</x-app-layout>
