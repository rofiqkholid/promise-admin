<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide ">Menus Master Catalog</h2>
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
        .dataTables_wrapper table.dataTable tbody td.menu-title-cell {
            padding-left: 0px !important;
        }
    </style>

    <div class="px-3 py-3 md:px-6 md:py-6" x-data="menuConsole()">

        <!-- Toast -->
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

        <!-- Add/Edit Menu Modal -->
        <div x-show="menuModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-lg max-h-[90vh] flex flex-col" @click.away="menuModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600" x-text="menuModal.mode === 'create' ? 'Add New Menu Item' : 'Edit Menu Item'"></h3>
                    <button @click="menuModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <form @submit.prevent="submitMenuForm()" class="p-5 overflow-y-auto flex-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Menu Title</label>
                            <input type="text" x-model="menuModal.form.title" required placeholder="e.g. Stock Opname"
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Application Scope</label>
                            <select id="scope-select" x-model="menuModal.form.scope_id" required
                                    x-init="
                                        $watch('menuModal.open', open => {
                                            if (open) {
                                                $nextTick(() => {
                                                    $('#scope-select').select2({ width: '100%', dropdownParent: $('#scope-select').parent() }).on('change', (e) => {
                                                        menuModal.form.scope_id = e.target.value;
                                                    });
                                                });
                                            }
                                        });
                                        $watch('menuModal.form.scope_id', value => {
                                            $('#scope-select').val(value).trigger('change.select2');
                                        });
                                    "
                                    class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none bg-white">
                                <template x-for="sc in scopes" :key="sc.id">
                                    <option :value="sc.id" x-text="sc.scope_name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Parent Menu</label>
                            <select id="parent-menu-select" x-model="menuModal.form.parent_id"
                                    x-init="
                                        $watch('menuModal.open', open => {
                                            if (open) {
                                                $nextTick(() => {
                                                    $('#parent-menu-select').select2({ width: '100%', dropdownParent: $('#parent-menu-select').parent() }).on('change', (e) => {
                                                        menuModal.form.parent_id = e.target.value;
                                                    });
                                                });
                                            }
                                        });
                                        $watch('menuModal.form.parent_id', value => {
                                            $('#parent-menu-select').val(value).trigger('change.select2');
                                        });
                                    "
                                    class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none bg-white">
                                <option value="">— None (Top-Level) —</option>
                                <template x-for="pm in parentMenus" :key="pm.id">
                                    <option :value="pm.id" x-text="pm.title"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Route / URL</label>
                            <input type="text" x-model="menuModal.form.route" placeholder="e.g. inventory.stock.index"
                                   class="w-full text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Icon Class</label>
                            <input type="text" x-model="menuModal.form.icon" placeholder="e.g. fa-solid fa-box"
                                   class="w-full text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Sort Order</label>
                            <input type="number" x-model="menuModal.form.sort_order" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors">
                        </div>
                        <div class="flex items-center gap-6 pt-3">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" x-model="menuModal.form.is_active"
                                       class="h-3.5 w-3.5 border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] cursor-pointer">
                                <span class="text-xs text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" x-model="menuModal.form.is_visible"
                                       class="h-3.5 w-3.5 border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2] cursor-pointer">
                                <span class="text-xs text-gray-700">Visible</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-5 pt-4 border-t border-gray-200">
                        <button type="button" @click="menuModal.open = false" :disabled="saving"
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

        <!-- Manage Scopes Modal -->
        <div x-show="scopeModal.open"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50"
             style="display: none;">
            <div class="bg-white border border-gray-300 w-full max-w-xl flex flex-col" style="max-height: 85vh;" @click.away="scopeModal.open = false">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between shrink-0">
                    <h3 class="text-xs font-semibold tracking-wider text-gray-600">Manage Application Scopes</h3>
                    <button @click="scopeModal.open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Scope ID</th>
                                <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Name</th>
                                <th class="px-4 py-2.5 text-center text-[10px] font-semibold tracking-wider text-gray-500">Status</th>
                                <th class="px-4 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="sc in allScopes" :key="sc.id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-mono font-medium text-gray-800" x-text="sc.id"></td>
                                    <td class="px-4 py-2 text-gray-600" x-text="sc.scope_name"></td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="text-[9px] font-medium px-1.5 py-0.5 border"
                                              :class="sc.is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200'"
                                              x-text="sc.is_active ? 'Active' : 'Inactive'"></span>
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <button @click="triggerDelete('scope', sc.id, sc.scope_name)" class="h-6 w-6 rounded-xs border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors ml-auto" title="Delete Scope">
                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-gray-200 p-4 shrink-0">
                    <p class="text-[10px] font-semibold tracking-wider text-gray-500 mb-2.5">Register New Scope</p>
                    <form @submit.prevent="submitScopeForm()" class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                        <input type="text" x-model="scopeModal.form.id" required placeholder="app_billing"
                               class="flex-1 text-xs border border-gray-300 py-2 px-3 font-mono focus:border-[#0c4da2] focus:outline-none transition-colors">
                        <input type="text" x-model="scopeModal.form.scope_name" required placeholder="Billing App"
                               class="flex-1 text-xs border border-gray-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors">
                        <label class="flex items-center gap-1.5 cursor-pointer whitespace-nowrap">
                            <input type="checkbox" x-model="scopeModal.form.is_active" class="h-3.5 w-3.5 border-gray-300 text-[#0c4da2] focus:ring-[#0c4da2]">
                            <span class="text-xs text-gray-600">Active</span>
                        </label>
                        <button type="submit" :disabled="saving" class="px-4 py-2 text-xs font-medium bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors whitespace-nowrap flex items-center gap-1.5 disabled:opacity-50">
                            <template x-if="saving">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="saving ? 'Adding...' : 'Add'"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white border border-slate-300 flex flex-col" style="min-height: calc(100vh - 120px);">

            <!-- Toolbar -->
            <div class="px-5 py-3 border-b border-slate-300 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0">
                <div>
                    <h3 class="text-sm md:text-base font-bold text-gray-800 tracking-wider">Application Menu Hierarchies</h3>
                    <p class="text-[10px] text-gray-500 mt-0.5">Manage sidebar routing, icons, and display configurations per scope.</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button @click="openManageScopesModal()"
                            class="px-3 h-8 text-xs font-semibold border border-slate-300 text-slate-600 bg-white hover:bg-slate-50 transition-colors rounded-xs flex items-center justify-center">
                        Manage Scopes
                    </button>
                    <button @click="openAddMenuModal()"
                            class="px-3 h-8 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors flex items-center justify-center gap-1 rounded-xs">
                        + Add Menu Item
                    </button>
                </div>
            </div>

            <!-- Scope Tabs -->
            <div class="border-b border-slate-300 flex overflow-x-auto bg-slate-50 shrink-0 h-[44px]">
                <template x-for="sc in scopes" :key="sc.id">
                    <button @click="currentScopeId = sc.id"
                            class="px-6 h-full text-[11px] font-semibold tracking-wider border-b-2 transition-all flex items-center gap-2"
                            :class="currentScopeId === sc.id
                                ? 'text-[#0c4da2] border-[#0c4da2] bg-white font-semibold'
                                : 'text-gray-500 border-transparent hover:bg-gray-100/50 hover:text-gray-800'"
                            x-text="sc.scope_name">
                    </button>
                </template>
            </div>

            <!-- Menu Table -->
            <div class="p-4 bg-white relative">
                <!-- Custom Loader Overlay -->
                <div x-show="isLoadingMenus" class="absolute inset-0 bg-white/75 flex flex-col items-center justify-center z-10" style="display: none;">
                    <div class="text-center text-xs text-gray-400">
                        <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-[#0c4da2]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Loading menus...</span>
                    </div>
                </div>
                <table id="menus-table" class="w-full border-collapse text-xs display cell-border hover stripe" style="width: 100%;">
                    <thead class="bg-gray-50 border-b border-slate-300">
                        <tr>
                            <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500 w-12">No</th>
                            <th class="text-left px-5 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Title</th>
                            <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Route</th>
                            <th class="text-left px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Icon</th>
                            <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Order</th>
                            <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Status</th>
                            <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Visible</th>
                            <th class="text-center px-4 py-2.5 text-[10px] font-semibold tracking-wider text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Populated by DataTables AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function menuConsole() {
            return {
                menus: @json($menus),
                scopes: @json($scopes),
                parentMenus: @json($parentMenus),
                allScopes: @json($allScopes),

                currentScopeId: 'app_drawing',
                toast: { show: false, message: '', type: 'success' },
                dataTable: null,
                isLoadingMenus: false,
                saving: false,

                menuModal: {
                    open: false,
                    mode: 'create',
                    form: { id: null, title: '', route: '', icon: '', sort_order: 0, parent_id: '', scope_id: '', is_active: true, is_visible: true }
                },
                scopeModal: { open: false, form: { id: '', scope_name: '', is_active: true } },
                deleteModal: { open: false, type: '', id: null, itemName: '' },

                init() {
                    if (this.scopes.length > 0) this.currentScopeId = this.scopes[0].id;

                    this.$nextTick(() => {
                        this.initDataTable();
                    });

                    this.$watch('currentScopeId', value => {
                        if (this.dataTable) {
                            this.dataTable.ajax.url(`{{ url('/admin/menus/ajax') }}?scope_id=${value}`).load();
                        }
                    });
                },

                initDataTable() {
                    const self = this;
                    this.dataTable = $('#menus-table')
                        .on('preXhr.dt', () => { this.isLoadingMenus = true; })
                        .on('draw.dt', () => { this.isLoadingMenus = false; })
                        .DataTable({
                            serverSide: true,
                            processing: false,
                            dom: '<"top"lf>r<"overflow-x-auto w-full"t><"bottom"ip>',
                            ajax: {
                                url: `{{ url('/admin/menus/ajax') }}?scope_id=${this.currentScopeId}`,
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
                            {
                                data: 'title',
                                className: 'menu-title-cell',
                                render: function(data, type, row) {
                                    const indent = row.parent_id ? 'padding-left: 32px' : 'padding-left: 20px';
                                    const prefix = row.parent_id ? '<span class="text-gray-300 select-none text-[10px] mr-2">└</span>' : '';
                                    const iconHtml = row.icon ? `<i class="${row.icon} text-slate-400 text-[11px] shrink-0 mr-2"></i>` : `<i class="fa-solid fa-bars text-slate-400 text-[11px] shrink-0 mr-2"></i>`;
                                    return `<div style="${indent}" class="flex items-center">${prefix}${iconHtml}<span>${data}</span></div>`;
                                }
                            },
                            {
                                data: 'route',
                                defaultContent: '—',
                                render: function(data) {
                                    return `<span class="font-mono text-gray-500">${data || '—'}</span>`;
                                }
                            },
                            {
                                data: 'icon',
                                defaultContent: '—',
                                render: function(data) {
                                    if (!data) return '<span class="font-mono text-slate-400">—</span>';
                                    return `<div class="flex items-center gap-2">
                                                <i class="${data} text-slate-500 text-xs shrink-0"></i>
                                                <span class="font-mono text-gray-500">${data}</span>
                                            </div>`;
                                }
                            },
                            { data: 'sort_order', className: 'text-center' },
                            {
                                data: 'is_active',
                                className: 'text-center',
                                render: function(data) {
                                    const badgeClass = data ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200';
                                    const text = data ? 'Active' : 'Inactive';
                                    return `<span class="text-[9px] font-medium px-1.5 py-0.5 border ${badgeClass}">${text}</span>`;
                                }
                            },
                            {
                                data: 'is_visible',
                                className: 'text-center',
                                render: function(data) {
                                    const badgeClass = data ? 'bg-blue-50 text-[#0c4da2] border-blue-200' : 'bg-gray-100 text-gray-600 border-gray-200';
                                    const text = data ? 'Visible' : 'Hidden';
                                    return `<span class="text-[9px] font-medium px-1.5 py-0.5 border ${badgeClass}">${text}</span>`;
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                orderable: false,
                                render: function(data, type, row) {
                                    return `<div class="flex items-center justify-center gap-2">
                                                <button onclick="window.editMenuAjax(${row.id})" 
                                                        class="w-7 h-7 bg-blue-50 border border-blue-200 text-[#0c4da2] hover:bg-[#0c4da2] hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                        title="Edit Menu">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </button>
                                                <button onclick="window.deleteMenuAjax(${row.id})" 
                                                        class="w-7 h-7 bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white transition-colors flex items-center justify-center rounded-xs cursor-pointer" 
                                                        title="Delete Menu">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </div>`;
                                }
                            }
                        ],
                        pageLength: 10,
                        ordering: false,
                        language: {
                            searchPlaceholder: "Search menus...",
                            search: ""
                        }
                    });

                    window.editMenuAjax = (id) => {
                        const menu = self.dataTable.rows().data().toArray().find(m => m.id == id);
                        if (menu) {
                            self.openEditMenuModal(menu);
                        }
                    };
                    window.deleteMenuAjax = (id) => {
                        const menu = self.dataTable.rows().data().toArray().find(m => m.id == id);
                        self.triggerDelete('menu', id, menu ? menu.title : 'this menu item');
                    };
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => { this.toast.show = false; }, 4000);
                },

                getScopeMenus() {
                    return this.menus.filter(m => m.scope_id === this.currentScopeId);
                },

                openAddMenuModal() {
                    this.saving = false;
                    this.menuModal = {
                        open: true, mode: 'create',
                        form: { id: null, title: '', route: '', icon: '', sort_order: this.getScopeMenus().length + 1, parent_id: '', scope_id: this.currentScopeId, is_active: true, is_visible: true }
                    };
                },

                openEditMenuModal(menu) {
                    this.saving = false;
                    this.menuModal = {
                        open: true, mode: 'edit',
                        form: { id: menu.id, title: menu.title, route: menu.route, icon: menu.icon, sort_order: menu.sort_order, parent_id: menu.parent_id || '', scope_id: menu.scope_id, is_active: !!menu.is_active, is_visible: !!menu.is_visible }
                    };
                },

                submitMenuForm() {
                    const isCreate = this.menuModal.mode === 'create';
                    const formData = { ...this.menuModal.form, is_active: this.menuModal.form.is_active ? 1 : 0, is_visible: this.menuModal.form.is_visible ? 1 : 0 };
                    
                    this.saving = true;
                    const url = isCreate 
                        ? '{{ url('/admin/menus') }}' 
                        : `{{ url('/admin/menus') }}/${this.menuModal.form.id}?_method=PUT`;
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
                        body: JSON.stringify(formData)
                    }).then(r => r.json()).then(data => {
                        this.saving = false;
                        if (data.success) { this.showToast(data.message); this.menuModal.open = false; window.location.reload(); }
                        else this.showToast(data.message || 'Error', 'error');
                    }).catch(() => {
                        this.saving = false;
                        this.showToast('Failed to save', 'error');
                    });
                },



                openManageScopesModal() {
                    this.saving = false;
                    this.scopeModal = { open: true, form: { id: '', scope_name: '', is_active: true } };
                },

                submitScopeForm() {
                    const formData = { ...this.scopeModal.form, is_active: this.scopeModal.form.is_active ? 1 : 0 };
                    
                    this.saving = true;
                    fetch('{{ url('/admin/scopes') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(formData)
                    }).then(r => r.json()).then(data => {
                        this.saving = false;
                        if (data.success) { this.showToast(data.message); this.scopeModal.open = false; window.location.reload(); }
                        else this.showToast(data.message || 'Error', 'error');
                    }).catch(() => {
                        this.saving = false;
                        this.showToast('Failed to add scope', 'error');
                    });
                },

                triggerDelete(type, id, itemName) {
                    this.deleteModal.type = type;
                    this.deleteModal.id = id;
                    this.deleteModal.itemName = itemName;
                    this.deleteModal.open = true;
                },

                confirmDelete() {
                    const type = this.deleteModal.type;
                    const id = this.deleteModal.id;
                    const baseUrl = type === 'menu' ? `{{ url('/admin/menus') }}/${id}` : `{{ url('/admin/scopes') }}/${id}`;
                    const url = `${baseUrl}?_method=DELETE`;
                    fetch(url, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'DELETE'
                        }
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            this.showToast(data.message);
                            this.deleteModal.open = false;
                            window.location.reload();
                        } else {
                            this.showToast(data.message || 'Deletion failed', 'error');
                        }
                    }).catch(() => this.showToast('Failed to delete', 'error'));
                },


            };
        }
    </script>
</x-app-layout>
