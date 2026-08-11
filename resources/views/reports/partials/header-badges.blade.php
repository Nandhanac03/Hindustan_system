@php
    $activeProjectName = 'All Projects (Default)';
    if(request('project_id')) {
        $activeProject = \App\Models\Project::find(request('project_id'));
        if($activeProject) {
            $activeProjectName = $activeProject->name;
        }
    }
    $activeCustomerName = null;
    if(request('customer_id')) {
        $reqIds = is_array(request('customer_id')) ? request('customer_id') : [request('customer_id')];
        $activeCustomers = \App\Models\Customer::whereIn('id', $reqIds)->get();
        if($activeCustomers->count() === 1) {
            $activeCustomerName = $activeCustomers->first()->name;
        } elseif($activeCustomers->count() > 1) {
            $activeCustomerName = $activeCustomers->count() . ' Customers Selected';
        }
    }
@endphp
<div class="flex flex-wrap items-center gap-2">
    <span class="px-4 py-1.5 bg-slate-800 text-white border border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
        Active Project: {{ $activeProjectName }}
    </span>
    @if($activeCustomerName)
    <span class="px-4 py-1.5 bg-[#a38c29]/15 text-[#8a7522] border border-[#a38c29]/30 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-2xs flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#a38c29] animate-pulse"></span>
        Customer: {{ $activeCustomerName }}
    </span>
    @endif
</div>
