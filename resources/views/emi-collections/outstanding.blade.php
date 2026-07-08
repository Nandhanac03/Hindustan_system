<x-erp-layout title="Outstanding & Due Tracking" headerTitle="Customer Outstanding Directory">

<div class="max-w-[1400px] mx-auto space-y-6" x-data="outstandingApp()">

    {{-- Summary KPIs & Aging Brackets --}}
    <div class="space-y-3">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Select Aging Bracket to Filter</h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
            {{-- Bracket 1: Current / Not Due --}}
            <div @click="setBracketFilter('current')" 
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === 'current' ? 'border-primary shadow-glow bg-primary-50/20' : 'border-slate-200/80 shadow-sm hover:border-primary/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Current / Not Due</span>
                    <span class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-bold font-mono">0 Days</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-slate-900 block font-mono">₹18,45,000</span>
                    <span class="text-[9px] text-slate-400 mt-1 block font-semibold">12 Accounts Active</span>
                </div>
            </div>

            {{-- Bracket 2: 1-30 Days Overdue --}}
            <div @click="setBracketFilter('1-30')" 
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '1-30' ? 'border-amber-500 shadow-glow bg-amber-50/10' : 'border-slate-200/80 shadow-sm hover:border-amber-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">1 - 30 Days Late</span>
                    <span class="text-[9px] bg-amber-55 text-amber-800 px-1.5 py-0.5 rounded font-bold font-mono">Mild</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-amber-700 block font-mono">₹4,80,000</span>
                    <span class="text-[9px] text-amber-600 mt-1 block font-semibold">3 Accounts Overdue</span>
                </div>
            </div>

            {{-- Bracket 3: 31-60 Days Overdue --}}
            <div @click="setBracketFilter('31-60')" 
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '31-60' ? 'border-orange-500 shadow-glow bg-orange-50/10' : 'border-slate-200/80 shadow-sm hover:border-orange-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">31 - 60 Days Late</span>
                    <span class="text-[9px] bg-orange-50 text-orange-850 px-1.5 py-0.5 rounded font-bold font-mono">Moderate</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-orange-700 block font-mono">₹2,50,000</span>
                    <span class="text-[9px] text-orange-600 mt-1 block font-semibold">2 Accounts Critical</span>
                </div>
            </div>

            {{-- Bracket 4: 61+ Days Overdue --}}
            <div @click="setBracketFilter('61+')" 
                 class="bg-white rounded-2xl border p-5 cursor-pointer transition-all duration-200 select-none flex flex-col justify-between"
                 :class="activeBracket === '61+' ? 'border-rose-500 shadow-glow bg-rose-50/10' : 'border-slate-200/80 shadow-sm hover:border-rose-400/40'">
                <div class="flex justify-between items-start">
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider uppercase">61+ Days Late</span>
                    <span class="text-[9px] bg-rose-50 text-rose-800 px-1.5 py-0.5 rounded font-bold font-mono">Severe</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl font-extrabold text-rose-700 block font-mono">₹1,15,000</span>
                    <span class="text-[9px] text-rose-600 mt-1 block font-semibold">1 Account Default Risk</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table View --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
        
        {{-- Table Toolbar --}}
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    Receivable Statements 
                    <template x-if="activeBracket !== 'all'">
                        <span class="text-[10px] bg-primary-100 text-primary-850 px-2 py-0.5 rounded font-mono uppercase font-bold" x-text="'Bracket: ' + activeBracket"></span>
                    </template>
                </h2>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">Due tracking log, payment collection status, and aging summary.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="text" x-model="searchQuery" placeholder="Search Customer, project..."
                       class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none w-52">
                
                <button @click="resetFilters()" x-show="activeBracket !== 'all' || searchQuery !== ''"
                        class="text-xs font-bold text-slate-400 hover:text-slate-650 transition uppercase tracking-wider border border-slate-200 px-2 py-1.5 rounded-lg">
                    Reset
                </button>
            </div>
        </div>

        {{-- Outstanding Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Customer Details</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Project & Unit</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Booking Price</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Total Cleared</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Outstanding Balance</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Days Overdue</th>
                        <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Collect Reminders</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-650">
                    <template x-for="(c, idx) in filteredCustomers()" :key="idx">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-extrabold text-slate-900" x-text="c.name"></div>
                                <div class="text-[10px] text-slate-400 font-medium" x-text="c.phone"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800" x-text="c.project"></div>
                                <div class="text-[10px] text-slate-400 font-mono" x-text="'Unit ' + c.unit"></div>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold" x-text="'₹' + Number(c.bookingValue).toLocaleString('en-IN')"></td>
                            <td class="px-6 py-4 text-emerald-700 font-mono font-semibold" x-text="'₹' + Number(c.paid).toLocaleString('en-IN')"></td>
                            <td class="px-6 py-4 text-slate-900 font-extrabold font-mono" x-text="'₹' + Number(c.outstanding).toLocaleString('en-IN')"></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 text-[9px] font-extrabold rounded-full font-mono"
                                      :class="getOverdueBadgeClasses(c.overdueDays)" x-text="c.overdueDays + ' Days'"></span>
                            </td>
                            <td class="px-6 py-4 text-right space-y-1">
                                <div class="flex justify-end gap-1.5" x-data="{ sending: false }">
                                    <button @click="sendReminder(c.name, 'WhatsApp')" 
                                            class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 text-[9px] font-bold rounded-lg uppercase tracking-wide transition flex items-center gap-1">
                                        <i data-lucide="message-square" class="w-3 h-3"></i> WhatsApp
                                    </button>
                                    <button @click="sendReminder(c.name, 'Email')" 
                                            class="px-2 py-1 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-800 text-[9px] font-bold rounded-lg uppercase tracking-wide transition flex items-center gap-1">
                                        <i data-lucide="mail" class="w-3 h-3"></i> Email Invoice
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredCustomers().length === 0">
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400 italic">No customer records matching the filter criteria.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>

    {{-- Toast notifications --}}
    <div x-show="toast.open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-5 right-5 z-50 p-4 rounded-xl shadow-lg border text-xs font-bold uppercase tracking-wide flex items-center gap-2 bg-emerald-50 border-emerald-250 text-emerald-800"
         style="display: none;">
        <span x-text="toast.message"></span>
        <button @click="toast.open = false" class="ml-2 hover:opacity-75">✕</button>
    </div>

</div>

<script>
function outstandingApp() {
    return {
        activeBracket: 'all',
        searchQuery: '',
        toast: {
            open: false,
            message: ''
        },

        // Mock Customers list with aging profile
        customers: [
            { name: 'Rajesh Kumar', phone: '+91 98450 12093', project: 'Hindustan Royal Heights', unit: 'Block A - 502', bookingValue: 5000000, paid: 4655000, outstanding: 345000, overdueDays: 14, bracket: '1-30' },
            { name: 'Subramanian Swamy', phone: '+91 94440 28392', project: 'Hindustan Imperial Garden', unit: 'Villa B-18', bookingValue: 15000000, paid: 13720000, outstanding: 1280000, overdueDays: 0, bracket: 'current' },
            { name: 'Nandhini Chidambaram', phone: '+91 95000 12345', project: 'Hindustan Smart Enclave', unit: 'Apt 202', bookingValue: 3500000, paid: 3411000, outstanding: 89000, overdueDays: 45, bracket: '31-60' },
            { name: 'Vikas Sharma', phone: '+91 88701 92837', project: 'Hindustan Royal Heights', unit: 'Block B - 104', bookingValue: 6500000, paid: 5960000, outstanding: 540000, overdueDays: 0, bracket: 'current' },
            { name: 'Anita Desai', phone: '+91 91760 98765', project: 'Hindustan Smart Enclave', unit: 'Apt 101', bookingValue: 3800000, paid: 3665000, outstanding: 135000, overdueDays: 22, bracket: '1-30' },
            { name: 'Vikramaditya Rao', phone: '+91 99620 11223', project: 'Hindustan Imperial Garden', unit: 'Villa A-04', bookingValue: 18000000, paid: 17885000, outstanding: 115000, overdueDays: 75, bracket: '61+' },
            { name: 'Balaji Parthasarathy', phone: '+91 98401 55667', project: 'Hindustan Smart Enclave', unit: 'Penthouse 501', bookingValue: 7500000, paid: 7339000, outstanding: 161000, overdueDays: 39, bracket: '31-60' }
        ],

        setBracketFilter(bracket) {
            // Toggle bracket filter
            if (this.activeBracket === bracket) {
                this.activeBracket = 'all';
            } else {
                this.activeBracket = bracket;
            }
        },

        resetFilters() {
            this.activeBracket = 'all';
            this.searchQuery = '';
        },

        filteredCustomers() {
            return this.customers.filter(c => {
                const matchQuery = this.searchQuery === '' || 
                    c.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    c.project.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                const matchBracket = this.activeBracket === 'all' || c.bracket === this.activeBracket;
                
                return matchQuery && matchBracket;
            });
        },

        getOverdueBadgeClasses(days) {
            if (days === 0) return 'bg-emerald-50 text-emerald-800 border border-emerald-100';
            if (days <= 30) return 'bg-amber-50 text-amber-800 border border-amber-200';
            if (days <= 60) return 'bg-orange-50 text-orange-850 border border-orange-200';
            return 'bg-rose-50 text-rose-800 border border-rose-200';
        },

        sendReminder(name, channel) {
            this.toast.message = `Remittance notice sent to ${name} via ${channel}...`;
            this.toast.open = true;
            setTimeout(() => {
                this.toast.open = false;
            }, 3000);
        }
    };
}
</script>

</x-erp-layout>
