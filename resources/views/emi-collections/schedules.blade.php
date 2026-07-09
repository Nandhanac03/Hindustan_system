<x-erp-layout title="Payment Schedules" headerTitle="Payment Schedules Manager">

<div class="max-w-[1800px] mx-auto space-y-6" x-data="scheduleApp()">
    
    {{-- Header Options --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Payment Schedules & EMI Templates</h2>
            <p class="text-xs text-slate-400 mt-0.5">Select, configure, or calculate fixed and milestone-based installment schemes for bookings.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="activeTab = 'templates'" 
                    class="px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wide transition-all border"
                    :class="activeTab === 'templates' ? 'bg-primary text-white border-primary shadow-md' : 'bg-white text-slate-650 hover:bg-slate-50 border-slate-200'">
                Plan Templates
            </button>
            <button @click="activeTab = 'calculator'" 
                    class="px-4 py-2 text-xs font-bold rounded-xl uppercase tracking-wide transition-all border"
                    :class="activeTab === 'calculator' ? 'bg-primary text-white border-primary shadow-md' : 'bg-white text-slate-650 hover:bg-slate-50 border-slate-200'">
                Interactive EMI Calculator
            </button>
        </div>
    </div>

    {{-- VIEW 1: PLAN TEMPLATES --}}
    <div x-show="activeTab === 'templates'" x-transition class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Card 1: 12-Month Fixed EMI --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col hover:shadow-glow/10 transition-all duration-300">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start">
                    <div>
                        <span class="text-[9px] font-bold px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded border border-emerald-100 uppercase tracking-wider">Fixed Plan</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-2.5">Fixed 12-Month Plan</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Standard 1-year equal installments.</p>
                    </div>
                    <span class="text-xs font-extrabold text-primary font-mono">0% Int.</span>
                </div>
                <div class="p-6 flex-1 space-y-4 text-xs text-slate-600">
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>Down Payment</span>
                        <strong class="text-slate-900">20% of Unit Value</strong>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>Number of Installments</span>
                        <strong class="text-slate-900">12 Equal Payments</strong>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>EMI Frequency</span>
                        <strong class="text-slate-900">Monthly</strong>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span>Grace Period</span>
                        <strong class="text-slate-900">7 Days / Installment</strong>
                    </div>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">142 bookings active</span>
                    <button @click="showTemplateDetails('fixed-12')" class="text-xs font-bold text-primary hover:text-primary-700 uppercase tracking-wider">View Milestones &rarr;</button>
                </div>
            </div>

            {{-- Card 2: Construction Linked Milestone Plan --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col hover:shadow-glow/10 transition-all duration-300">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-start">
                    <div>
                        <span class="text-[9px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded border border-indigo-100 uppercase tracking-wider">Milestone-Based</span>
                        <h3 class="text-sm font-bold text-slate-900 mt-2.5">Construction Linked Plan (CLP)</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Linked to actual on-site progress stages.</p>
                    </div>
                    <span class="text-xs font-extrabold text-primary font-mono">Stage-wise</span>
                </div>
                <div class="p-6 flex-1 space-y-4 text-xs text-slate-600">
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>Booking Booking Amount</span>
                        <strong class="text-slate-900">₹1,00,000 + 10% in 30 days</strong>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>Milestone Stages</span>
                        <strong class="text-slate-900">8 Progressive Phases</strong>
                    </div>
                    <div class="flex justify-between items-center py-1 border-b border-dashed border-slate-150">
                        <span>Verification Mode</span>
                        <strong class="text-slate-900">Site Engineer Certificate</strong>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span>Outstanding Allocation</span>
                        <strong class="text-slate-900">Calculated on Base Area Rate</strong>
                    </div>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 uppercase font-bold">298 bookings active</span>
                    <button @click="showTemplateDetails('clp')" class="text-xs font-bold text-primary hover:text-primary-700 uppercase tracking-wider">View Milestones &rarr;</button>
                </div>
            </div>

        </div>

        {{-- Dynamic Plan Stage Breakdown Details (shows when a template is selected) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden" x-show="selectedTemplate">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">
                        Milestone Configuration: <span class="text-primary font-mono" x-text="templateName"></span>
                    </h3>
                    <p class="text-[11px] text-slate-450 mt-1">Below is the progress percentage and due trigger criteria for the selected plan template.</p>
                </div>
                <button @click="selectedTemplate = null" class="text-slate-400 hover:text-slate-600 text-xs font-bold uppercase tracking-wider">Collapse View</button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">S.No.</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Milestone Name / Stage</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Due Percentage</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Trigger Condition</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Expected Timeline</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Grace Days</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650">
                        <template x-for="(milestone, idx) in templateMilestones" :key="idx">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-semibold text-slate-400" x-text="idx + 1"></td>
                                <td class="px-6 py-3.5 font-bold text-slate-850" x-text="milestone.name"></td>
                                <td class="px-6 py-3.5 font-mono font-extrabold text-slate-800" x-text="milestone.pct + '%'"></td>
                                <td class="px-6 py-3.5" x-text="milestone.trigger"></td>
                                <td class="px-6 py-3.5 font-medium" x-text="milestone.timeline"></td>
                                <td class="px-6 py-3.5 font-mono text-right font-bold text-slate-500" x-text="milestone.grace"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- VIEW 2: INTERACTIVE EMI CALCULATOR --}}
    <div x-show="activeTab === 'calculator'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Inputs Panel --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Amortization Inputs</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Input terms below to immediately construct a repayment timeline table.</p>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Total Property/Unit Cost (₹)</label>
                    <input type="number" x-model.number="calc.totalCost" @input="recalcEMI()"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs font-semibold focus:outline-none transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Booking / Down Payment (₹)</label>
                    <input type="number" x-model.number="calc.downPayment" @input="recalcEMI()"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs font-semibold focus:outline-none transition-all">
                    <div class="text-[9px] text-slate-400 font-bold text-right">
                        Pct: <span x-text="((calc.downPayment / calc.totalCost) * 100).toFixed(1) + '%'"></span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Repayment Term (Months)</label>
                        <span class="text-[10px] text-primary font-mono font-bold" x-text="calc.months + ' Months'"></span>
                    </div>
                    <input type="range" min="6" max="60" step="3" x-model.number="calc.months" @input="recalcEMI()"
                           class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-primary">
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Annual Interest Rate (%, Flat/Year)</label>
                        <span class="text-[10px] text-primary font-mono font-bold" x-text="calc.interestRate + '%'"></span>
                    </div>
                    <input type="range" min="0" max="15" step="0.5" x-model.number="calc.interestRate" @input="recalcEMI()"
                           class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-primary">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Plan Starts From</label>
                    <input type="date" x-model="calc.startDate" @change="recalcEMI()"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs text-slate-700 focus:outline-none transition-all">
                </div>
            </div>
            
            <div class="pt-2">
                <button type="button" @click="resetCalculator()" 
                        class="w-full py-2 bg-slate-100 hover:bg-slate-200/80 text-slate-655 text-[10px] font-bold rounded-xl transition uppercase tracking-wider">
                    Reset Calculator
                </button>
            </div>
        </div>

        {{-- Results Table & Summary --}}
        <div class="lg:col-span-2 space-y-6 flex flex-col">
            
            {{-- Quick Summary Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Net Loan Amount</span>
                    <span class="text-lg font-extrabold text-slate-900 mt-2 block font-mono">
                        ₹<span x-text="calc.loanAmount.toLocaleString('en-IN', {maximumFractionDigits: 0})"></span>
                    </span>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Monthly Installment (EMI)</span>
                    <span class="text-lg font-extrabold text-primary mt-2 block font-mono">
                        ₹<span x-text="calc.emi.toLocaleString('en-IN', {maximumFractionDigits: 0})"></span>
                    </span>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 flex flex-col justify-between">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total Collection Value</span>
                    <span class="text-lg font-extrabold text-slate-900 mt-2 block font-mono">
                        ₹<span x-text="calc.totalPayout.toLocaleString('en-IN', {maximumFractionDigits: 0})"></span>
                    </span>
                </div>
            </div>

            {{-- Amortization Schedule List --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex-1 flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Amortization Repayment Timeline</h4>
                        <p class="text-[10px] text-slate-400 mt-0.5">Calculated table for booking installments by month.</p>
                    </div>
                    <button @click="printSchedule()" class="text-[10px] text-primary hover:underline font-bold uppercase tracking-wider flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Print/Save PDF
                    </button>
                </div>
                
                <div class="overflow-y-auto max-h-[400px]">
                    <table class="w-full text-xs text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 sticky top-0">
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Inst. No.</th>
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Due Date</th>
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Installment (₹)</th>
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Principal Portion</th>
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Interest Portion</th>
                                <th class="px-6 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Balance Outstanding</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-660 font-mono">
                            <tr class="bg-slate-50/30">
                                <td class="px-6 py-2.5 text-slate-400 font-sans">0</td>
                                <td class="px-6 py-2.5 text-slate-400 font-sans" x-text="formatDate(calc.startDate)"></td>
                                <td class="px-6 py-2.5 font-semibold text-slate-500">-</td>
                                <td class="px-6 py-2.5 text-slate-500">-</td>
                                <td class="px-6 py-2.5 text-slate-500">-</td>
                                <td class="px-6 py-2.5 text-right text-slate-800 font-bold">₹<span x-text="calc.loanAmount.toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2})"></span></td>
                            </tr>
                            <template x-for="(row, idx) in calc.schedule" :key="idx">
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-6 py-2.5 text-slate-500 font-sans font-semibold" x-text="row.instNo"></td>
                                    <td class="px-6 py-2.5 text-slate-800 font-sans font-medium" x-text="formatDate(row.date)"></td>
                                    <td class="px-6 py-2.5 text-slate-900 font-bold">₹<span x-text="row.emi.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></td>
                                    <td class="px-6 py-2.5 text-emerald-700">₹<span x-text="row.principal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></td>
                                    <td class="px-6 py-2.5 text-amber-700">₹<span x-text="row.interest.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></td>
                                    <td class="px-6 py-2.5 text-right text-slate-800 font-bold">₹<span x-text="row.balance.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function scheduleApp() {
    return {
        activeTab: 'templates',
        selectedTemplate: null,
        templateName: '',
        templateMilestones: [],
        
        // Calculator state
        calc: {
            totalCost: 5000000,
            downPayment: 1000000,
            months: 24,
            interestRate: 6, // flat annual interest rate %
            startDate: new Date().toISOString().slice(0, 10),
            loanAmount: 0,
            emi: 0,
            totalPayout: 0,
            schedule: []
        },

        init() {
            this.recalcEMI();
        },

        showTemplateDetails(type) {
            this.selectedTemplate = type;
            if (type === 'fixed-12') {
                this.templateName = 'Fixed 12-Month Repayment Plan';
                this.templateMilestones = [
                    { name: 'Booking Advance', pct: 10, trigger: 'At execution of booking request', timeline: 'Immediate', grace: 7 },
                    { name: 'Agreement Signing', pct: 10, trigger: 'Signing of Agreement of Sale', timeline: 'Within 30 days', grace: 7 },
                    { name: 'Installment 1 to 10', pct: 7.5, trigger: 'Monthly automatic recurring trigger', timeline: 'Monthly schedule', grace: 5 },
                    { name: 'Final Handover Possession', pct: 5, trigger: 'At registration and key handover', timeline: 'Month 12', grace: 15 }
                ];
            } else if (type === 'clp') {
                this.templateName = 'Construction Linked milestone Plan (CLP)';
                this.templateMilestones = [
                    { name: 'Booking Advance & Allocation', pct: 10, trigger: 'At execution of booking', timeline: 'Immediate', grace: 7 },
                    { name: 'Excavation & Foundation Start', pct: 15, trigger: 'Completion of excavation phase', timeline: 'Stage trigger (Est. 3 Mos)', grace: 10 },
                    { name: 'Plinth Level Casting', pct: 15, trigger: 'Casting of plinth beam structure', timeline: 'Stage trigger (Est. 6 Mos)', grace: 10 },
                    { name: 'Ground Floor Slab Casting', pct: 10, trigger: 'Casting of floor slab concrete', timeline: 'Stage trigger (Est. 9 Mos)', grace: 10 },
                    { name: 'First Floor Slab Casting', pct: 10, trigger: 'Casting of first floor slab concrete', timeline: 'Stage trigger (Est. 12 Mos)', grace: 10 },
                    { name: 'Masonry & Internal Brickwork', pct: 15, trigger: 'Completion of internal walls & layout', timeline: 'Stage trigger (Est. 15 Mos)', grace: 10 },
                    { name: 'Sanitary & External Plastering', pct: 15, trigger: 'Plaster finishing & electrical conduits', timeline: 'Stage trigger (Est. 18 Mos)', grace: 10 },
                    { name: 'Final Handover possession', pct: 10, trigger: 'Completion certificate & utilities setup', timeline: 'Month 24 / Possession', grace: 15 }
                ];
            } else if (type === 'fixed-36') {
                this.templateName = '36-Month Milestone + Fixed Combo Plan';
                this.templateMilestones = [
                    { name: 'Initial Booking Token', pct: 15, trigger: 'Booking registration fee', timeline: 'Immediate', grace: 7 },
                    { name: 'Regular Monthly Installments', pct: 2.0, trigger: '35 monthly equal collections', timeline: 'Months 1 to 35', grace: 5 },
                    { name: 'Construction Stage Mid-Step 1', pct: 5.0, trigger: 'Completion of Plinth stage', timeline: 'Estimated Month 10', grace: 10 },
                    { name: 'Construction Stage Mid-Step 2', pct: 5.0, trigger: 'Completion of Roof structure casting', timeline: 'Estimated Month 22', grace: 10 },
                    { name: 'Key possession & Final Registry', pct: 5.0, trigger: 'Handover & signoff', timeline: 'Month 36 / Registry', grace: 15 }
                ];
            }
        },

        recalcEMI() {
            // Net loan amount
            const P = Math.max(0, this.calc.totalCost - this.calc.downPayment);
            this.calc.loanAmount = P;

            // Simple Flat Rate calculation for Real Estate Milestone EMI display
            const r = this.calc.interestRate / 100; // Annual interest rate
            const n = this.calc.months; // duration in months

            if (P <= 0) {
                this.calc.emi = 0;
                this.calc.totalPayout = 0;
                this.calc.schedule = [];
                return;
            }

            // Flat Interest calculation: Total interest = P * r * (n/12)
            const totalInterest = P * r * (n / 12);
            const totalPayout = P + totalInterest;
            const emi = totalPayout / n;

            this.calc.totalPayout = totalPayout;
            this.calc.emi = emi;

            // Generate Amortization Schedule
            let balance = P;
            const schedule = [];
            let currentDate = new Date(this.calc.startDate);

            // Calculate monthly principal and interest portion
            const monthlyInterestPortion = totalInterest / n;
            const monthlyPrincipalPortion = P / n;

            for (let i = 1; i <= n; i++) {
                currentDate.setMonth(currentDate.getMonth() + 1);
                balance = Math.max(0, balance - monthlyPrincipalPortion);

                schedule.push({
                    instNo: i,
                    date: new Date(currentDate),
                    emi: emi,
                    principal: monthlyPrincipalPortion,
                    interest: monthlyInterestPortion,
                    balance: balance
                });
            }
            this.calc.schedule = schedule;
        },

        resetCalculator() {
            this.calc.totalCost = 5000000;
            this.calc.downPayment = 1000000;
            this.calc.months = 24;
            this.calc.interestRate = 6;
            this.calc.startDate = new Date().toISOString().slice(0, 10);
            this.recalcEMI();
        },

        formatDate(dateObj) {
            if (!dateObj) return '';
            const d = new Date(dateObj);
            return d.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
        },

        printSchedule() {
            alert("Generating print view of the Amortization schedule... Total Installments: " + this.calc.months + ", Monthly EMI: ₹" + Math.round(this.calc.emi).toLocaleString('en-IN'));
        }
    };
}
</script>

</x-erp-layout>
