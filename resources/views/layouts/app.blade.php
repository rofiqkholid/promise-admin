<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PROMISE Admin') }} — Admin Console</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- jQuery & Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <style>
            body { font-family: 'Inter', sans-serif; }
            .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

            /* Select2 Flat Custom Style */
            .select2-container--default .select2-selection--single {
                border-color: #e5e7eb !important;
                border-radius: 0px !important;
                height: 34px !important;
                font-size: 0.75rem !important;
                display: flex !important;
                align-items: center !important;
                background-color: #fff !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #1f2937 !important;
                padding-left: 12px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 32px !important;
            }
            .select2-dropdown {
                border-color: #e5e7eb !important;
                border-radius: 0px !important;
                font-size: 0.75rem !important;
                box-shadow: none !important;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #0284c7 !important;
            }

            /* DataTables Flat Style Overrides */
            .dataTables_wrapper .dataTables_length select {
                border: 1px solid #cbd5e1 !important;
                border-radius: 0px !important;
                padding: 4px 24px 4px 8px !important;
                font-size: 0.75rem !important;
                background-color: #fff !important;
                outline: none !important;
                min-width: 70px !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #cbd5e1 !important;
                border-radius: 0px !important;
                padding: 4px 8px !important;
                font-size: 0.75rem !important;
                background-color: #fff !important;
                outline: none !important;
            }
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 18px !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #0284c7 !important;
                color: #fff !important;
                border-color: #0284c7 !important;
                border-radius: 0px !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 0px !important;
                font-size: 0.75rem !important;
            }
            table.dataTable {
                border-collapse: collapse !important;
                border-color: #cbd5e1 !important;
            }
            table.dataTable thead th {
                border-bottom: 2px solid #cbd5e1 !important;
                background-color: #f8fafc !important;
            }
            .dataTables_wrapper .dataTables_processing {
                background: rgba(255, 255, 255, 0.95) !important;
                color: #0c4da2 !important;
                border: 1px solid #e2e8f0 !important;
                font-weight: 600 !important;
                font-size: 0.75rem !important;
                border-radius: 0px !important;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05) !important;
                padding: 8px 16px !important;
                height: auto !important;
                margin-top: -15px !important;
                z-index: 100 !important;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading Strip -->
            @isset($header)
                <div class="bg-white border-b border-gray-200">
                    <div class="w-full px-6 py-3">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Footer (Scrollable) -->
            <footer class="bg-white border-t border-gray-200 mt-auto py-4 shrink-0">
                <div class="w-full px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-gray-500 uppercase tracking-wider">PROMISE</span>
                        <span>&copy; {{ date('Y') }} Summit Adyawinsa Indonesia. All rights reserved.</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="font-medium text-gray-300">Admin Console v1.0.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
