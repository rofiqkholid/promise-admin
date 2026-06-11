<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide">Master Data Configurations</h2>
    </x-slot>

    <style>
        /* Force DataTable Header styles and borders */
        .dataTables_wrapper table.dataTable {
            border-collapse: collapse !important;
            border: 1px solid #e2e8f0 !important;
            margin-top: 4px !important;
            margin-bottom: 8px !important;
        }
        .dataTables_wrapper table.dataTable thead {
            background-color: #f8fafc !important;
        }
        .dataTables_wrapper table.dataTable thead th {
            background-color: #f8fafc !important;
            border-bottom: 2px solid #cbd5e1 !important;
            border-top: 1px solid #e2e8f0 !important;
            border-left: 1px solid #e2e8f0 !important;
            border-right: 1px solid #e2e8f0 !important;
            color: #475569 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 10px !important;
            letter-spacing: 0.05em !important;
            padding: 10px 14px !important;
            text-align: center !important;
        }
        .dataTables_wrapper table.dataTable tbody td {
            border-bottom: 1px solid #e2e8f0 !important;
            border-left: 1px solid #e2e8f0 !important;
            border-right: 1px solid #e2e8f0 !important;
            padding: 8px 14px !important;
        }
        .dataTables_wrapper table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
        }
        .dataTables_wrapper .dataTables_length {
            white-space: nowrap !important;
        }
        
        /* Top search and length wrapper divider styling */
        .dataTables_wrapper .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0px !important;
            background-color: transparent !important;
            border: none !important;
        }
        
        @media (max-width: 640px) {
            .dataTables_wrapper .top {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 8px !important;
                padding-bottom: 12px !important;
            }
            .dataTables_wrapper .dataTables_filter {
                width: 100% !important;
                text-align: left !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                margin-left: 0 !important;
            }
            .dataTables_wrapper .bottom {
                flex-direction: column !important;
                align-items: center !important;
                gap: 8px !important;
            }
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            padding: 5px 10px !important;
            font-size: 11px !important;
            outline: none !important;
            background-color: #ffffff !important;
            border-radius: 2px !important;
            margin-left: 0.5em !important;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #0c4da2 !important;
            box-shadow: 0 0 0 1px #0c4da2 !important;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #cbd5e1 !important;
            padding: 4px 24px 4px 8px !important;
            font-size: 11px !important;
            outline: none !important;
            background-color: #ffffff !important;
            border-radius: 2px !important;
        }
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #0c4da2 !important;
        }
        
        /* Bottom pagination styles */
        .dataTables_wrapper .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0px !important;
            background-color: transparent !important;
            border: none !important;
            font-size: 11px !important;
            color: #64748b !important;
        }

        /* Style DataTables pagination buttons to match theme */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 8px !important;
            display: flex !important;
            align-items: center !important;
            gap: 2px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            color: #475569 !important;
            padding: 4px 9px !important;
            margin: 0 !important;
            border-radius: 2px !important;
            font-size: 11px !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0c4da2 !important;
            border-color: #0c4da2 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #083c80 !important;
            border-color: #083c80 !important;
            color: #ffffff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #cbd5e1 !important;
            cursor: not-allowed !important;
        }
    </style>

    <div class="px-3 py-3 md:px-6 md:py-6" x-data="masterConsole()">

        <!-- Toast Notification -->
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="fixed bottom-5 right-5 z-50 flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium border"
             :class="toast.type === 'success' ? 'bg-white text-gray-800 border-l-4 border-emerald-500' : 'bg-white text-gray-800 border-l-4 border-rose-500'"
             style="display: none;">
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-2 text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Custom Delete Confirmation Modal -->
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

        <!-- Department Modal (Add/Edit) -->
        <div x-show="deptModal.open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm max-h-[90vh] flex flex-col" @click.away="deptModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600" x-text="deptModal.mode === 'create' ? 'Add Department' : 'Edit Department'"></h3>
                    <button @click="deptModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitDept()" class="p-5 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Code</label>
                        <input type="text" x-model="deptModal.form.code" required placeholder="e.g. ICT"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Name</label>
                        <input type="text" x-model="deptModal.form.name" required placeholder="e.g. Information Technology"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="deptModal.open = false" :disabled="saving"
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

        <!-- Scope Modal (Add/Edit) -->
        <div x-show="scopeModal.open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm max-h-[90vh] flex flex-col" @click.away="scopeModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600" x-text="scopeModal.mode === 'create' ? 'Add Scope' : 'Edit Scope'"></h3>
                    <button @click="scopeModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitScope()" class="p-5 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Scope ID</label>
                        <input type="text" x-model="scopeModal.form.id" required placeholder="e.g. app_billing" :disabled="scopeModal.mode === 'edit'"
                               class="w-full text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800 disabled:bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Scope Name</label>
                        <input type="text" x-model="scopeModal.form.scope_name" required placeholder="e.g. Billing App"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div>
                        <label class="inline-flex items-center cursor-pointer select-none pt-1">
                            <div class="relative">
                                <input type="checkbox" x-model="scopeModal.form.is_active" class="sr-only peer">
                                <div class="w-8 h-4 bg-gray-200 rounded-full peer-checked:bg-[#0c4da2] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-gray-300 after:h-3 after:w-3 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 ml-2">Active Status</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="scopeModal.open = false" :disabled="saving"
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

        <!-- Permission Modal (Add/Edit) -->
        <div x-show="permModal.open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-sm max-h-[90vh] flex flex-col" @click.away="permModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600" x-text="permModal.mode === 'create' ? 'Add Permission Action' : 'Edit Permission Action'"></h3>
                    <button @click="permModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitPerm()" class="p-5 space-y-4 overflow-y-auto flex-1">
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Action Name</label>
                        <input type="text" x-model="permModal.form.permission_name" required placeholder="e.g. approve"
                               class="w-full text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Description</label>
                        <input type="text" x-model="permModal.form.description" placeholder="e.g. Can approve documents"
                               class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="permModal.open = false" :disabled="saving"
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

        <!-- Master Tabs Layout -->
        <div class="bg-white border border-slate-300 flex flex-col" style="min-height: calc(100vh - 120px);">
            
            <!-- Tab Headers -->
            <div class="border-b border-slate-300 flex overflow-x-auto bg-slate-50 shrink-0 h-[44px]">
                <button @click="activeTab = 'departments'"
                        class="px-6 h-full text-[11px] font-semibold tracking-wider border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'departments' ? 'text-[#0c4da2] border-[#0c4da2] bg-white font-semibold' : 'text-gray-500 border-transparent hover:bg-gray-100/50 hover:text-gray-800'">
                    <i class="fa-solid fa-building"></i> Departments
                </button>
                <button @click="activeTab = 'scopes'"
                        class="px-6 h-full text-[11px] font-semibold tracking-wider border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'scopes' ? 'text-[#0c4da2] border-[#0c4da2] bg-white font-semibold' : 'text-gray-500 border-transparent hover:bg-gray-100/50 hover:text-gray-800'">
                    <i class="fa-solid fa-layer-group"></i> Application Scopes
                </button>
                <button @click="activeTab = 'permissions'"
                        class="px-6 h-full text-[11px] font-semibold tracking-wider border-b-2 transition-all flex items-center gap-2"
                        :class="activeTab === 'permissions' ? 'text-[#0c4da2] border-[#0c4da2] bg-white font-semibold' : 'text-gray-500 border-transparent hover:bg-gray-100/50 hover:text-gray-800'">
                    <i class="fa-solid fa-key"></i> Action Permissions
                </button>
            </div>

            <!-- Tab Content: Departments -->
            <div x-show="activeTab === 'departments'" class="p-5 flex-1 flex flex-col">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-gray-800 tracking-wider">Master Departments</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Manage user organization structure groups and codes.</p>
                    </div>
                    <button @click="openAddDept()" class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add Department
                    </button>
                </div>
                <div class="p-4 bg-white border border-slate-300 relative">
                    <!-- Custom Loader Overlay -->
                    <div x-show="isLoadingDepts" class="absolute inset-0 bg-white/75 flex flex-col items-center justify-center z-10" style="display: none;">
                        <div class="text-center text-xs text-gray-400">
                            <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-[#0c4da2]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Loading departments...</span>
                        </div>
                    </div>
                    <table id="departments-table" class="w-full border-collapse text-xs display cell-border hover stripe" style="width: 100%;">
                        <thead class="bg-gray-50 border-b border-slate-300">
                            <tr>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-12">No</th>
                                <th class="text-left px-5 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-24">ID</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-40">Code</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Department Name</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: Scopes -->
            <div x-show="activeTab === 'scopes'" class="p-5 flex-1 flex flex-col" style="display: none;">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-gray-800 tracking-wider">Master Application Scopes</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Configure system modules, sub-apps, and scope bindings.</p>
                    </div>
                    <button @click="openAddScope()" class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add Scope
                    </button>
                </div>
                <div class="p-4 bg-white border border-slate-300 relative">
                    <!-- Custom Loader Overlay -->
                    <div x-show="isLoadingScopes" class="absolute inset-0 bg-white/75 flex flex-col items-center justify-center z-10" style="display: none;">
                        <div class="text-center text-xs text-gray-400">
                            <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-[#0c4da2]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Loading scopes...</span>
                        </div>
                    </div>
                    <table id="scopes-table" class="w-full border-collapse text-xs display cell-border hover stripe" style="width: 100%;">
                        <thead class="bg-gray-50 border-b border-slate-300">
                            <tr>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-12">No</th>
                                <th class="text-left px-5 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-48">Scope ID</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Scope Name</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-40">Status</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Content: Permissions -->
            <div x-show="activeTab === 'permissions'" class="p-5 flex-1 flex flex-col" style="display: none;">
                <div class="flex items-center justify-between mb-4 shrink-0">
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-gray-800 tracking-wider">Master Action Permissions</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5">Manage action keywords used across permission assignment matrices.</p>
                    </div>
                    <button @click="openAddPerm()" class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add Action
                    </button>
                </div>
                <div class="p-4 bg-white border border-slate-300 relative font-normal">
                    <!-- Custom Loader Overlay -->
                    <div x-show="isLoadingPerms" class="absolute inset-0 bg-white/75 flex flex-col items-center justify-center z-10" style="display: none;">
                        <div class="text-center text-xs text-gray-400">
                            <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-[#0c4da2]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Loading permissions...</span>
                        </div>
                    </div>
                    <table id="permissions-table" class="w-full border-collapse text-xs display cell-border hover stripe" style="width: 100%;">
                        <thead class="bg-gray-50 border-b border-slate-300">
                            <tr>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-12">No</th>
                                <th class="text-left px-5 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-24">ID</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-48">Action / Key</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Description</th>
                                <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <script>
        function masterConsole() {
            return {
                activeTab: 'departments',
                departments: [],
                scopes: [],
                permissions: [],

                deptTable: null,
                scopeTable: null,
                permTable: null,

                isLoadingDepts: false,
                isLoadingScopes: false,
                isLoadingPerms: false,
                saving: false,

                toast: { show: false, message: '', type: 'success' },
                deleteModal: { open: false, type: '', id: null, itemName: '' },
                
                deptModal: { open: false, mode: 'create', form: { id: null, code: '', name: '' } },
                scopeModal: { open: false, mode: 'create', form: { id: '', scope_name: '', is_active: true } },
                permModal: { open: false, mode: 'create', form: { id: null, permission_name: '', description: '' } },

                init() {
                    this.$nextTick(() => {
                        this.initTables();
                    });

                    this.$watch('activeTab', value => {
                        this.$nextTick(() => {
                            if (value === 'departments' && this.deptTable) this.deptTable.columns.adjust();
                            if (value === 'scopes' && this.scopeTable) this.scopeTable.columns.adjust();
                            if (value === 'permissions' && this.permTable) this.permTable.columns.adjust();
                        });
                    });
                },

                initTables() {
                    const self = this;

                    // Departments DataTable
                    this.deptTable = $('#departments-table')
                        .on('preXhr.dt', () => { this.isLoadingDepts = true; })
                        .on('draw.dt', () => { this.isLoadingDepts = false; })
                        .DataTable({
                            serverSide: true,
                            processing: false,
                            dom: '<"top"lf>r<"overflow-x-auto w-full"t><"bottom"ip>',
                            ajax: {
                                url: '{{ url('/admin/departments/ajax') }}',
                                dataSrc: 'data'
                            },
                            columns: [
                                {
                                    data: null,
                                    className: 'text-center font-medium text-gray-400 py-2 px-4 w-12',
                                    orderable: false,
                                    searchable: false,
                                    render: function (data, type, row, meta) {
                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                                { data: 'id', className: 'font-mono text-gray-400 py-2 px-4' },
                                { data: 'code', className: 'font-bold text-[#0c4da2] font-mono py-2 px-4' },
                                { data: 'name', className: 'text-gray-700 font-medium py-2 px-4' },
                                {
                                    data: null,
                                    className: 'text-center py-2 px-4',
                                    orderable: false,
                                    render: function(data, type, row) {
                                        return `<div class="flex items-center justify-center gap-2">
                                                    <button onclick="window.editDeptAjax(${row.id})" 
                                                            class="w-7 h-7 bg-blue-50 border border-blue-200 text-[#0c4da2] hover:bg-[#0c4da2] hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Edit">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <button onclick="window.deleteDeptAjax(${row.id})" 
                                                            class="w-7 h-7 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Delete">
                                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                                    </button>
                                                </div>`;
                                    }
                                }
                            ],
                            pageLength: 10,
                            ordering: false,
                            language: {
                                searchPlaceholder: "Search departments...",
                                search: ""
                            }
                        });

                    // Scopes DataTable
                    this.scopeTable = $('#scopes-table')
                        .on('preXhr.dt', () => { this.isLoadingScopes = true; })
                        .on('draw.dt', () => { this.isLoadingScopes = false; })
                        .DataTable({
                            serverSide: true,
                            processing: false,
                            dom: '<"top"lf>r<"overflow-x-auto w-full"t><"bottom"ip>',
                            ajax: {
                                url: '{{ url('/admin/scopes/ajax') }}',
                                dataSrc: 'data'
                            },
                            columns: [
                                {
                                    data: null,
                                    className: 'text-center font-medium text-gray-400 py-2 px-4 w-12',
                                    orderable: false,
                                    searchable: false,
                                    render: function (data, type, row, meta) {
                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                                { data: 'id', className: 'font-mono font-semibold text-gray-800 py-2 px-4' },
                                { data: 'scope_name', className: 'text-gray-700 font-medium py-2 px-4' },
                                {
                                    data: 'is_active',
                                    className: 'text-center py-2 px-4',
                                    render: function(data) {
                                        const badgeClass = data ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200';
                                        const text = data ? 'Active' : 'Inactive';
                                        return `<span class="text-[9px] font-bold px-2 py-0.5 border rounded-xs tracking-wider ${badgeClass}">${text}</span>`;
                                    }
                                },
                                {
                                    data: null,
                                    className: 'text-center py-2 px-4',
                                    orderable: false,
                                    render: function(data, type, row) {
                                        const escapedId = row.id.replace(/'/g, "\\'");
                                        return `<div class="flex items-center justify-center gap-2">
                                                    <button onclick="window.editScopeAjax('${escapedId}')" 
                                                            class="w-7 h-7 bg-sky-50 border border-sky-200 text-sky-600 hover:bg-sky-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Edit">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <button onclick="window.deleteScopeAjax('${escapedId}')" 
                                                            class="w-7 h-7 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Delete">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </div>`;
                                    }
                                }
                            ],
                            pageLength: 10,
                            ordering: false,
                            language: {
                                searchPlaceholder: "Search scopes...",
                                search: ""
                            }
                        });

                    // Permissions DataTable
                    this.permTable = $('#permissions-table')
                        .on('preXhr.dt', () => { this.isLoadingPerms = true; })
                        .on('draw.dt', () => { this.isLoadingPerms = false; })
                        .DataTable({
                            serverSide: true,
                            processing: false,
                            dom: '<"top"lf>r<"overflow-x-auto w-full"t><"bottom"ip>',
                            ajax: {
                                url: '{{ url('/admin/permissions/ajax') }}',
                                dataSrc: 'data'
                            },
                            columns: [
                                {
                                    data: null,
                                    className: 'text-center font-medium text-gray-400 py-2 px-4 w-12',
                                    orderable: false,
                                    searchable: false,
                                    render: function (data, type, row, meta) {
                                        return meta.row + meta.settings._iDisplayStart + 1;
                                    }
                                },
                                { data: 'id', className: 'font-mono text-gray-400 py-2 px-4' },
                                { data: 'permission_name', className: 'font-mono font-semibold text-gray-800 py-2 px-4' },
                                { data: 'description', className: 'text-gray-500 font-medium py-2 px-4', defaultContent: '—' },
                                {
                                    data: null,
                                    className: 'text-center py-2 px-4',
                                    orderable: false,
                                    render: function(data, type, row) {
                                        return `<div class="flex items-center justify-center gap-2">
                                                    <button onclick="window.editPermAjax(${row.id})" 
                                                            class="w-7 h-7 bg-sky-50 border border-sky-200 text-sky-600 hover:bg-sky-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Edit">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <button onclick="window.deletePermAjax(${row.id})" 
                                                            class="w-7 h-7 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                            title="Delete">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </div>`;
                                    }
                                }
                            ],
                            pageLength: 10,
                            ordering: false,
                            language: {
                                searchPlaceholder: "Search permissions...",
                                search: ""
                            }
                        });

                    window.editDeptAjax = (id) => {
                        const dept = self.deptTable.rows().data().toArray().find(d => d.id == id);
                        if (dept) self.openEditDept(dept);
                    };
                    window.deleteDeptAjax = (id) => {
                        const dept = self.deptTable.rows().data().toArray().find(d => d.id == id);
                        if (dept) self.triggerDelete('department', id, dept.name);
                    };

                    window.editScopeAjax = (id) => {
                        const sc = self.scopeTable.rows().data().toArray().find(s => s.id == id);
                        if (sc) self.openEditScope(sc);
                    };
                    window.deleteScopeAjax = (id) => {
                        const sc = self.scopeTable.rows().data().toArray().find(s => s.id == id);
                        if (sc) self.triggerDelete('scope', id, sc.scope_name);
                    };

                    window.editPermAjax = (id) => {
                        const p = self.permTable.rows().data().toArray().find(item => item.id == id);
                        if (p) self.openEditPerm(p);
                    };
                    window.deletePermAjax = (id) => {
                        const p = self.permTable.rows().data().toArray().find(item => item.id == id);
                        if (p) self.triggerDelete('permission', id, p.permission_name);
                    };
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 4000);
                },

                // Delete Triggers
                triggerDelete(type, id, itemName) {
                    this.deleteModal = { open: true, type, id, itemName };
                },

                confirmDelete() {
                    const type = this.deleteModal.type;
                    const id = this.deleteModal.id;
                    let baseUrl = '';

                    if (type === 'department') baseUrl = `{{ url('/admin/departments') }}/${id}`;
                    else if (type === 'scope') baseUrl = `{{ url('/admin/scopes') }}/${id}`;
                    else if (type === 'permission') baseUrl = `{{ url('/admin/permissions') }}/${id}`;

                    const url = `${baseUrl}?_method=DELETE`;

                    fetch(url, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-HTTP-Method-Override': 'DELETE'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.showToast(data.message);
                            this.deleteModal.open = false;
                            
                            if (type === 'department' && this.deptTable) this.deptTable.ajax.reload(null, false);
                            else if (type === 'scope' && this.scopeTable) this.scopeTable.ajax.reload(null, false);
                            else if (type === 'permission' && this.permTable) this.permTable.ajax.reload(null, false);
                        } else {
                            this.showToast(data.message || 'Error occurred', 'error');
                        }
                    })
                    .catch(() => this.showToast('Request failed', 'error'));
                },

                // Department CRUD
                openAddDept() {
                    this.saving = false;
                    this.deptModal = { open: true, mode: 'create', form: { id: null, code: '', name: '' } };
                },
                openEditDept(dept) {
                    this.saving = false;
                    this.deptModal = { open: true, mode: 'edit', form: { id: dept.id, code: dept.code, name: dept.name } };
                },
                submitDept() {
                    const isCreate = this.deptModal.mode === 'create';
                    const url = isCreate 
                        ? '{{ url('/admin/departments') }}' 
                        : `{{ url('/admin/departments') }}/${this.deptModal.form.id}?_method=PUT`;
                    
                    const headers = { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    };
                    if (!isCreate) {
                        headers['X-HTTP-Method-Override'] = 'PUT';
                    }

                    this.saving = true;
                    fetch(url, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(this.deptModal.form)
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.saving = false;
                        if (data.success) {
                            this.showToast(data.message);
                            this.deptModal.open = false;
                            if (this.deptTable) this.deptTable.ajax.reload(null, false);
                        } else {
                            this.showToast(data.message || 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        this.saving = false;
                        this.showToast('Request failed', 'error');
                    });
                },

                // Scope CRUD
                openAddScope() {
                    this.saving = false;
                    this.scopeModal = { open: true, mode: 'create', form: { id: '', scope_name: '', is_active: true } };
                },
                openEditScope(sc) {
                    this.saving = false;
                    this.scopeModal = { open: true, mode: 'edit', form: { id: sc.id, scope_name: sc.scope_name, is_active: sc.is_active ? true : false } };
                },
                submitScope() {
                    const isCreate = this.scopeModal.mode === 'create';
                    const url = isCreate 
                        ? '{{ url('/admin/scopes') }}' 
                        : `{{ url('/admin/scopes') }}/${this.scopeModal.form.id}?_method=PUT`;
                    
                    const headers = { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    };
                    if (!isCreate) {
                        headers['X-HTTP-Method-Override'] = 'PUT';
                    }

                    this.saving = true;
                    fetch(url, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify({
                            id: this.scopeModal.form.id,
                            scope_name: this.scopeModal.form.scope_name,
                            is_active: this.scopeModal.form.is_active ? 1 : 0
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.saving = false;
                        if (data.success) {
                            this.showToast(data.message);
                            this.scopeModal.open = false;
                            if (this.scopeTable) this.scopeTable.ajax.reload(null, false);
                        } else {
                            this.showToast(data.message || 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        this.saving = false;
                        this.showToast('Request failed', 'error');
                    });
                },

                // Permission CRUD
                openAddPerm() {
                    this.saving = false;
                    this.permModal = { open: true, mode: 'create', form: { id: null, permission_name: '', description: '' } };
                },
                openEditPerm(p) {
                    this.saving = false;
                    this.permModal = { open: true, mode: 'edit', form: { id: p.id, permission_name: p.permission_name, description: p.description } };
                },
                submitPerm() {
                    const isCreate = this.permModal.mode === 'create';
                    const url = isCreate 
                        ? '{{ url('/admin/permissions') }}' 
                        : `{{ url('/admin/permissions') }}/${this.permModal.form.id}?_method=PUT`;
                    
                    const headers = { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    };
                    if (!isCreate) {
                        headers['X-HTTP-Method-Override'] = 'PUT';
                    }

                    this.saving = true;
                    fetch(url, {
                        method: 'POST',
                        headers: headers,
                        body: JSON.stringify(this.permModal.form)
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.saving = false;
                        if (data.success) {
                            this.showToast(data.message);
                            this.permModal.open = false;
                            if (this.permTable) this.permTable.ajax.reload(null, false);
                        } else {
                            this.showToast(data.message || 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        this.saving = false;
                        this.showToast('Request failed', 'error');
                    });
                }
            };
        }
    </script>
</x-app-layout>
