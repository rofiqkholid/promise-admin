<x-app-layout>
    <x-slot name="header">
        <h2 class="text-sm font-semibold text-gray-800 tracking-wide">My Profile Settings</h2>
    </x-slot>

    @php
        $userDept = \DB::table('departments')->where('id', $user->id_dept)->first();
    @endphp

    <div class="px-3 py-3 md:px-6 md:py-6 max-w-6xl mx-auto">
        <!-- Status Toast / Alert -->
        @if (session('status'))
            <div class="mb-4 flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold border-l-4 border-emerald-500 border border-slate-300 bg-white text-emerald-800 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>
                    @if (session('status') === 'profile-updated')
                        Profile information updated successfully.
                    @elseif (session('status') === 'password-updated')
                        Password updated successfully.
                    @else
                        {{ session('status') }}
                    @endif
                </span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
            
            <!-- Left Column: User Summary Card -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white border border-slate-300 shadow-[0_1px_2px_rgba(0,0,0,0.02)] p-6 flex flex-col items-center text-center">
                    <!-- Avatar circle -->
                    <div class="w-16 h-16 bg-[#0c4da2] text-white flex items-center justify-center font-extrabold text-2xl rounded-xs shadow-inner">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <!-- Name & email -->
                    <h3 class="text-sm font-bold text-gray-900 mt-4 leading-tight">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-400 mt-1">{{ $user->email }}</p>
                    
                    <!-- Status Badge -->
                    <div class="mt-3">
                        @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xs bg-emerald-50 text-emerald-600 font-bold text-[10px] border border-emerald-100 uppercase">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xs bg-slate-50 text-slate-500 font-bold text-[10px] border border-slate-200 uppercase">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Inactive
                            </span>
                        @endif
                    </div>

                    <!-- Meta Details -->
                    <div class="w-full border-t border-slate-100 mt-6 pt-4 space-y-2.5 text-left">
                        <div class="flex justify-between text-[11px] border-b border-slate-50 pb-1.5">
                            <span class="text-slate-400 font-medium">NIK / Employee ID</span>
                            <span class="font-bold text-slate-700">{{ $user->nik ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between text-[11px] border-b border-slate-50 pb-1.5">
                            <span class="text-slate-400 font-medium">Department</span>
                            <span class="font-bold text-slate-700">{{ $userDept ? '[' . $userDept->code . '] ' . $userDept->name : 'No Department' }}</span>
                        </div>
                        <div class="flex justify-between text-[11px]">
                            <span class="text-slate-400 font-medium">Registered At</span>
                            <span class="font-bold text-slate-700">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Stack -->
            <div class="lg:col-span-8 space-y-4">
                <!-- Card 1: Profile Info -->
                <div class="bg-white border border-slate-300 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-slate-300 bg-slate-50 flex items-center">
                        <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Profile Information</span>
                    </div>
                    <div class="p-5">
                        <p class="text-[11px] text-slate-400 mb-4">Update your account's profile details and email address.</p>
                        
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                           class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    @if ($errors->get('name'))
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $errors->first('name') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                           class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    @if ($errors->get('email'))
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $errors->first('email') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-5 py-2 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card 2: Password Update -->
                <div class="bg-white border border-slate-300 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-slate-300 bg-slate-50 flex items-center">
                        <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">Update Password</span>
                    </div>
                    <div class="p-5">
                        <p class="text-[11px] text-slate-400 mb-4">Ensure your account is using a long, random password to stay secure.</p>
                        
                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">Current Password</label>
                                    <input type="password" name="current_password" required
                                           class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    @if ($errors->updatePassword->get('current_password'))
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">New Password</label>
                                    <input type="password" name="password" required
                                           class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    @if ($errors->updatePassword->get('password'))
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">Confirm Password</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                    @if ($errors->updatePassword->get('password_confirmation'))
                                        <p class="text-[10px] text-rose-600 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-5 py-2 text-xs font-semibold bg-[#0c4da2] hover:bg-[#083c80] text-white transition-colors">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card 3: Danger Zone -->
                <div class="bg-rose-50/50 border border-rose-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <div class="px-4 py-2.5 border-b border-rose-200 bg-rose-50 flex items-center">
                        <span class="text-[10px] font-bold tracking-wider text-rose-700 uppercase">Danger Zone</span>
                    </div>
                    <div class="p-5" x-data="{ openDelete: false }">
                        <p class="text-[11px] text-rose-700/80 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        
                        <button type="button" @click="openDelete = true"
                                class="px-5 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors">
                            Delete Account
                        </button>

                        <!-- Custom Delete Modal -->
                        <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
                            <div class="bg-white border border-slate-300 w-full max-w-sm" @click.away="openDelete = false">
                                <div class="px-5 py-3 border-b border-slate-300 bg-slate-50 flex items-center justify-between">
                                    <h3 class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Delete Account</h3>
                                    <button @click="openDelete = false" class="text-slate-400 hover:text-slate-600">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>
                                <form method="post" action="{{ route('profile.destroy') }}" class="p-5 space-y-4">
                                    @csrf
                                    @method('delete')
                                    <p class="text-xs text-gray-600">Please enter your password to confirm you want to permanently delete your account.</p>
                                    <div>
                                        <label class="block text-[10px] font-semibold tracking-wider text-slate-500 mb-1.5 uppercase">Password</label>
                                        <input type="password" name="password" required placeholder="••••••••"
                                               class="w-full text-xs border border-slate-300 py-2 px-3 focus:border-[#0c4da2] focus:outline-none transition-colors bg-white text-gray-800">
                                        @if ($errors->userDeletion->get('password'))
                                            <p class="text-[10px] text-rose-600 mt-1">{{ $errors->userDeletion->first('password') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" @click="openDelete = false"
                                                class="px-4 py-2 text-xs font-medium border border-slate-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
                                        <button type="submit"
                                                class="px-4 py-2 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white transition-colors">Delete Account</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
