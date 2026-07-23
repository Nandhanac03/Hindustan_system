<x-erp-layout>
    <x-slot:title>Supplier Master - HindustanERP</x-slot:title>
    <x-slot:headerTitle>Suppliers > Supplier Master</x-slot:headerTitle>

    <div class="max-w-[1800px] mx-auto space-y-6" x-data="supplierDirectory()">
        <!-- Section Header -->
        <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Supplier Master Directory</h1>
                <p class="text-xs text-slate-450 mt-1">Manage external contractors, vendors, and suppliers associated with site development expenses.</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold" x-text="'Registered Suppliers: ' + suppliers.length"></span>
            </div>
        </div>

        @if(session('status'))
            <div class="p-4 bg-emerald-50 border border-emerald-250 text-emerald-800 text-xs font-bold rounded-2xl shadow-2xs">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-250 text-rose-800 text-xs font-bold rounded-2xl shadow-2xs">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- Left Card: Add New Supplier Form -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-white border-b border-slate-100">
                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Register New Supplier</h3>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <!-- Supplier Name -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Supplier / Contractor Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. BuildRight Constructions Pvt. Ltd."
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 focus:bg-white focus:ring-2 focus:ring-blue-500/20 rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition">
                    </div>

                    <!-- Phone & Email -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Phone Number</label>
                            <input type="text" name="phone" placeholder="e.g. +91 9876543210"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Email Address</label>
                            <input type="email" name="email" placeholder="e.g. contact@supplier.com"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none transition">
                        </div>
                    </div>

                    <!-- GSTIN & PAN -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier GSTIN</label>
                            <input type="text" name="gstin" placeholder="e.g. 33AABCB1234C1Z5"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none transition">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier PAN</label>
                            <input type="text" name="pan" placeholder="e.g. AABCB1234C"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none transition">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Office Address</label>
                        <textarea name="address" rows="2" placeholder="Postal address details..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 px-4 py-2 focus:outline-none transition resize-none"></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition shadow-sm uppercase tracking-wider">
                            Save Supplier
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Card: Registered Suppliers Directory (Span 2) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Registered Suppliers Directory</h3>
                    
                    <!-- Search Input -->
                    <input type="text" x-model="searchQuery" placeholder="Search by name, GSTIN, or account..."
                           class="w-64 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:bg-white focus:border-blue-500 transition">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                <th class="px-5 py-4">Supplier Name / Info</th>
                                <th class="px-5 py-4">Tax / Legal IDs</th>
                                <th class="px-5 py-4">Contact details</th>
                                <th class="px-5 py-4">Office Address</th>
                                <th class="px-5 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            <template x-for="sup in filteredSuppliers()" :key="sup.id">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800 text-xs" x-text="sup.name"></div>
                                        <div class="font-mono text-[9px] text-blue-600 font-bold mt-1" x-text="sup.linked_account ? sup.linked_account.code : 'SUP-ACC-xxxx'"></div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-700">GSTIN: <span class="font-mono text-slate-900" x-text="sup.gstin || 'N/A'"></span></div>
                                        <div class="text-[10px] text-slate-450 mt-0.5">PAN: <span class="font-mono text-slate-900" x-text="sup.pan || 'N/A'"></span></div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-600" x-text="sup.phone || 'No Phone'"></div>
                                        <div class="text-slate-405" x-text="sup.email || 'No Email'"></div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 font-semibold" x-text="sup.address || 'N/A'"></td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Edit trigger -->
                                            <button type="button" @click="editSupplier(sup)" class="p-2 rounded-lg bg-[#09876B]/10 hover:bg-[#09876B]/20 text-[#09876B] hover:text-[#076852] transition inline-flex items-center justify-center shadow-sm" title="Edit Supplier">
                                                <svg class="w-4 h-4 text-[#09876B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            
                                            <!-- Delete -->
                                            <form :action="'/suppliers/' + sup.id" method="POST" onsubmit="return confirm('Are you sure you want to remove this supplier?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-red-600/10 hover:bg-red-600/20 text-red-600 hover:text-red-700 transition inline-flex items-center justify-center shadow-sm" title="Delete Supplier">
                                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredSuppliers().length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-450 font-bold">
                                    No registered suppliers match the search query.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Popup Modal Wrapper -->
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop" style="display: none;" x-transition.opacity>
             <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up" @click.away="showEditModal = false">
                  {{-- Header --}}
                  <div class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-5 border-b border-[#a38c29]/10">
                      <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#a38c29]/15 rounded-full blur-3xl pointer-events-none"></div>
                      <div class="relative z-10 flex items-center justify-between gap-4">
                          <div>
                              <span class="px-2 py-0.5 rounded bg-[#a38c29]/20 text-[#d9bf3b] text-[9px] font-bold uppercase tracking-widest whitespace-nowrap">Supplier Management</span>
                              <h2 class="text-sm font-extrabold text-white uppercase tracking-wider mt-1">Edit Supplier Details</h2>
                          </div>
                          <button type="button" @click="showEditModal = false" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition focus:outline-none shrink-0 text-xs">✕</button>
                      </div>
                  </div>

                  <form :action="'/suppliers/' + editForm.id" method="POST">
                      @csrf
                      @method('PUT')
                      
                      <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto font-sans text-xs bg-slate-50/50">
                          <div class="bg-white p-5 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
                              <!-- Supplier Name -->
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-450 uppercase tracking-widest block">Supplier Name <span class="text-rose-500">*</span></label>
                                  <input type="text" name="name" required x-model="editForm.name"
                                         class="w-full px-3 py-2 bg-slate-50 border border-slate-250 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 font-semibold focus:outline-none transition shadow-sm">
                              </div>

                              <!-- Phone & Email -->
                              <div class="grid grid-cols-2 gap-4">
                                  <div class="space-y-1.5">
                                      <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Phone Number</label>
                                      <input type="text" name="phone" x-model="editForm.phone"
                                             class="w-full px-3 py-2 bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold focus:outline-none transition shadow-sm text-slate-700">
                                  </div>
                                  <div class="space-y-1.5">
                                      <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Email Address</label>
                                      <input type="email" name="email" x-model="editForm.email"
                                             class="w-full px-3 py-2 bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold focus:outline-none transition shadow-sm text-slate-700">
                                  </div>
                              </div>

                              <!-- GSTIN & PAN -->
                              <div class="grid grid-cols-2 gap-4">
                                  <div class="space-y-1.5">
                                      <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier GSTIN</label>
                                      <input type="text" name="gstin" x-model="editForm.gstin"
                                             class="w-full px-3 py-2 bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold focus:outline-none transition shadow-sm text-slate-700 font-mono">
                                  </div>
                                  <div class="space-y-1.5">
                                      <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Supplier PAN</label>
                                      <input type="text" name="pan" x-model="editForm.pan"
                                             class="w-full px-3 py-2 bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs font-semibold focus:outline-none transition shadow-sm text-slate-700 font-mono">
                                  </div>
                              </div>

                              <!-- Address -->
                              <div class="space-y-1.5">
                                  <label class="text-[10px] font-bold text-slate-455 uppercase tracking-widest block">Office Address</label>
                                  <textarea name="address" rows="2" x-model="editForm.address"
                                            class="w-full bg-slate-50 border border-slate-255 focus:bg-white focus:ring-4 focus:ring-[#a38c29]/10 focus:border-[#a38c29] rounded-xl text-xs text-slate-800 px-4 py-2 focus:outline-none transition resize-none shadow-sm font-semibold"></textarea>
                              </div>
                          </div>
                      </div>

                      <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-2 bg-slate-50">
                          <button type="button" @click="showEditModal = false"
                                  class="px-4 py-2 border border-slate-250 hover:bg-slate-100 text-slate-655 text-xs font-bold rounded-xl transition uppercase tracking-wider">
                              Cancel
                          </button>
                          <button type="submit" class="px-5 py-2 bg-[#a38c29] hover:bg-[#8e7a23] text-white text-xs font-bold rounded-xl transition shadow-md shadow-[#a38c29]/20 uppercase tracking-wider">
                              Save Changes
                          </button>
                      </div>
                  </form>
             </div>
        </div>
    </div>

    <script>
        function supplierDirectory() {
            return {
                suppliers: @json($suppliers),
                searchQuery: '',
                showEditModal: false,
                editForm: {
                    id: '',
                    name: '',
                    phone: '',
                    email: '',
                    gstin: '',
                    pan: '',
                    address: ''
                },
                filteredSuppliers() {
                    if (!this.searchQuery) return this.suppliers;
                    const q = this.searchQuery.toLowerCase();
                    return this.suppliers.filter(sup => {
                        return sup.name.toLowerCase().includes(q) ||
                               (sup.gstin && sup.gstin.toLowerCase().includes(q)) ||
                               (sup.pan && sup.pan.toLowerCase().includes(q)) ||
                               (sup.linked_account && sup.linked_account.code.toLowerCase().includes(q));
                    });
                },
                editSupplier(sup) {
                    this.editForm.id = sup.id;
                    this.editForm.name = sup.name;
                    this.editForm.phone = sup.phone || '';
                    this.editForm.email = sup.email || '';
                    this.editForm.gstin = sup.gstin || '';
                    this.editForm.pan = sup.pan || '';
                    this.editForm.address = sup.address || '';
                    this.showEditModal = true;
                }
            }
        }
    </script>
</x-erp-layout>
