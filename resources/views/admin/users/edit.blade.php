<x-erp-layout>
    <x-slot:title>Edit User</x-slot:title>
    <x-slot:headerTitle>User Directory / Edit</x-slot:headerTitle>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Edit User Profile</h2>
                <p class="text-xs text-slate-500 mt-0.5">Modify roles, operating entity permissions, and account status.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-primary font-bold hover:text-indigo-650 transition uppercase tracking-wider">&larr; Back to List</a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name & Email Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="name" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Full Name</label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required 
                               placeholder="e.g. Rajesh Kumar"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                        @if($errors->has('name'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Email Address</label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required 
                               placeholder="rajesh@company.com"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                        @if($errors->has('email'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Phone & Employee Code Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="phone" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Phone Number</label>
                        <input id="phone" 
                               type="text" 
                               name="phone" 
                               value="{{ old('phone', $user->phone) }}" 
                               placeholder="+91 99999 99999"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                        @if($errors->has('phone'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="employee_code" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Employee Code</label>
                        <input id="employee_code" 
                               type="text" 
                               name="employee_code" 
                               value="{{ old('employee_code', $user->employee_code) }}" 
                               placeholder="e.g. EMP-IN-ACC01"
                               class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                        @if($errors->has('employee_code'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('employee_code') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Associated System, Role & Status Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label for="system_id" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Operating System</label>
                        <select id="system_id" 
                                name="system_id" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="">Select Region...</option>
                            @foreach($systems as $sys)
                                <option value="{{ $sys->id }}" {{ old('system_id', $user->system_id) == $sys->id ? 'selected' : '' }}>
                                    {{ $sys->name }} ({{ $sys->code }})
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('system_id'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('system_id') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="role" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Role Assignment</label>
                        <select id="role" 
                                name="role" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="">Select Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('role'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('role') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <label for="status" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Account Status</label>
                        <select id="status" 
                                name="status" 
                                required 
                                class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 focus:outline-none transition cursor-pointer">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @if($errors->has('status'))
                            <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('status') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Password (Optional) -->
                <div class="space-y-1.5">
                    <label for="password" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">New Password (leave blank to keep current)</label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           placeholder="••••••••"
                           class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-3 placeholder-slate-400 focus:outline-none transition" />
                    @if($errors->has('password'))
                        <p class="text-xs text-rose-500 font-medium mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary/95 text-white rounded-xl text-xs font-bold transition shadow-md shadow-primary/10 tracking-wide uppercase">
                        Update User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-erp-layout>
