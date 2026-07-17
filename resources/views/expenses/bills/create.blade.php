<x-erp-layout>
    <x-slot:title>Create New Supplier Bill - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Site Expenses > Create New Supplier Bill</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto" x-data="addBillForm()" x-init="fetchProjectMetrics()">
        <!-- Multi-Step Progress Tracker Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-6">
            <div class="max-w-4xl mx-auto relative flex items-center justify-between">
                <!-- Progress Line -->
                <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-slate-200 -z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-primary transition-all duration-300 -z-0"
                     :style="'width: ' + ((step - 1) * 50) + '%'"></div>

                <!-- Step 1 Circle -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition duration-300"
                         :class="step >= 1 ? 'bg-primary text-white' : 'bg-slate-200 text-slate-600'">
                        1
                    </div>
                    <span class="text-[10px] font-bold mt-2 uppercase tracking-wider" :class="step >= 1 ? 'text-primary' : 'text-slate-500'">Supplier & Bill Details</span>
                    <span class="text-[8px] text-slate-400">Who is owed & legal details</span>
                </div>

                <!-- Step 2 Circle -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition duration-300"
                         :class="step >= 2 ? 'bg-primary text-white' : 'bg-slate-200 text-slate-600'">
                        2
                    </div>
                    <span class="text-[10px] font-bold mt-2 uppercase tracking-wider" :class="step >= 2 ? 'text-primary' : 'text-slate-500'">Bill Amount & Allocation</span>
                    <span class="text-[8px] text-slate-400">How much, when & where</span>
                </div>

                <!-- Step 3 Circle -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs transition duration-300"
                         :class="step >= 3 ? 'bg-primary text-white' : 'bg-slate-200 text-slate-600'">
                        3
                    </div>
                    <span class="text-[10px] font-bold mt-2 uppercase tracking-wider" :class="step >= 3 ? 'text-primary' : 'text-slate-500'">Upload Bill Document</span>
                    <span class="text-[8px] text-slate-400">Attach bill copy (Mandatory)</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-850 text-xs font-bold rounded-2xl p-4 mb-6 shadow-xs">
                <div class="uppercase tracking-wider text-[10px] mb-2 font-extrabold text-rose-800">Please correct the following errors:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('expenses.bills.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Two-Panel Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                <!-- Left Panel: Steps Form (Span 2) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Step 1 Content Panel -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-show="step === 1" x-transition>
                        <div class="px-6 py-5 bg-white border-b border-slate-100">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Step 1: Select Supplier / Contractor & Bill Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Supplier Selection -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier / Contractor <span class="text-rose-500">*</span></label>
                                <select name="payee_id" required x-model="form.payee_id" @change="onSupplierChange()"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 hover:border-slate-350 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition cursor-pointer">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Supplier Bill Number -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Supplier Bill Number (as per invoice) <span class="text-rose-500">*</span></label>
                                <input type="text" name="bill_number" required x-model="form.bill_number" placeholder="e.g. BR/25-26/0987"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Supplier GSTIN -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Supplier GSTIN</label>
                                <input type="text" x-model="form.gstin" placeholder="GSTIN number"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Supplier Bill Date -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier Bill Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="bill_date" required x-model="form.bill_date"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition-all">
                            </div>

                            <!-- Supplier PAN -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier PAN</label>
                                <input type="text" x-model="form.pan" placeholder="PAN details"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Bill Type -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Bill Type</label>
                                <select name="bill_type" x-model="form.bill_type"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="Material Supply">Material Supply</option>
                                    <option value="Labor Works">Labor Works</option>
                                    <option value="Professional Services">Professional Services</option>
                                </select>
                            </div>

                            <!-- Payment Terms -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Payment Terms</label>
                                <select name="payment_terms" x-model="form.payment_terms"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="Immediate">Immediate / Cash</option>
                                    <option value="30 Days">30 Days</option>
                                    <option value="45 Days">45 Days</option>
                                    <option value="60 Days">60 Days</option>
                                </select>
                            </div>

                            <!-- Place of Supply -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Place of Supply</label>
                                <select name="place_of_supply" x-model="form.place_of_supply"
                                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                    <option value="Tamil Nadu (33)">Tamil Nadu (33)</option>
                                    <option value="Kerala (32)">Kerala (32)</option>
                                    <option value="Karnataka (29)">Karnataka (29)</option>
                                </select>
                            </div>

                            <!-- Base Amount -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Base Amount (₹) <span class="text-rose-500">*</span></label>
                                <input type="number" name="bill_amount" required step="0.01" min="0.01" x-model.number="form.amount" @input="calcTotal(); fetchProjectMetrics()"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Tax Amount -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Tax / GST (₹)</label>
                                <input type="number" step="0.01" min="0" x-model.number="form.tax" @input="calcTotal(); fetchProjectMetrics()"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                            </div>

                            <!-- Total Bill Liability -->
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Total Amount (₹) <span class="text-rose-500">*</span></label>
                                <input type="number" name="final_amount" required step="0.01" min="0.01" x-model.number="form.total_amount" @input="calcBase(); fetchProjectMetrics()"
                                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-bold focus:outline-none transition">
                            </div>

                            <!-- Form Action Next Button -->
                            <div class="md:col-span-2 pt-4 flex justify-end">
                                <button type="button" @click="if (validateStep(1)) step = 2"
                                        class="flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                                    <span>Next</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 Content Panel -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-show="step === 2" x-transition>
                        <div class="px-6 py-5 bg-white border-b border-slate-100">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Step 2: Expense Head & Project Allocation</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Expense Head -->
                                <div class="space-y-1.5 col-span-2">
                                    <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Expense Head <span class="text-rose-500">*</span></label>
                                    <select name="expense_head" x-model="form.expense_head"
                                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none cursor-pointer">
                                        <option value="Cement">Cement</option>
                                        <option value="Steel/Rebars">Steel/Rebars</option>
                                        <option value="Sand & Bricks">Sand & Bricks</option>
                                        <option value="Plumbing Supplies">Plumbing Supplies</option>
                                        <option value="Electrical Cable">Electrical Cable</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Project Allocation Section -->
                            <div class="pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Project Allocation (Multi-Tenant)</label>
                                    <!-- Warning Badge if total != 100% -->
                                    <span class="text-[9px] font-extrabold uppercase px-2.5 py-0.5 rounded-lg transition"
                                          :class="totalAllocationPct() === 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100 animate-pulse'"
                                          x-text="totalAllocationPct() === 100 ? 'Total Allocated: 100%' : 'Total Allocated: ' + totalAllocationPct() + '% (Must be 100%)'">
                                    </span>
                                </div>
                                
                                <input type="hidden" name="allocations" :value="JSON.stringify(allocations)">
                                <input type="hidden" name="project_id" :value="allocations[0] ? allocations[0].project_id : ''">

                                <div class="space-y-3">
                                    <template x-for="(alloc, idx) in allocations" :key="idx">
                                        <div class="p-4 bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-xl flex flex-wrap sm:flex-nowrap items-center justify-between gap-4 text-xs font-semibold transition">
                                            <!-- Project Selection -->
                                            <div class="flex-1 min-w-[200px] space-y-1">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Project</span>
                                                <select x-model="alloc.project_id" @change="syncFirstProject()"
                                                        class="w-full px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-[#a38c29]/30 cursor-pointer">
                                                    @foreach($projects as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Allocation % -->
                                            <div class="w-24 space-y-1 text-center">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Allocation (%)</span>
                                                <input type="number" x-model.number="alloc.allocation_pct" min="1" max="100" @input="syncFirstProject()"
                                                       class="w-16 px-2 py-1 text-center bg-white border border-slate-200 rounded-lg font-mono font-bold focus:outline-none focus:ring-1 focus:ring-[#a38c29]/30">
                                            </div>
                                            <!-- Allocated Amount -->
                                            <div class="w-32 space-y-1 text-right">
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Amount (₹)</span>
                                                <div class="font-mono font-extrabold text-slate-900 pr-2 pt-1" x-text="formatCurrency(((alloc.allocation_pct || 0) / 100) * form.total_amount)"></div>
                                            </div>
                                            <!-- Action Button (Delete) -->
                                            <div class="pt-3 sm:pt-0 sm:mt-4 w-8 flex justify-center">
                                                <button type="button" @click="removeAllocationRow(idx)"
                                                        x-show="allocations.length > 1"
                                                        class="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="pt-3">
                                    <button type="button" @click="addAllocationRow()"
                                            class="text-xs font-extrabold text-[#a38c29] hover:text-[#8f7b24] flex items-center gap-1.5 focus:outline-none hover:-translate-y-0.5 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                                        <span>Add Another Project Allocation</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="pt-4 flex justify-between">
                                <button type="button" @click="step = 1"
                                        class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                    Back
                                </button>
                                <button type="button" @click="if (validateStep(2) && totalAllocationPct() === 100) step = 3"
                                        :disabled="totalAllocationPct() !== 100"
                                        :class="totalAllocationPct() !== 100 ? 'opacity-55 cursor-not-allowed bg-slate-300 hover:bg-slate-350' : 'bg-primary hover:bg-primary-700'"
                                        class="flex items-center gap-2 px-6 py-2.5 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                                    <span>Next</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 Content Panel -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-show="step === 3" x-transition>
                        <div class="px-6 py-5 bg-white border-b border-slate-100">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Step 3: Upload Bill Document (Mandatory)</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Drag & Drop Zone -->
                            <input type="file" name="bill_file" x-ref="fileInput" class="hidden" @change="onFileChange($event)" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="border-2 border-dashed border-slate-250 hover:border-primary hover:bg-primary-50/10 rounded-2xl p-8 text-center transition cursor-pointer"
                                 @dragover.prevent="dragover = true"
                                 @dragenter.prevent="dragover = true"
                                 @dragleave.prevent="dragover = false"
                                 @drop.prevent="onFileDrop($event); dragover = false"
                                 :class="dragover ? 'border-primary bg-primary-50/10' : ''"
                                 @click="$refs.fileInput.click()">
                                <svg class="w-10 h-10 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-xs text-slate-600 font-semibold mb-1">Drag & drop the bill / invoice here</p>
                                <p class="text-[10px] text-slate-400 mb-3">or</p>
                                <button type="button" class="px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold rounded-lg border border-slate-200 transition">
                                    Browse Files
                                </button>
                                <p class="text-[9px] text-slate-400 mt-2">Accepted formats: PDF, JPG, PNG (Max 10MB)</p>
                            </div>

                            <!-- Uploaded File Display -->
                            <div x-show="form.uploaded_file_name" class="p-4 border border-slate-200 rounded-2xl flex items-center justify-between bg-slate-50/50" x-transition>
                                <div class="flex items-center gap-3">
                                    <!-- PDF Icon -->
                                    <span class="text-rose-500">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A1 1 0 0112 2.586L15.414 6A1 1 0 0116 6.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v10h8V7h-3a1 1 0 01-1-1V4H6z" clip-rule="evenodd"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700" x-text="form.uploaded_file_name"></p>
                                        <p class="text-[9px] text-slate-400" x-text="form.uploaded_file_size"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-emerald-500">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    </span>
                                    <button type="button" @click="removeFile()" class="text-slate-400 hover:text-rose-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="pt-4 flex justify-between">
                                <button type="button" @click="step = 2"
                                        class="px-5 py-2.5 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                                    Back
                                </button>
                                <button type="submit"
                                        class="flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    <span>Save & Create Liability</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Bill Summary & Visualizations -->
                <div class="space-y-6">
                    
                    <!-- Bill Summary Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
                        <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider pb-3 border-b border-slate-100">Bill Summary <span class="text-slate-400 lowercase">(On Save)</span></h3>
                        
                        <div class="space-y-3 text-xs">
                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">ERP Bill Reference ID</span>
                                <div class="flex items-center gap-1.5 font-bold text-slate-700">
                                    <span x-text="systemRef"></span>
                                    <button type="button" class="text-slate-400 hover:text-slate-655">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Supplier</span>
                                <div class="font-bold text-slate-800 text-xs" x-text="form.supplier_name"></div>
                            </div>

                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Supplier Bill Number</span>
                                <div class="font-mono font-bold text-slate-805" x-text="form.bill_number"></div>
                            </div>

                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide text-blue-600">Total Bill Amount</span>
                                <div class="font-mono font-extrabold text-slate-900 text-lg" x-text="'₹' + formatCurrency(form.total_amount)"></div>
                            </div>

                            <div class="space-y-0.5">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Status</span>
                                <span class="inline-block px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-lg text-[9px] font-extrabold uppercase">Traced: NO</span>
                                <span class="text-[9px] text-slate-400 block mt-0.5">(Pending Customer Advance)</span>
                            </div>

                            <div class="space-y-1.5 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-455">
                                <div>
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase">Created By</span>
                                    <strong class="text-slate-700">{{ auth()->user()->name }}</strong>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[8px] font-bold text-slate-400 uppercase">Created On</span>
                                    <strong class="text-slate-700">{{ now()->format('d/m/Y h:i A') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liability Trace Visualization Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Liability Trace Visualization</h3>
                            <button type="button" class="text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                            </button>
                        </div>

                        <!-- Current State (A) -->
                        <div class="space-y-2">
                            <span class="inline-block px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[8px] font-bold uppercase tracking-wider">Current State (A)</span>
                            <div class="space-y-1">
                                <div class="text-[9px] text-slate-400">Total Liability (This Bill)</div>
                                <div class="text-sm font-extrabold text-slate-900 font-mono" x-text="'₹' + formatCurrency(form.total_amount)"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[9px] font-bold text-slate-500">
                                    <span>Liability Bucket (Pending Customer Advances)</span>
                                    <span>100%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-rose-500 h-1.5" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="text-[9px] font-bold text-rose-600" x-text="'₹' + formatCurrency(form.total_amount) + ' Pending'"></div>
                        </div>

                        <!-- Future State (B) -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[8px] font-bold uppercase tracking-wider">Future State (B) - After Receipts & Split</span>
                            
                            <div class="space-y-1">
                                <div class="flex items-center justify-between text-[9px] font-bold text-slate-500">
                                    <span>Liability Bucket (Progressive Realization)</span>
                                    <span x-text="projectMetrics.realized_pct + '%'"></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-primary h-1.5" :style="'width: ' + projectMetrics.realized_pct + '%'"></div>
                                </div>
                            </div>
                            <div class="text-[9px] font-bold text-slate-500 flex items-center justify-between">
                                <span class="text-primary font-extrabold" x-text="'₹' + formatCurrency(form.total_amount * (projectMetrics.realized_pct / 100)) + ' Paid'"></span>
                                <span class="text-slate-455" x-text="'₹' + formatCurrency(form.total_amount * (projectMetrics.pending_pct / 100)) + ' Pending'"></span>
                            </div>

                            <!-- Customer Paid List -->
                            <div class="space-y-2 text-[10px] bg-slate-50 p-3 rounded-xl border border-slate-200">
                                <div class="font-bold text-slate-500 text-[8px] uppercase tracking-wider text-left">Paid by Customers</div>
                                
                                <template x-for="cust in projectMetrics.customers">
                                    <div class="flex items-center justify-between text-slate-700">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            <span x-text="cust.name + ' (' + cust.units + ')'"></span>
                                        </div>
                                        <div class="font-bold text-slate-900 flex items-center gap-2">
                                            <span class="font-mono" x-text="'₹' + formatCurrency(form.total_amount * (cust.percentage / 100))"></span>
                                            <span class="text-slate-400 font-normal" x-text="cust.percentage + '%'"></span>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="projectMetrics.customers.length === 0">
                                    <div class="text-[9px] text-slate-400 italic py-1 text-left">No customer payments received for this project.</div>
                                </template>

                                <div class="flex items-center justify-between text-slate-455 border-t border-slate-200 pt-1.5 mt-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                        <span>Pending Outstanding</span>
                                    </div>
                                    <div class="font-bold text-slate-600 flex items-center gap-2">
                                        <span class="font-mono" x-text="'₹' + formatCurrency(form.total_amount * (projectMetrics.pending_pct / 100))"></span>
                                        <span class="text-slate-400 font-normal" x-text="projectMetrics.pending_pct + '%'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Routing Banner Info -->
                        <div class="p-3 bg-primary/5 border border-primary/20 rounded-xl flex items-start gap-2.5 text-xs text-primary-800">
                            <span class="text-primary mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                            </span>
                            <div class="space-y-0.5">
                                <p class="font-bold">Ready for Cash Routing</p>
                                <p class="text-[10px] text-primary-700">This bill is now available in Receipt & Split to be targeted for cash routing.</p>
                            </div>
                        </div>                  </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function addBillForm() {
            return {
                step: 1,
                dragover: false,
                suppliers: @json($suppliers),
                systemRef: '{{ $systemBillRef }}',
                projectMetrics: {
                    total_receipts: 0.0,
                    total_outstanding: 0.0,
                    total_value: 0.0,
                    realized_pct: 0,
                    pending_pct: 100,
                    customers: []
                },
                allocations: [
                    { project_id: '{{ $projects->first()?->id ?? "" }}', allocation_pct: 100 }
                ],
                addAllocationRow() {
                    const remaining = 100 - this.totalAllocationPct();
                    this.allocations.push({
                        project_id: '{{ $projects->first()?->id ?? "" }}',
                        allocation_pct: remaining > 0 ? remaining : 0
                    });
                    this.syncFirstProject();
                },
                removeAllocationRow(idx) {
                    this.allocations.splice(idx, 1);
                    this.syncFirstProject();
                },
                totalAllocationPct() {
                    return this.allocations.reduce((sum, item) => sum + (parseFloat(item.allocation_pct) || 0), 0);
                },
                syncFirstProject() {
                    if (this.allocations[0]) {
                        this.form.project_id = this.allocations[0].project_id;
                        this.fetchProjectMetrics();
                    }
                },
                form: {
                    payee_id: '',
                    supplier_name: '',
                    bill_number: '',
                    gstin: '',
                    bill_date: '{{ date('Y-m-d') }}',
                    pan: '',
                    bill_type: 'Material Supply',
                    payment_terms: '30 Days',
                    place_of_supply: 'Tamil Nadu (33)',
                    invoice_date: '{{ date('Y-m-d') }}',
                    expense_head: 'Cement',
                    amount: 0.00,
                    tax: 0.00,
                    total_amount: 0.00,
                    project_id: '{{ $projects->first()?->id ?? "" }}',
                    project_name: '{{ $projects->first()?->name ?? "" }}',
                    allocation_pct: 100,
                    uploaded_file_name: '',
                    uploaded_file_size: ''
                },
                onSupplierChange() {
                    const supplier = this.suppliers.find(s => s.id == this.form.payee_id);
                    if (supplier) {
                        this.form.supplier_name = supplier.name;
                        this.form.gstin = supplier.gstin || '';
                        this.form.pan = supplier.pan || '';
                    } else {
                        this.form.supplier_name = '';
                        this.form.gstin = '';
                        this.form.pan = '';
                    }
                },
                updateProjectName(el) {
                    this.form.project_name = el.options[el.selectedIndex]?.text || '';
                },
                fetchProjectMetrics() {
                    if (!this.form.project_id) {
                        this.projectMetrics = {
                            total_receipts: 0.0,
                            total_outstanding: 0.0,
                            total_value: 0.0,
                            realized_pct: 0,
                            pending_pct: 100,
                            customers: []
                        };
                        return;
                    }
                    fetch(`/expenses/project/${this.form.project_id}/metrics`)
                        .then(res => res.json())
                        .then(data => {
                            this.projectMetrics = data;
                        })
                        .catch(err => {
                            console.error('Error fetching project metrics:', err);
                        });
                },
                calcTotal() {
                    const amt = parseFloat(this.form.amount) || 0;
                    const tx = parseFloat(this.form.tax) || 0;
                    this.form.total_amount = parseFloat((amt + tx).toFixed(2));
                },
                calcBase() {
                    const total = parseFloat(this.form.total_amount) || 0;
                    const tx = parseFloat(this.form.tax) || 0;
                    this.form.amount = parseFloat((total - tx).toFixed(2));
                },
                formatCurrency(val) {
                    return Number(val.toFixed(2)).toLocaleString('en-IN', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },
                onFileChange(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.form.uploaded_file_name = file.name;
                        this.form.uploaded_file_size = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    }
                },
                onFileDrop(e) {
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        this.$refs.fileInput.files = e.dataTransfer.files;
                        this.form.uploaded_file_name = file.name;
                        this.form.uploaded_file_size = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    }
                },
                removeFile() {
                    this.$refs.fileInput.value = '';
                    this.form.uploaded_file_name = '';
                    this.form.uploaded_file_size = '';
                },
                validateStep(currentStep) {
                    const panel = document.querySelector(`[x-show="step === ${currentStep}"]`);
                    if (!panel) return true;
                    
                    const inputs = panel.querySelectorAll('input, select, textarea');
                    let isValid = true;
                    
                    for (let i = 0; i < inputs.length; i++) {
                        if (!inputs[i].checkValidity()) {
                            inputs[i].reportValidity();
                            isValid = false;
                            break;
                        }
                    }
                    return isValid;
                }
            }
        }
    </script>
</x-erp-layout>
