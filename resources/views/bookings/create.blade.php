<x-erp-layout title="Register Property Sale" headerTitle="Sales Register & Bookings">

<div class="max-w-[850px] mx-auto space-y-6" x-data="bookingFormApp()">

    {{-- Top Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('bookings.index') }}" class="p-2 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight uppercase">Register Property Sales</h1>
            <p class="text-xs text-slate-500 mt-0.5">Register a property sales contract and assign it to a customer.</p>
        </div>
    </div>

    {{-- Booking Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('bookings.store') }}" class="p-6 space-y-6">
            @csrf

            @if($selectedUnit)
                {{-- Hidden Inputs --}}
                <input type="hidden" name="unit_id" value="{{ $selectedUnit->id }}">

                {{-- Selected Unit details card --}}
                <div class="space-y-3">
                    <h4 class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Selected Property Inventory</h4>
                    <div class="p-4 bg-slate-50 border border-slate-150 rounded-2xl grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Project</span>
                            <span class="font-bold text-slate-800">{{ $selectedUnit->project->name }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Unit Number</span>
                            <span class="font-bold text-indigo-700 uppercase font-mono">{{ $selectedUnit->unit_number }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Floor</span>
                            <span class="font-bold text-slate-800">{{ $selectedUnit->floor->name }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Type / Category</span>
                            <span class="font-bold text-slate-800">{{ $selectedUnit->unitType->name }}</span>
                        </div>
                        <div class="space-y-1 pt-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">BUA Area</span>
                            <span class="font-bold text-slate-700">{{ $selectedUnit->bua_area }} {{ $selectedUnit->area_unit }}</span>
                        </div>
                        <div class="space-y-1 pt-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Carpet Area</span>
                            <span class="font-bold text-slate-700">{{ $selectedUnit->carpet_area ?? 'N/A' }} {{ $selectedUnit->area_unit }}</span>
                        </div>
                        <div class="space-y-1 pt-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Base Rate</span>
                            <span class="font-bold text-slate-700">₹{{ number_format($selectedUnit->base_rate, 2) }}</span>
                        </div>
                        <div class="space-y-1 pt-2">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Base Inventory Value</span>
                            <span class="font-bold text-emerald-700">₹{{ number_format($selectedUnit->bua_area * $selectedUnit->base_rate, 2) }}</span>
                        </div>
                    </div>
                </div>
            @else
                {{-- Unit Selector Dropdown --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Select Unit to Book</label>
                    <select name="unit_id" x-model="form.unit_id" @change="onUnitChange()" required
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">Select a Unit...</option>
                        @foreach($projects as $p)
                            <optgroup label="{{ $p->name }}">
                                @foreach($p->units()->whereIn('status', ['available', 'blocked'])->get() as $u)
                                    <option value="{{ $u->id }}" data-rate="{{ $u->base_rate }}" data-bua="{{ $u->bua_area }}">
                                        {{ $u->unit_number }} (Floor: {{ $u->floor->name }} · {{ $u->unitType->name }} · BUA: {{ $u->bua_area }} {{ $u->area_unit }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Customer Dropdown --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Customer</label>
                    <select name="customer_id" required
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">Select Customer...</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->email }})</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Sales Executive --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Sales Executive</label>
                    <select name="sales_executive_id" required
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">Select Executive...</option>
                        @foreach($executives as $ex)
                            <option value="{{ $ex->id }}" {{ old('sales_executive_id') == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                        @endforeach
                    </select>
                    @error('sales_executive_id')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Broker Dropdown --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Broker / Agent (Optional)</label>
                    <select name="broker_id"
                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">None / Direct Sale...</option>
                        @foreach($brokers as $b)
                            <option value="{{ $b->id }}" {{ old('broker_id') == $b->id ? 'selected' : '' }}>{{ $b->name }} ({{ $b->default_commission_pct }}% Comm.)</option>
                        @endforeach
                    </select>
                    @error('broker_id')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Agreement Date --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Agreement Date</label>
                    <input type="date" name="agreement_date" value="{{ old('agreement_date') }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 focus:outline-none transition-all">
                    @error('agreement_date')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Registration Date --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Registration Date</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date') }}"
                           class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 focus:outline-none transition-all">
                    @error('registration_date')
                        <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Financial Calculations --}}
            <div class="border-t border-slate-100 pt-6 space-y-4">
                <h4 class="text-[10px] font-bold text-slate-450 uppercase tracking-wider block">Financial Calculations & Dues</h4>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                    {{-- Actual Sale Rate per Sqft --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Actual Sale Rate per Sqft (₹)</label>
                        <input type="number" step="0.01" name="sale_rate_per_sqft" x-model="form.sale_rate" @input="recalculateTotal()" required
                               class="w-full px-3 py-2.5 bg-white border border-slate-200/80 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl text-xs text-slate-700 focus:outline-none transition-all">
                        @error('sale_rate_per_sqft')
                            <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- GST Behavior --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">GST Behavior</label>
                        <select name="gst_behavior" x-model="form.gst_behavior" @change="recalculateTotal()" required
                                class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                            <option value="none">No GST (None)</option>
                            <option value="inclusive">GST Included (18%)</option>
                            <option value="exclusive">GST Excluded (18% Extra)</option>
                        </select>
                        @error('gst_behavior')
                            <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- GST Amount --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">GST Amount Payable (₹)</label>
                        <input type="number" step="0.01" name="gst_amount" x-model="form.gst_amount" readonly
                               class="w-full px-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs text-slate-500 focus:outline-none cursor-not-allowed">
                        @error('gst_amount')
                            <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Total Agreement Amount --}}
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Total Agreement Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" x-model="form.amount" required
                               class="w-full px-3 py-2.5 bg-white border border-indigo-200/80 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl text-xs font-bold text-indigo-700 placeholder-slate-400 focus:outline-none transition-all shadow-sm">
                        @error('amount')
                            <span class="text-[10px] text-rose-500 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-slate-100 flex justify-end gap-2 bg-slate-50/50 p-4 -mx-6 -mb-6">
                <a href="{{ route('bookings.index') }}" 
                   class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-650 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-[#a38c29] hover:bg-[#8d7923] text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function bookingFormApp() {
    return {
        form: {
            unit_id: '{{ $selectedUnit ? $selectedUnit->id : "" }}',
            bua: {{ $selectedUnit ? $selectedUnit->bua_area : 0 }},
            sale_rate: {{ $selectedUnit ? $selectedUnit->base_rate : 0 }},
            gst_behavior: 'none',
            gst_amount: 0,
            amount: {{ $selectedUnit ? ($selectedUnit->bua_area * $selectedUnit->base_rate) : 0 }}
        },

        init() {
            this.recalculateTotal();
        },

        onUnitChange() {
            let selectEl = document.querySelector('select[name="unit_id"]');
            if (!selectEl) return;
            
            let selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (selectedOpt && selectedOpt.value) {
                let rate = parseFloat(selectedOpt.getAttribute('data-rate')) || 0;
                let bua = parseFloat(selectedOpt.getAttribute('data-bua')) || 0;
                this.form.bua = bua;
                this.form.sale_rate = rate;
            } else {
                this.form.bua = 0;
                this.form.sale_rate = 0;
            }
            this.recalculateTotal();
        },

        recalculateTotal() {
            let basePrice = this.form.bua * this.form.sale_rate;
            if (this.form.gst_behavior === 'inclusive') {
                this.form.amount = Math.round(basePrice * 100) / 100;
                this.form.gst_amount = Math.round((basePrice - (basePrice / 1.18)) * 100) / 100;
            } else if (this.form.gst_behavior === 'exclusive') {
                let gst = basePrice * 0.18;
                this.form.gst_amount = Math.round(gst * 100) / 100;
                this.form.amount = Math.round((basePrice + gst) * 100) / 100;
            } else {
                this.form.amount = Math.round(basePrice * 100) / 100;
                this.form.gst_amount = 0;
            }
        }
    };
}
</script>

</x-erp-layout>
