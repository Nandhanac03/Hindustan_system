{{-- ApexCharts Library --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
function reportsApp() {
    return {
        activeTab: '{{ $activeTab }}',
        customerList: {!! json_encode($customers->map(function($c) { return ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone, 'email' => $c->email]; })) !!},
        selectedCustomerId: '{{ request('customer_id', '') }}',

        getFilteredCustomersList(search = '') {
            const q = (search || '').toLowerCase().trim();
            if (!q) return this.customerList;
            return this.customerList.filter(c => 
                (c.name && c.name.toLowerCase().includes(q)) || 
                (c.phone && c.phone.toLowerCase().includes(q))
            );
        },
        getSelectedCustomer() {
            if (!this.selectedCustomerId) return null;
            return this.customerList.find(c => c.id == this.selectedCustomerId) || null;
        },
        selectCustomer(customer) {
            this.selectedCustomerId = customer ? customer.id : '';
            this.$nextTick(() => {
                const form = document.getElementById('customerLedgerForm');
                if (form) form.submit();
            });
        },

        init() {
            this.$nextTick(() => {
                this.renderAllCharts();
            });
        },

        printReport() {
            window.print();
        },

        exportCurrentTable() {
            const table = document.querySelector("#reportsTable");
            if (!table) {
                alert("No table available on this report tab to export.");
                return;
            }
            let html = table.outerHTML;
            // Remove styling and interactive components
            html = html.replace(/<button[^>]*>([\s\S]*?)<\/button>/gi, '');
            html = html.replace(/<input[^>]*>/gi, '');
            const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'HindustanERP_Report_' + this.activeTab + '.xls';
            a.click();
        },

        renderAllCharts() {
            // Render specific charts depending on the active tab
            
            // 16. DASHBOARD & MIS
            @if($activeTab === 'dashboard')
            if (this.activeTab === 'dashboard') {
                const sold = {{ $dashboardData['sold_units'] ?? 0 }};
                const unsold = {{ $dashboardData['unsold_units'] ?? 0 }};

                if (document.querySelector("#soldUnsoldChart")) {
                    new ApexCharts(document.querySelector("#soldUnsoldChart"), {
                        series: [sold, unsold],
                        labels: ['Sold Units', 'Unsold Units'],
                        chart: { type: 'donut', height: 230, fontFamily: 'Inter, sans-serif' },
                        colors: ['#059669', '#3b82f6'],
                        legend: { position: 'bottom', fontSize: '12px', fontWeight: 600, labels: { colors: '#64748b' } },
                        stroke: { width: 2, colors: ['#ffffff'] },
                        dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 'bold' } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Units',
                                            fontSize: '12px',
                                            fontWeight: 700,
                                            color: '#64748b'
                                        },
                                        value: {
                                            color: '#0f172a'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                if (document.querySelector("#collectionsExpectedChart")) {
                    new ApexCharts(document.querySelector("#collectionsExpectedChart"), {
                        series: [{
                            name: 'Amount (₹)',
                            data: [{{ $dashboardData['collections'] ?? 0 }}, {{ $dashboardData['outstanding'] ?? 0 }}]
                        }],
                        chart: { type: 'bar', height: 230, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#f59e0b'],
                        plotOptions: {
                            bar: {
                                columnWidth: '45%',
                                borderRadius: 6,
                                distributed: true
                            }
                        },
                        legend: { show: false },
                        dataLabels: {
                            enabled: true,
                            formatter: function (val) {
                                return '₹' + new Intl.NumberFormat('en-IN').format(val);
                            },
                            style: { fontSize: '10px', fontWeight: 'bold' }
                        },
                        xaxis: {
                            categories: ['Total Collections', 'Outstanding'],
                            labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: '700' } }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#64748b' },
                                formatter: function(val) {
                                    return '₹' + (val / 100000).toFixed(1) + 'L';
                                }
                            }
                        }
                    }).render();
                }

                // Profitability charts
                const projNames = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        '{{ $row['project']->name }}',
                    @endforeach
                ];
                const actRevs = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['actual_revenue'] }},
                    @endforeach
                ];
                const totCosts = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['total_cost'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#revenueCostChart"), {
                    series: [
                        { name: 'Actual Revenue', data: actRevs },
                        { name: 'Total Cost', data: totCosts }
                    ],
                    chart: { type: 'bar', height: 250, toolbar: { show: false } },
                    colors: ['#10b981', '#ef4444'],
                    plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 3 } },
                    xaxis: { categories: projNames }
                }).render();

                const margins = [
                    @foreach($dashboardData['project_profitability'] as $row)
                        {{ $row['margin'] }},
                    @endforeach
                ];
                new ApexCharts(document.querySelector("#profitMarginsChart"), {
                    series: [{ name: 'Profit Margin %', data: margins }],
                    chart: { type: 'line', height: 250, toolbar: { show: false } },
                    colors: ['#a38c29'],
                    stroke: { width: 3, curve: 'smooth' },
                    markers: { size: 4 },
                    yaxis: { labels: { formatter: (v) => v.toFixed(1) + '%' } },
                    xaxis: { categories: projNames }
                }).render();
            }
            @endif

            // 1. AVAILABILITY
            @if($activeTab === 'availability')
            if (this.activeTab === 'availability') {
                new ApexCharts(document.querySelector("#availabilityDistributionChart"), {
                    series: [
                        {{ $inventoryGrid->where('status', 'available')->count() }},
                        {{ $inventoryGrid->where('status', 'sold')->count() }},
                        {{ $inventoryGrid->where('status', 'booked')->count() }},
                        {{ $inventoryGrid->where('status', 'reserved')->count() }}
                    ],
                    labels: ['Available', 'Sold', 'Booked', 'Reserved'],
                    chart: { type: 'donut', height: 200 },
                    colors: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6'],
                    legend: { position: 'bottom' }
                }).render();

                const unitTypeNames = {!! json_encode($groupedSummary->pluck('type')) !!};
                const unitTypeCounts = {!! json_encode($groupedSummary->pluck('nos')) !!};

                new ApexCharts(document.querySelector("#unitTypeDistributionChart"), {
                    series: [{
                        name: 'Units Count',
                        data: unitTypeCounts
                    }],
                    chart: { type: 'bar', height: 200, toolbar: { show: false } },
                    colors: ['#6366f1'],
                    plotOptions: { bar: { columnWidth: '40%', borderRadius: 4 } },
                    xaxis: { categories: unitTypeNames }
                }).render();
            }
            @endif

            // 2. SALES
            @if($activeTab === 'sales')
            if (this.activeTab === 'sales') {
                const sMonths  = {!! json_encode($salesChartData['months'] ?? []) !!};
                const sAmounts = {!! json_encode($salesChartData['amounts'] ?? []) !!};
                const sProjects = {!! json_encode($salesChartData['project_names'] ?? []) !!};
                const sCounts   = {!! json_encode($salesChartData['project_counts'] ?? []) !!};

                const monthlyChartEl = document.querySelector("#monthlySalesTrendChart");
                if (monthlyChartEl) {
                    new ApexCharts(monthlyChartEl, {
                        series: [{
                            name: 'Sales Value (₹)',
                            data: sAmounts
                        }],
                        chart: { type: 'area', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29'],
                        stroke: { curve: 'smooth', width: 3 },
                        fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                        xaxis: { categories: sMonths },
                        yaxis: { labels: { formatter: (v) => '₹' + (v >= 10000000 ? (v/10000000).toFixed(1)+'Cr' : (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K')) } },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                    }).render();
                }

                const projectChartEl = document.querySelector("#salesByProjectChart");
                if (projectChartEl) {
                    new ApexCharts(projectChartEl, {
                        series: [{
                            name: 'Active Sales Count',
                            data: sCounts
                        }],
                        chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#f97316'],
                        plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
                        xaxis: { categories: sProjects.length ? sProjects : ['No Projects'] },
                        tooltip: { y: { formatter: (v) => v + ' Units Sold' } }
                    }).render();
                }
            }
            @endif

            // 3. EMI & COLLECTIONS
            @if($activeTab === 'emi_collections')
            if (this.activeTab === 'emi_collections') {
                const emiMonths  = {!! json_encode($emiChartData['months'] ?? []) !!};
                const emiAmounts = {!! json_encode($emiChartData['amounts'] ?? []) !!};

                new ApexCharts(document.querySelector("#emiOutstandingCollectionChart"), {
                    series: [{{ $emiCollectionsSummary['total_received'] ?? 0 }}, {{ $emiCollectionsSummary['outstanding'] ?? 0 }}],
                    labels: ['Collected', 'Outstanding'],
                    chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
                    dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Value',
                                        formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                    }
                                }
                            }
                        }
                    }
                }).render();

                new ApexCharts(document.querySelector("#emiCollectionTrendChart"), {
                    series: [{
                        name: 'Monthly Collections (₹)',
                        data: emiAmounts
                    }],
                    chart: { type: 'area', height: 200, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: emiMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 4. CUSTOMER LEDGER
            @if($activeTab === 'customer_ledger')
            if (this.activeTab === 'customer_ledger' && document.querySelector("#customerPaymentHistoryChart")) {
                const customerCredits = {!! json_encode(($ledgerEntries ?? collect())->where('credit', '>', 0)->pluck('credit')->map(fn($v) => (float)$v)->values()) !!};
                const customerDates   = {!! json_encode(($ledgerEntries ?? collect())->where('credit', '>', 0)->pluck('date')->values()) !!};
                new ApexCharts(document.querySelector("#customerPaymentHistoryChart"), {
                    series: [{
                        name: 'Receipt Payments (₹)',
                        data: customerCredits.length ? customerCredits : [0]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#10b981'],
                    plotOptions: { bar: { columnWidth: '45%', borderRadius: 3 } },
                    xaxis: { categories: customerDates.length ? customerDates : ['No Payments'] },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 5. CASH BOOK — Partner Analytics Dashboard
            @if($activeTab === 'cash_book')
            if (this.activeTab === 'cash_book') {
                // Monthly collections bar chart
                const cbMonthlyLabels  = {!! json_encode(array_column($cashBookChartData['monthly'], 'label')) !!};
                const cbMonthlyAmounts = {!! json_encode(array_column($cashBookChartData['monthly'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbMonthlyChart"), {
                    series: [{ name: 'Collections', data: cbMonthlyAmounts }],
                    chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#a38c29'],
                    plotOptions: { bar: { columnWidth: '55%', borderRadius: 4 } },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbMonthlyLabels,
                        labels: { style: { fontSize: '9px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v))
                        }
                    },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Payment mode donut
                const cbModeLabels  = {!! json_encode($cashBookChartData['payment_modes']->pluck('payment_mode')->map(fn($m) => $m ?? '-')) !!};
                const cbModeAmounts = {!! json_encode($cashBookChartData['payment_modes']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbModeLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPaymentModeChart"), {
                        series: cbModeAmounts,
                        labels: cbModeLabels,
                        chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#f97316'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: { fontSize: '10px', fontWeight: 600, color: '#64748b' },
                                        value: { fontSize: '11px', fontWeight: 700, color: '#0f172a' },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '10px',
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: (w) => {
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                if (total >= 10000000) return '₹' + (total / 10000000).toFixed(2) + ' Cr';
                                                if (total >= 100000) return '₹' + (total / 100000).toFixed(2) + ' L';
                                                return '₹' + total.toLocaleString('en-IN');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                // Daily trend line chart (last 30 days)
                const cbDailyLabels  = {!! json_encode(array_column($cashBookChartData['daily'], 'label')) !!};
                const cbDailyAmounts = {!! json_encode(array_column($cashBookChartData['daily'], 'amount')) !!};
                new ApexCharts(document.querySelector("#cbDailyTrendChart"), {
                    series: [{ name: 'Daily Collections', data: cbDailyAmounts }],
                    chart: { type: 'area', height: 200, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: cbDailyLabels,
                        labels: { rotate: -45, style: { fontSize: '8px' }, show: cbDailyLabels.length <= 15 }
                    },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                }).render();

                // Partner-wise donut chart
                const cbPartnerLabels  = {!! json_encode($cashBookChartData['partner_wise']->map(fn($r) => $r->partner?->name ?? '-')) !!};
                const cbPartnerAmounts = {!! json_encode($cashBookChartData['partner_wise']->pluck('total')->map(fn($v) => (float)$v)) !!};
                if (cbPartnerLabels.length > 0) {
                    new ApexCharts(document.querySelector("#cbPartnerDonutChart"), {
                        series: cbPartnerAmounts,
                        labels: cbPartnerLabels,
                        chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316', '#8b5cf6'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: { fontSize: '10px', fontWeight: 600, color: '#64748b', offsetY: -2 },
                                        value: { fontSize: '11px', fontWeight: 700, color: '#0f172a', offsetY: 2 },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '10px',
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: (w) => {
                                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                if (total >= 10000000) return '₹' + (total / 10000000).toFixed(2) + ' Cr';
                                                if (total >= 100000) return '₹' + (total / 100000).toFixed(2) + ' L';
                                                return '₹' + total.toLocaleString('en-IN');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                // Partner comparison bar chart (if multiple partners)
                @if($cashBookChartData['partner_wise']->count() > 1)
                const cbPartnerBarEl = document.querySelector("#cbPartnerBarChart");
                if (cbPartnerBarEl) {
                    new ApexCharts(cbPartnerBarEl, {
                        series: [{ name: 'Total Received', data: cbPartnerAmounts }],
                        chart: { type: 'bar', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316'],
                        plotOptions: { bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } } },
                        dataLabels: {
                            enabled: true,
                            formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN'),
                            style: { fontSize: '9px', colors: ['#475569'] },
                            offsetX: 5
                        },
                        xaxis: {
                            categories: cbPartnerLabels,
                            labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') }
                        },
                        grid: { borderColor: '#f1f5f9' },
                        tooltip: { y: { formatter: (v) => '₹' + v.toLocaleString('en-IN') } }
                    }).render();
                }
                @endif
            }
            @endif

            // 6. BANK REPORTS
            @if($activeTab === 'bank_reports')
            if (this.activeTab === 'bank_reports') {
                const bankMonths  = {!! json_encode($bankChartData['months'] ?? []) !!};
                const bankAmounts = {!! json_encode($bankChartData['amounts'] ?? []) !!};
                new ApexCharts(document.querySelector("#bankTransactionsChart"), {
                    series: [{
                        name: 'Bank Clearances',
                        data: bankAmounts
                    }],
                    chart: { type: 'area', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: bankMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 7. PARTNER STATEMENTS
            @if($activeTab === 'partner_statements')
            if (this.activeTab === 'partner_statements') {
                const partnerMonths  = {!! json_encode($partnerChartData['months'] ?? []) !!};
                const partnerAmounts = {!! json_encode($partnerChartData['amounts'] ?? []) !!};
                const pLabels = {!! json_encode($partnerChartData['partner_labels'] ?? []) !!};
                const pTotals = {!! json_encode($partnerChartData['partner_totals'] ?? []) !!};

                new ApexCharts(document.querySelector("#partnerStatementsChart"), {
                    series: [{
                        name: 'Capital Outflow Allocated',
                        data: partnerAmounts
                    }],
                    chart: { type: 'bar', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#a38c29'],
                    plotOptions: {
                        bar: {
                            columnWidth: '32%',
                            borderRadius: 6,
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: (v) => v > 0 ? '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') : '',
                        offsetY: -20,
                        style: { fontSize: '10px', fontWeight: 700, colors: ['#a38c29'] }
                    },
                    xaxis: {
                        categories: partnerMonths,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)),
                            style: { colors: '#94a3b8', fontSize: '10px' }
                        }
                    },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();

                if (pLabels.length > 0 && document.querySelector("#partnerDistributionChart")) {
                    new ApexCharts(document.querySelector("#partnerDistributionChart"), {
                        series: pTotals,
                        labels: pLabels,
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29', '#10b981', '#3b82f6', '#f97316', '#8b5cf6'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Outflow',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }
            }
            @endif

            // 8. SUPPLIER & CONTRACTOR
            @if($activeTab === 'supplier_contractor')
            if (this.activeTab === 'supplier_contractor') {
                const supplierLabels = {!! json_encode($supplierChartData['labels'] ?? []) !!};
                const supplierDues   = {!! json_encode($supplierChartData['dues'] ?? []) !!};
                const supplierPaids  = {!! json_encode($supplierChartData['paids'] ?? []) !!};
                new ApexCharts(document.querySelector("#supplierPayablesChart"), {
                    series: [
                        { name: 'Commission Due', data: supplierDues },
                        { name: 'Commission Paid', data: supplierPaids }
                    ],
                    chart: { type: 'bar', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#f97316', '#10b981'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { columnWidth: '30%', borderRadius: 4 } },
                    xaxis: { categories: supplierLabels.length ? supplierLabels : ['No Brokers'] },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 9. SALES RETURN
            @if($activeTab === 'sales_return')
            if (this.activeTab === 'sales_return') {
                const retMonths  = {!! json_encode($salesReturnChartData['months'] ?? []) !!};
                const retFees    = {!! json_encode($salesReturnChartData['fees'] ?? []) !!};
                const retRefunds = {!! json_encode($salesReturnChartData['refunds'] ?? []) !!};
                const totalFee   = {{ $salesReturnChartData['total_fee'] ?? 0 }};
                const totalRefund = {{ $salesReturnChartData['total_refund'] ?? 0 }};

                new ApexCharts(document.querySelector("#salesReturnChart"), {
                    series: [
                        { name: 'Cancellation Fees Retained (₹)', data: retFees },
                        { name: 'Refund Amount Payable (₹)', data: retRefunds }
                    ],
                    chart: { type: 'area', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#ef4444', '#10b981'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.05 } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: retMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)) } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();

                if (document.querySelector("#salesReturnDonutChart")) {
                    new ApexCharts(document.querySelector("#salesReturnDonutChart"), {
                        series: [totalFee, totalRefund],
                        labels: ['Cancellation Fee Retained', 'Refund Payable'],
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#ef4444', '#10b981'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Value',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }
            }
            @endif

            // 10. EXCHANGE REPORT
            @if($activeTab === 'exchange_report')
            if (this.activeTab === 'exchange_report') {
                const exMonths   = {!! json_encode($exchangeChartData['months'] ?? []) !!};
                const exEquities = {!! json_encode($exchangeChartData['equities'] ?? []) !!};
                new ApexCharts(document.querySelector("#unitExchangesChart"), {
                    series: [{
                        name: 'Transferred Equity Applied (₹)',
                        data: exEquities
                    }],
                    chart: { type: 'area', height: 180, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                    xaxis: { categories: exMonths },
                    yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 11. PETTY CASH
            @if($activeTab === 'petty_cash')
            if (this.activeTab === 'petty_cash') {
                const pcMonths  = {!! json_encode($pettyCashChartData['months'] ?? []) !!};
                const pcAmounts = {!! json_encode($pettyCashChartData['amounts'] ?? []) !!};
                const pcCustLabels = {!! json_encode($pettyCashChartData['cust_labels'] ?? []) !!};
                const pcCustTotals = {!! json_encode($pettyCashChartData['cust_totals'] ?? []) !!};

                if (pcCustLabels.length > 0 && document.querySelector("#pettyCashCustomerChart")) {
                    new ApexCharts(document.querySelector("#pettyCashCustomerChart"), {
                        series: pcCustTotals,
                        labels: pcCustLabels,
                        chart: { type: 'donut', height: 210, fontFamily: 'Inter, sans-serif' },
                        colors: ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#a38c29'],
                        legend: { position: 'bottom', fontSize: '10px', fontWeight: 600 },
                        dataLabels: { formatter: (val) => val.toFixed(1) + '%' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Inflow',
                                            formatter: (w) => '₹' + (w.globals.seriesTotals.reduce((a, b) => a + b, 0) / 100000).toFixed(1) + 'L'
                                        }
                                    }
                                }
                            }
                        }
                    }).render();
                }

                if (document.querySelector("#pettyCashChart")) {
                    new ApexCharts(document.querySelector("#pettyCashChart"), {
                        series: [{
                            name: 'Cash Collections',
                            data: pcAmounts
                        }],
                        chart: { type: 'bar', height: 210, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#a38c29'],
                        plotOptions: {
                            bar: {
                                columnWidth: '32%',
                                borderRadius: 6,
                                dataLabels: { position: 'top' }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (v) => v > 0 ? '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v/1000).toFixed(0)+'K') : '',
                            offsetY: -20,
                            style: { fontSize: '10px', fontWeight: 700, colors: ['#a38c29'] }
                        },
                        xaxis: {
                            categories: pcMonths,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: { style: { colors: '#64748b', fontSize: '10px', fontWeight: 600 } }
                        },
                        yaxis: {
                            labels: {
                                formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)),
                                style: { colors: '#94a3b8', fontSize: '10px' }
                            }
                        },
                        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                        tooltip: {
                            y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') }
                        }
                    }).render();
                }
            }
            @endif

            // 12. BANK LOAN EMI
            @if($activeTab === 'loan_schedules')
            if (this.activeTab === 'loan_schedules') {
                const el = document.querySelector("#bankLoanEmiChart");
                if (el) {
                    const lMonths     = {!! json_encode($loanChartData['months'] ?? []) !!};
                    const lPrincipals = {!! json_encode($loanChartData['principals'] ?? []) !!};
                    const lInterests  = {!! json_encode($loanChartData['interests'] ?? []) !!};
                    new ApexCharts(el, {
                        series: [
                            { name: 'Principal Component', data: lPrincipals },
                            { name: 'Interest Component', data: lInterests }
                        ],
                        chart: { type: 'bar', height: 210, stacked: true, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#3b82f6', '#ef4444'],
                        dataLabels: { enabled: false },
                        plotOptions: { bar: { columnWidth: '30%', borderRadius: 4 } },
                        xaxis: { categories: lMonths },
                        yaxis: { labels: { formatter: (v) => '₹' + (v >= 100000 ? (v/100000).toFixed(1)+'L' : (v >= 1000 ? (v/1000).toFixed(0)+'K' : v)) } },
                        grid: { borderColor: '#f1f5f9' },
                        tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                    }).render();
                }
            }
            @endif

            // 13. TRIAL BALANCE
            @if($activeTab === 'trial_balance')
            if (this.activeTab === 'trial_balance') {
                new ApexCharts(document.querySelector("#trialBalanceChart"), {
                    series: [
                        { name: 'Total Debit', data: [{{ $trialBalanceEntries['grand_total_debit'] ?? 0 }}] },
                        { name: 'Total Credit', data: [{{ $trialBalanceEntries['grand_total_credit'] ?? 0 }}] }
                    ],
                    chart: { type: 'bar', height: 140, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    colors: ['#3b82f6', '#10b981'],
                    dataLabels: { enabled: false },
                    plotOptions: { bar: { horizontal: true, barHeight: '35%', borderRadius: 4 } },
                    xaxis: { labels: { formatter: (v) => '₹' + (v >= 10000000 ? (v/10000000).toFixed(1)+'Cr' : (v/100000).toFixed(1)+'L') } },
                    grid: { borderColor: '#f1f5f9' },
                    tooltip: { y: { formatter: (v) => '₹' + parseFloat(v).toLocaleString('en-IN') } }
                }).render();
            }
            @endif

            // 14. PROFIT & LOSS
            @if($activeTab === 'profit_loss')
            if (this.activeTab === 'profit_loss') {
                const revenue = {{ $profitLossEntries['revenue'] ?? 0 }};
                const cost = {{ ($profitLossEntries['brokerage'] ?? 0) + ($profitLossEntries['financing'] ?? 0) + ($profitLossEntries['site_expenses'] ?? 0) }};
                new ApexCharts(document.querySelector("#profitLossMixChart"), {
                    series: [revenue, cost],
                    labels: ['Revenue', 'Expenses'],
                    chart: { type: 'donut', height: 180 },
                    colors: ['#10b981', '#f43f5e'],
                    legend: { position: 'bottom' }
                }).render();
            }
            @endif

            // 15. BALANCE SHEET
            @if($activeTab === 'balance_sheet')
            if (this.activeTab === 'balance_sheet') {
                const assetsSum = {{ $balanceSheetEntries['assets']['total'] ?? 0 }};
                const liabilitiesSum = {{ $balanceSheetEntries['liabilities_and_equity']['total'] ?? 0 }};
                new ApexCharts(document.querySelector("#balanceSheetRatioChart"), {
                    series: [{
                        name: 'Value',
                        data: [assetsSum, liabilitiesSum]
                    }],
                    chart: { type: 'bar', height: 140, toolbar: { show: false } },
                    colors: ['#10b981', '#f43f5e'],
                    plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
                    xaxis: { categories: ['Total Assets', 'Total Liabilities & Equity'] }
                }).render();
            }
            @endif
        }
    };
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-\[1800px\], .max-w-\[1800px\] * {
        visibility: visible;
    }
    .max-w-\[1800px\] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    button, input, select, a, .print-hidden, .print\:hidden, [class*="print-hidden"] {
        display: none !important;
    }
}
</style>
