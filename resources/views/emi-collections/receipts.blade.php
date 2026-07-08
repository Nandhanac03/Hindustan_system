<x-erp-layout title="Receipts Entry" headerTitle="Collection Receipts Register">

<div class="max-w-[1400px] mx-auto space-y-6" x-data="receiptsApp()">
    
    {{-- Summary KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Month-to-Date Collections</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">₹14,85,000</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Cheques in Transit</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">₹4,20,000</span>
                <span class="text-[9px] font-bold text-amber-600 mt-0.5 block">5 Pending Clearance</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Digital Payments</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">₹8,90,000</span>
                <span class="text-[9px] font-bold text-indigo-600 mt-0.5 block">100% Autocleared</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <i data-lucide="zap" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Total Cash Collected</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block font-mono">₹1,75,000</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    {{-- Main Workspace --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left side: Interactive Log Entry --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5 h-fit">
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Add Receipt Entry</h3>
                <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Record cash, cheque, bank transfer, or online collections.</p>
            </div>
            
            <form @submit.prevent="submitReceipt()" class="space-y-4">
                {{-- Select Booking / Customer --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Select Customer Booking</label>
                    <select x-model="form.bookingIdx" required @change="updateBookingDetail()"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 rounded-xl text-xs text-slate-700 cursor-pointer focus:outline-none transition-all">
                        <option value="">-- Choose Booking --</option>
                        <template x-for="(b, idx) in bookings" :key="idx">
                            <option :value="idx" x-text="b.customerName + ' (' + b.bookingNo + ' - ' + b.projectName + ')'"></option>
                        </template>
                    </select>
                </div>

                {{-- Quick booking info card --}}
                <div x-show="selectedBooking" class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-[11px] font-semibold text-slate-600" x-transition>
                    <div class="flex justify-between">
                        <span>Project Unit:</span>
                        <strong class="text-slate-900" x-text="selectedBooking.unit"></strong>
                    </div>
                    <div class="flex justify-between">
                        <span>Outstanding Due:</span>
                        <strong class="text-primary font-mono" x-text="'₹' + Number(selectedBooking.outstanding).toLocaleString('en-IN')"></strong>
                    </div>
                </div>

                {{-- Amount and Date --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Amount (₹)</label>
                        <input type="number" x-model.number="form.amount" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs font-bold focus:outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Receipt Date</label>
                        <input type="date" x-model="form.date" required
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200/80 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary rounded-xl text-xs focus:outline-none transition-all">
                    </div>
                </div>

                {{-- Payment Mode --}}
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide block">Payment Mode</label>
                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" @click="form.mode = 'Cash'" 
                                class="py-1.5 border rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all"
                                :class="form.mode === 'Cash' ? 'bg-primary text-white border-primary' : 'bg-white hover:bg-slate-50 text-slate-600 border-slate-200'">Cash</button>
                        <button type="button" @click="form.mode = 'Cheque'" 
                                class="py-1.5 border rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all"
                                :class="form.mode === 'Cheque' ? 'bg-primary text-white border-primary' : 'bg-white hover:bg-slate-50 text-slate-600 border-slate-200'">Cheque</button>
                        <button type="button" @click="form.mode = 'Bank'" 
                                class="py-1.5 border rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all"
                                :class="form.mode === 'Bank' ? 'bg-primary text-white border-primary' : 'bg-white hover:bg-slate-50 text-slate-600 border-slate-200'">Bank</button>
                        <button type="button" @click="form.mode = 'Online'" 
                                class="py-1.5 border rounded-lg text-[10px] font-bold uppercase tracking-wide transition-all"
                                :class="form.mode === 'Online' ? 'bg-primary text-white border-primary' : 'bg-white hover:bg-slate-50 text-slate-600 border-slate-200'">UPI/Web</button>
                    </div>
                </div>

                {{-- Conditional Mode Fields: CHEQUE --}}
                <div x-show="form.mode === 'Cheque'" class="space-y-3.5 border-t border-slate-100 pt-3" x-transition>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Cheque Number</label>
                            <input type="text" x-model="form.chequeNo" placeholder="e.g. 021456"
                                   class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs font-semibold focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Cheque Date</label>
                            <input type="date" x-model="form.chequeDate"
                                   class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs focus:outline-none focus:bg-white">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Drawee Bank</label>
                            <input type="text" x-model="form.chequeBank" placeholder="e.g. HDFC Bank"
                                   class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs font-semibold focus:outline-none focus:bg-white">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Bank Branch</label>
                            <input type="text" x-model="form.chequeBranch" placeholder="e.g. T-Nagar Branch"
                                   class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs focus:outline-none focus:bg-white">
                        </div>
                    </div>
                </div>

                {{-- Conditional Mode Fields: BANK TRANSFER & ONLINE --}}
                <div x-show="form.mode === 'Bank' || form.mode === 'Online'" class="space-y-3 border-t border-slate-100 pt-3" x-transition>
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Reference / UTR / Transaction ID</label>
                        <input type="text" x-model="form.reference" placeholder="e.g. UTRN09384594"
                               class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs font-mono font-semibold focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary">
                    </div>
                    <div class="space-y-1.5" x-show="form.mode === 'Bank'">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Recipient Company Bank Account</label>
                        <select x-model="form.recipientBank"
                                class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200/80 rounded-lg text-xs focus:outline-none">
                            <option value="ICICI Current A/c ..9082">ICICI Corporate A/c (..9082)</option>
                            <option value="SBI Escrow A/c ..5113">SBI Project Escrow (..5113)</option>
                        </select>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wider shadow-md shadow-primary/20">
                        Create Receipt Voucher
                    </button>
                </div>
            </form>
        </div>

        {{-- Right side: Receipts History --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Receipt Ledger Log</h2>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium">Historical register of cleared and pending collections.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" x-model="searchQuery" placeholder="Search customer, receipt..."
                           class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:bg-white focus:outline-none w-44">
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Receipt No.</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Customer & Project</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Amt / Date</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Mode</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Status</th>
                            <th class="px-6 py-3 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-650">
                        <template x-for="(r, idx) in filteredReceipts()" :key="idx">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-primary" x-text="r.receiptNo"></td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900" x-text="r.customerName"></div>
                                    <div class="text-[10px] text-slate-400 font-medium" x-text="r.projectName + ' · Unit: ' + r.unit"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-800" x-text="'₹' + Number(r.amount).toLocaleString('en-IN')"></div>
                                    <div class="text-[10px] text-slate-400 font-mono" x-text="r.date"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-700" x-text="r.mode"></span>
                                    <div class="text-[9px] text-slate-400 font-mono" x-text="r.details"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-[9px] font-bold rounded-full uppercase"
                                          :class="getStatusClasses(r.status)" x-text="r.status"></span>
                                </td>
                                <td class="px-6 py-4 text-right space-y-1">
                                    <div class="flex justify-end gap-1.5">
                                        <button @click="viewVoucher(r)" 
                                                class="px-2 py-1 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-[9px] font-bold rounded-lg uppercase tracking-wide transition">
                                            Voucher
                                        </button>
                                        <template x-if="r.status === 'PENDING CLEARING'">
                                            <button @click="markCleared(r)" 
                                                    class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-[9px] font-bold rounded-lg uppercase tracking-wide transition">
                                                Clear
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- PRINT VOUCHER MODAL OVERLAY --}}
    <div x-show="modal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;" x-transition>
        <div @click.away="modal.open = false" class="w-full max-w-2xl bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden">
            {{-- Modal Header --}}
            <div class="px-8 py-5 bg-slate-50 border-b border-slate-150 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">Hindustan Real Estate ERP</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Corporate Official Payment Receipt Voucher</p>
                </div>
                <button @click="modal.open = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
            </div>
            
            {{-- Printable Area --}}
            <div id="printVoucherArea" class="p-8 space-y-6 bg-white text-slate-800 relative">
                
                {{-- Watermark --}}
                <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] select-none pointer-events-none">
                    <span class="text-7xl font-extrabold text-slate-900 rotate-12">HINDUSTAN</span>
                </div>

                {{-- Voucher Header --}}
                <div class="flex justify-between items-start border-b border-slate-200 pb-5">
                    <div>
                        <h1 class="text-lg font-extrabold tracking-wider text-slate-900">HINDUSTAN REAL ESTATE CO.</h1>
                        <p class="text-[10px] text-slate-450 leading-relaxed">No. 12, Gold Crest Plaza, Mount Road, Chennai - 600002<br>Tel: +91 44 2849 5000 | Support: contact@hindustanre.com</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-bold px-2 py-0.5 bg-primary-100 text-primary-850 rounded border border-primary-200 font-mono uppercase">Voucher Copy</span>
                        <div class="mt-2.5 text-xs text-slate-500 font-semibold">
                            Receipt No: <span class="text-slate-900 font-bold" x-text="modalData.receiptNo"></span><br>
                            Date: <span class="text-slate-900 font-mono" x-text="modalData.date"></span>
                        </div>
                    </div>
                </div>

                {{-- Key Columns --}}
                <div class="grid grid-cols-2 gap-6 text-xs border-b border-slate-200 pb-5">
                    <div class="space-y-1.5">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Received From:</span>
                        <div class="font-extrabold text-slate-900 text-sm" x-text="modalData.customerName"></div>
                        <div class="text-slate-500">Phone: +91 98450 12093<br>GSTIN: 33AAFCH2938Q1Z3</div>
                    </div>
                    <div class="space-y-1.5 text-right sm:text-left sm:pl-10">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Property details:</span>
                        <div class="font-bold text-slate-900" x-text="modalData.projectName"></div>
                        <div class="text-slate-500">Unit Allocated: <strong class="text-slate-700" x-text="modalData.unit"></strong><br>Allocation ID: BKG-2026-902</div>
                    </div>
                </div>

                {{-- Table details --}}
                <table class="w-full text-xs text-left border border-slate-100 rounded-xl overflow-hidden">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-150">
                            <th class="px-4 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Description</th>
                            <th class="px-4 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Payment Mode</th>
                            <th class="px-4 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px]">Reference</th>
                            <th class="px-4 py-2.5 font-bold text-slate-500 uppercase tracking-widest text-[9px] text-right">Amount Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3.5 font-medium text-slate-900">EMI Instalment Payment under purchase agreement terms</td>
                            <td class="px-4 py-3.5 font-bold text-slate-700" x-text="modalData.mode"></td>
                            <td class="px-4 py-3.5 font-mono text-slate-600" x-text="modalData.details || '-'"></td>
                            <td class="px-4 py-3.5 text-right font-extrabold text-slate-900 font-mono" x-text="'₹' + Number(modalData.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                        </tr>
                        <tr class="border-t border-slate-150 bg-slate-50/50 font-bold">
                            <td colspan="3" class="px-4 py-2.5 text-right uppercase tracking-wider font-extrabold text-[9px] text-slate-500">Total Cleared</td>
                            <td class="px-4 py-2.5 text-right font-extrabold text-primary font-mono text-sm" x-text="'₹' + Number(modalData.amount).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Amount in words & declarations --}}
                <div class="text-xs space-y-3.5 pt-2">
                    <div class="p-3 bg-slate-50/80 border border-slate-150 rounded-xl">
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider block">Amount in Words:</span>
                        <strong class="text-slate-800 capitalize italic" x-text="getAmountInWords(modalData.amount) + ' Only'"></strong>
                    </div>
                    <div class="text-[9px] text-slate-400 leading-normal">
                        * Note: This is a system-generated electronic receipt issued in compliance with the RERA guidelines. Cheque receipts are valid only subject to bank realization. Digital references are cross-matched with automated bank reconciliation files.
                    </div>
                </div>

                {{-- Signatures --}}
                <div class="flex justify-between items-end pt-12 text-xs">
                    <div>
                        <div class="w-36 border-b border-slate-350"></div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">Customer Acknowledgment</span>
                    </div>
                    <div class="text-right">
                        <div class="text-[10px] text-primary italic font-bold uppercase tracking-wider">Hindustan Real Estate Co.</div>
                        <div class="h-10"></div> {{-- Placeholder space for digital signature --}}
                        <div class="w-44 border-b border-slate-350 ml-auto"></div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-1 block">Authorized Signatory</span>
                    </div>
                </div>

            </div>

            {{-- Action buttons --}}
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-150 flex justify-end gap-2.5">
                <button type="button" @click="modal.open = false" 
                        class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wide">
                    Close
                </button>
                <button type="button" @click="window.print()" 
                        class="px-4 py-2 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition uppercase tracking-wide shadow-md">
                    Print Voucher
                </button>
            </div>
        </div>
    </div>

</div>

<script>
function receiptsApp() {
    return {
        searchQuery: '',
        modal: {
            open: false
        },
        modalData: {},
        
        // Mock Bookings list for choice
        bookings: [
            { id: 101, bookingNo: 'BKG-409', customerName: 'Rajesh Kumar', projectName: 'Hindustan Royal Heights', unit: 'Block A - 502', outstanding: 345000 },
            { id: 102, bookingNo: 'BKG-521', customerName: 'Subramanian Swamy', projectName: 'Hindustan Imperial Garden', unit: 'Villa B-18', outstanding: 1280000 },
            { id: 103, bookingNo: 'BKG-219', customerName: 'Nandhini Chidambaram', projectName: 'Hindustan Smart Enclave', unit: 'Apt 202', outstanding: 89000 },
            { id: 104, bookingNo: 'BKG-318', customerName: 'Vikas Sharma', projectName: 'Hindustan Royal Heights', unit: 'Block B - 104', outstanding: 540000 }
        ],
        selectedBooking: null,

        form: {
            bookingIdx: '',
            amount: '',
            date: new Date().toISOString().slice(0, 10),
            mode: 'Cash',
            chequeNo: '',
            chequeDate: '',
            chequeBank: '',
            chequeBranch: '',
            reference: '',
            recipientBank: 'ICICI Current A/c ..9082'
        },

        // Mock Receipts ledger
        receipts: [
            { receiptNo: 'REC-09238', customerName: 'Rajesh Kumar', projectName: 'Hindustan Royal Heights', unit: 'Block A - 502', amount: 115000, date: '2026-07-02', mode: 'Cheque', details: 'No. 892348 · HDFC Bank', status: 'PENDING CLEARING' },
            { receiptNo: 'REC-09112', customerName: 'Subramanian Swamy', projectName: 'Hindustan Imperial Garden', unit: 'Villa B-18', amount: 350000, date: '2026-06-29', mode: 'Bank', details: 'UTR: SBIN2938491823', status: 'COMPLETED' },
            { receiptNo: 'REC-09048', customerName: 'Vikas Sharma', projectName: 'Hindustan Royal Heights', unit: 'Block B - 104', amount: 90000, date: '2026-06-28', mode: 'Online', details: 'UPI Ref: 6183920912', status: 'COMPLETED' },
            { receiptNo: 'REC-08992', customerName: 'Nandhini Chidambaram', projectName: 'Hindustan Smart Enclave', unit: 'Apt 202', amount: 25000, date: '2026-06-25', mode: 'Cash', details: 'Cash in Hand (Counter)', status: 'COMPLETED' },
            { receiptNo: 'REC-08819', customerName: 'Rajesh Kumar', projectName: 'Hindustan Royal Heights', unit: 'Block A - 502', amount: 115000, date: '2026-06-02', mode: 'Cheque', details: 'No. 892312 · HDFC Bank', status: 'COMPLETED' }
        ],

        updateBookingDetail() {
            if (this.form.bookingIdx !== '') {
                this.selectedBooking = this.bookings[this.form.bookingIdx];
                this.form.amount = this.selectedBooking.outstanding;
            } else {
                this.selectedBooking = null;
                this.form.amount = '';
            }
        },

        filteredReceipts() {
            if (this.searchQuery === '') return this.receipts;
            const q = this.searchQuery.toLowerCase();
            return this.receipts.filter(r => 
                r.customerName.toLowerCase().includes(q) ||
                r.receiptNo.toLowerCase().includes(q) ||
                r.projectName.toLowerCase().includes(q) ||
                r.mode.toLowerCase().includes(q)
            );
        },

        getStatusClasses(status) {
            if (status === 'COMPLETED') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            if (status === 'PENDING CLEARING') return 'bg-amber-50 text-amber-700 border border-amber-200';
            return 'bg-rose-50 text-rose-700 border border-rose-200';
        },

        submitReceipt() {
            if (!this.selectedBooking) {
                alert("Please select an active booking record.");
                return;
            }
            
            // Build details line
            let detailText = '';
            if (this.form.mode === 'Cash') {
                detailText = 'Cash in Hand (Counter)';
            } else if (this.form.mode === 'Cheque') {
                detailText = 'Cheque No: ' + this.form.chequeNo + ' · ' + this.form.chequeBank;
            } else {
                detailText = 'Ref: ' + this.form.reference;
            }

            const isCheque = this.form.mode === 'Cheque';

            const newRec = {
                receiptNo: 'REC-' + Math.floor(10000 + Math.random() * 90000),
                customerName: this.selectedBooking.customerName,
                projectName: this.selectedBooking.projectName,
                unit: this.selectedBooking.unit,
                amount: this.form.amount,
                date: this.form.date,
                mode: this.form.mode,
                details: detailText,
                status: isCheque ? 'PENDING CLEARING' : 'COMPLETED'
            };

            // Deduct outstanding
            this.selectedBooking.outstanding = Math.max(0, this.selectedBooking.outstanding - this.form.amount);

            // Add receipt
            this.receipts.unshift(newRec);

            // Reset form
            this.form.bookingIdx = '';
            this.selectedBooking = null;
            this.form.amount = '';
            this.form.mode = 'Cash';
            this.form.chequeNo = '';
            this.form.chequeDate = '';
            this.form.chequeBank = '';
            this.form.chequeBranch = '';
            this.form.reference = '';

            // Render lucide icons if applicable
            setTimeout(() => {
                lucide.createIcons();
            }, 100);

            alert("Receipt voucher saved successfully. Added Receipt: " + newRec.receiptNo);
        },

        markCleared(r) {
            r.status = 'COMPLETED';
            alert("Cheque associated with " + r.receiptNo + " marked as CLEARED.");
        },

        viewVoucher(r) {
            this.modalData = r;
            this.modal.open = true;
        },

        getAmountInWords(amount) {
            if (!amount) return 'Zero';
            // Simple English words converter for presentation
            const num = Math.floor(amount);
            const a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
            const b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];

            const inWords = (n) => {
                if (n < 20) return a[n];
                const digit = n % 10;
                return b[Math.floor(n / 10)] + (digit ? '-' + a[digit] : '');
            };

            const numString = num.toString();
            if (numString.length > 9) return 'Amount Too Large'; // limited to Crores
            
            // Format to Indian System: Crores, Lakhs, Thousands, Hundreds, Units
            let finalStr = '';
            let val = num;

            // Crores
            if (val >= 10000000) {
                const cr = Math.floor(val / 10000000);
                finalStr += inWords(cr) + ' Crore ';
                val %= 10000000;
            }
            // Lakhs
            if (val >= 100000) {
                const lk = Math.floor(val / 100000);
                finalStr += inWords(lk) + ' Lakh ';
                val %= 100000;
            }
            // Thousands
            if (val >= 1000) {
                const th = Math.floor(val / 1000);
                finalStr += inWords(th) + ' Thousand ';
                val %= 1000;
            }
            // Hundreds
            if (val >= 100) {
                const hd = Math.floor(val / 100);
                finalStr += inWords(hd) + ' Hundred ';
                val %= 100;
            }
            // Units
            if (val > 0) {
                finalStr += inWords(val);
            }

            return finalStr.trim() + ' Rupees';
        }
    };
}
</script>

</x-erp-layout>
