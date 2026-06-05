<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-1 h-5 bg-sky-500"></div>
            <h2 class="text-sm font-semibold text-gray-800 tracking-wide ">My Profile Settings</h2>
        </div>
    </x-slot>

    <div class="px-6 py-6 max-w-4xl mx-auto space-y-6">

        <!-- Status Toast -->
        @if (session('status'))
            <div class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium border-l-4 border-emerald-500 border bg-white text-gray-800">
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

        <!-- Card 1: Profile Info -->
        <div class="bg-white border border-gray-200">
            <div class="px-5 py-3 bg-gray-50/75 border-b border-gray-200 flex items-center gap-2">
                <div class="w-1 h-4 bg-sky-500"></div>
                <h4 class="text-[10px] font-semibold tracking-wider text-gray-500">Profile Information</h4>
            </div>
            <div class="p-5">
                <p class="text-xs text-gray-400 mb-4">Update your account's profile details and email address.</p>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
                            @if ($errors->get('name'))
                                <p class="text-[10px] text-rose-600 mt-1">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
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
        <div class="bg-white border border-gray-200">
            <div class="px-5 py-3 bg-gray-50/75 border-b border-gray-200 flex items-center gap-2">
                <div class="w-1 h-4 bg-sky-500"></div>
                <h4 class="text-[10px] font-semibold tracking-wider text-gray-500">Update Password</h4>
            </div>
            <div class="p-5">
                <p class="text-xs text-gray-400 mb-4">Ensure your account is using a long, random password to stay secure.</p>
                
                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
                            @if ($errors->updatePassword->get('current_password'))
                                <p class="text-[10px] text-rose-600 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">New Password</label>
                            <input type="password" name="password" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
                            @if ($errors->updatePassword->get('password'))
                                <p class="text-[10px] text-rose-600 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
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
        <div class="bg-rose-50 border border-rose-200">
            <div class="px-5 py-3 bg-rose-100/50 border-b border-rose-200 flex items-center gap-2">
                <div class="w-1 h-4 bg-rose-500"></div>
                <h4 class="text-[10px] font-semibold tracking-wider text-rose-800">Danger Zone</h4>
            </div>
            <div class="p-5" x-data="{ openDelete: false }">
                <p class="text-xs text-rose-700/80 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                
                <button type="button" @click="openDelete = true"
                        class="px-5 py-2 text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white transition-colors">
                    Delete Account
                </button>

                <!-- Custom Delete Modal -->
                <div x-show="openDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50" style="display: none;">
                    <div class="bg-white border border-gray-300 w-full max-w-sm" @click.away="openDelete = false">
                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                            <h3 class="text-xs font-semibold tracking-wider text-gray-600">Delete Account</h3>
                            <button @click="openDelete = false" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-5 space-y-4">
                            @csrf
                            @method('delete')
                            <p class="text-xs text-gray-600">Please enter your password to confirm you want to permanently delete your account.</p>
                            <div>
                                <label class="block text-[10px] font-semibold tracking-wider text-gray-500 mb-1.5">Password</label>
                                <input type="password" name="password" required placeholder="••••••••"
                                       class="w-full text-xs border border-gray-300 py-2 px-3 focus:border-sky-500 focus:outline-none transition-colors bg-white text-gray-800">
                                @if ($errors->userDeletion->get('password'))
                                    <p class="text-[10px] text-rose-600 mt-1">{{ $errors->userDeletion->first('password') }}</p>
                                @endif
                            </div>
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="openDelete = false"
                                        class="px-4 py-2 text-xs font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
                                <button type="submit"
                                        class="px-4 py-2 text-xs font-medium bg-rose-600 hover:bg-rose-700 text-white transition-colors">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
