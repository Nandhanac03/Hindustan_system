<x-erp-layout>
    <x-slot:title>Pending Approvals</x-slot:title>
    <x-slot:headerTitle>Approvals Inbox</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6">
        <div>
            <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">Pending Authorization Queue</h2>
            <p class="text-xs text-slate-500 mt-0.5">Review, approve, or reject transactions and discounts requiring owner/accountant signatures.</p>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($approvals as $req)
                <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4 hover:border-slate-350 transition duration-150 animate-fade-in-up">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <!-- Request Details -->
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center text-[9px] font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100 px-1.5 py-0.5 rounded">
                                    {{ class_basename($req->approvable_type) }} Request
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">Requested {{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 leading-snug">
                                Authorization request #{{ $req->id }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium">
                                Submitted by: <strong class="text-slate-700">{{ $req->requester->name }}</strong> ({{ $req->requester->email }})
                            </p>
                        </div>
                    </div>

                    <!-- Authorization Forms -->
                    <form method="POST" action="" id="form-{{ $req->id }}" class="space-y-3 pt-3 border-t border-slate-100">
                        @csrf
                        
                        <div class="space-y-1.5">
                            <label for="reason-{{ $req->id }}" class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Decision Comments / Reason (Optional)</label>
                            <input id="reason-{{ $req->id }}" 
                                   type="text" 
                                   name="reason" 
                                   placeholder="Add context to this decision..." 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl text-xs text-slate-900 px-4 py-2.5 placeholder-slate-400 focus:outline-none transition" />
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <!-- Reject -->
                            <button type="submit" 
                                    onclick="event.preventDefault(); document.getElementById('form-{{ $req->id }}').action = '{{ route('approvals.reject', $req->id) }}'; document.getElementById('form-{{ $req->id }}').submit();"
                                    class="px-4 py-2 bg-white border border-slate-200 hover:bg-rose-50 text-slate-650 hover:text-rose-700 font-bold rounded-xl transition text-xs uppercase tracking-wider">
                                Reject
                            </button>

                            <!-- Approve -->
                            <button type="submit" 
                                    onclick="event.preventDefault(); document.getElementById('form-{{ $req->id }}').action = '{{ route('approvals.approve', $req->id) }}'; document.getElementById('form-{{ $req->id }}').submit();"
                                    class="px-4 py-2 bg-primary hover:bg-primary/95 text-white font-bold rounded-xl transition text-xs shadow-md shadow-primary/10 uppercase tracking-wider">
                                Approve
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center space-y-3">
                    <div class="mx-auto w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Your inbox is clear!</h3>
                        <p class="text-xs text-slate-550 mt-1">There are no pending authorization requests matching your role permissions.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-erp-layout>
