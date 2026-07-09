<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class PartnerController extends Controller
{
    /**
     * Get mock partners data.
     */
    private function getMockPartners(): Collection
    {
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Koval Ahmed Haji',
                'linkedAccount' => (object)['code' => 'PRT-KAH01'],
                'total_allocated' => 5400000.00,
                'total_paid' => 3200000.00,
                'balance' => 2200000.00,
                'partnerShares' => collect([
                    (object)[
                        'project' => (object)['name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd'],
                        'share_pct' => 60.0
                    ]
                ])
            ],
            (object)[
                'id' => 2,
                'name' => 'Vijayan',
                'linkedAccount' => (object)['code' => 'PRT-VIJ02'],
                'total_allocated' => 3600000.00,
                'total_paid' => 1500000.00,
                'balance' => 2100000.00,
                'partnerShares' => collect([
                    (object)[
                        'project' => (object)['name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd'],
                        'share_pct' => 40.0
                    ]
                ])
            ]
        ]);
    }

    /**
     * Get mock active projects data.
     */
    private function getMockProjects(): Collection
    {
        return collect([
            (object)[
                'id' => 1,
                'name' => 'Tabasco Hindustan Infra Developers Pvt.Ltd',
                'partnerShares' => collect([
                    (object)['partner' => (object)['name' => 'Koval Ahmed Haji'], 'share_pct' => 60.0],
                    (object)['partner' => (object)['name' => 'Vijayan'], 'share_pct' => 40.0],
                ])
            ],
        
        ]);
    }

    public function index(): View
    {
        $partners = $this->getMockPartners();
        $projects = $this->getMockProjects();

        return view('partners.index', compact('partners', 'projects'));
    }

    public function storePartner(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return redirect()->route('partners.index')
            ->with('status', 'Mock Partner profile successfully registered with a simulated current account.');
    }

    public function shares($projectId): View
    {
        $projects = $this->getMockProjects();
        $project = $projects->firstWhere('id', (int)$projectId) ?? $projects->first();
        
        $partners = $this->getMockPartners();
        
        $existingShares = collect([
            1 => (object)['share_pct' => $project->id === 1 ? 60.0 : 50.0],
            2 => (object)['share_pct' => $project->id === 1 ? 40.0 : 50.0],
        ]);

        return view('partners.shares', compact('project', 'partners', 'existingShares'));
    }

    public function updateShares(Request $request, $projectId): RedirectResponse
    {
        $request->validate([
            'shares' => ['required', 'array'],
        ]);

        return redirect()->route('partners.index')
            ->with('status', 'Mock partner share percentages updated successfully.');
    }

    public function statement(Request $request, $partnerId): View
    {
        $partners = $this->getMockPartners();
        $partner = $partners->firstWhere('id', (int)$partnerId) ?? $partners->first();

        // Default to project ID '1' (Tabasco Hindustan Infra Developers Pvt.Ltd) when no filter is requested
        $projectId = $request->has('project_id') ? $request->input('project_id') : '1';
        $projects = $this->getMockProjects();

        // Prefilled mock ledger entries
        $ledger = collect();

        // If "All Projects Combined" is selected ($projectId === '')
        if ($projectId === null || $projectId === '') {
            // Return combined ledger of all projects
            if ($partner->id === 1) {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-01 10:00 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88021 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit D66)',
                        'credit' => 3000000.00,
                        'debit' => 0.00,
                        'balance' => 3000000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-05 09:30 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-99012 (Tabasco Heights (Tower B), Unit H101)',
                        'credit' => 500000.00,
                        'debit' => 0.00,
                        'balance' => 3500000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-10 02:30 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Bank Transfer Ref: TXN-98214)',
                        'credit' => 0.00,
                        'debit' => 1200000.00,
                        'balance' => 2300000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-15 11:15 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88045 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit B2)',
                        'credit' => 2400000.00,
                        'debit' => 0.00,
                        'balance' => 4700000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-07-01 04:45 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Chq Ref: CHQ-55410)',
                        'credit' => 0.00,
                        'debit' => 2000000.00,
                        'balance' => 2700000.00,
                    ],
                ]);
            } else {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-01 10:00 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88021 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit D66)',
                        'credit' => 2000000.00,
                        'debit' => 0.00,
                        'balance' => 2000000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-05 09:30 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-99012 (Tabasco Heights (Tower B), Unit H101)',
                        'credit' => 500000.00,
                        'debit' => 0.00,
                        'balance' => 2500000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-10 03:00 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Bank Transfer Ref: TXN-98215)',
                        'credit' => 0.00,
                        'debit' => 800000.00,
                        'balance' => 1700000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-15 11:15 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88045 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit B2)',
                        'credit' => 1600000.00,
                        'debit' => 0.00,
                        'balance' => 3300000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-07-02 10:30 AM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Chq Ref: CHQ-55411)',
                        'credit' => 0.00,
                        'debit' => 700000.00,
                        'balance' => 2600000.00,
                    ],
                ]);
            }
        } else if ((string)$projectId === '1') {
            // Project 1: Tabasco Hindustan Infra Developers Pvt.Ltd
            if ($partner->id === 1) {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-01 10:00 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88021 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit D66)',
                        'credit' => 3000000.00,
                        'debit' => 0.00,
                        'balance' => 3000000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-10 02:30 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Bank Transfer Ref: TXN-98214)',
                        'credit' => 0.00,
                        'debit' => 1200000.00,
                        'balance' => 1800000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-15 11:15 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88045 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit B2)',
                        'credit' => 2400000.00,
                        'debit' => 0.00,
                        'balance' => 4200000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-07-01 04:45 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Chq Ref: CHQ-55410)',
                        'credit' => 0.00,
                        'debit' => 2000000.00,
                        'balance' => 2200000.00,
                    ],
                ]);
            } else {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-01 10:00 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88021 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit D66)',
                        'credit' => 2000000.00,
                        'debit' => 0.00,
                        'balance' => 2000000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-10 03:00 PM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Bank Transfer Ref: TXN-98215)',
                        'credit' => 0.00,
                        'debit' => 800000.00,
                        'balance' => 1200000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-15 11:15 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-88045 (Tabasco Hindustan Infra Developers Pvt.Ltd, Unit B2)',
                        'credit' => 1600000.00,
                        'debit' => 0.00,
                        'balance' => 2800000.00,
                    ],
                    [
                        'date' => \Carbon\Carbon::parse('2026-07-02 10:30 AM'),
                        'type' => 'Payout',
                        'description' => 'Direct Payout processed (Chq Ref: CHQ-55411)',
                        'credit' => 0.00,
                        'debit' => 700000.00,
                        'balance' => 2100000.00,
                    ],
                ]);
            }
        } else if ((string)$projectId === '2') {
            // Project 2: Tabasco Heights
            if ($partner->id === 1) {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-05 09:30 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-99012 (Tabasco Heights (Tower B), Unit H101)',
                        'credit' => 500000.00,
                        'debit' => 0.00,
                        'balance' => 500000.00,
                    ]
                ]);
            } else {
                $ledger = collect([
                    [
                        'date' => \Carbon\Carbon::parse('2026-06-05 09:30 AM'),
                        'type' => 'Collection Share',
                        'description' => 'Collection share from Booking REC-99012 (Tabasco Heights (Tower B), Unit H101)',
                        'credit' => 500000.00,
                        'debit' => 0.00,
                        'balance' => 500000.00,
                    ]
                ]);
            }
        }

        return view('partners.statement', compact('partner', 'ledger', 'projects', 'projectId'));
    }
}
