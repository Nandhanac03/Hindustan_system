<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomerInstallment;
use App\Services\CollectionReminderService;
use App\Services\CollectionAgeingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateCollectionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collection:generate-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send reminders for overdue collection installments';

    /**
     * Execute the console command.
     */
    public function handle(CollectionReminderService $reminderService, CollectionAgeingService $ageingService)
    {
        $this->info('Starting collection reminders generation...');

        $installments = CustomerInstallment::with(['sale.customer'])
            ->whereNotIn('status', ['paid'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', Carbon::today())
            ->whereRaw('amount > paid_amount')
            ->get();

        $count = 0;

        foreach ($installments as $installment) {
            try {
                $daysOverdue = $ageingService->calculateDaysOverdue(Carbon::parse($installment->due_date));
                
                if ($daysOverdue > 0) {
                    $level = $reminderService->determineReminderLevel($installment, $daysOverdue);
                    
                    if ($level) {
                        $reminderService->generateReminder($installment, $level);
                        $this->info("Generated {$level} for Installment ID: {$installment->id}");
                        $count++;
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error generating reminder for installment {$installment->id}: " . $e->getMessage());
                $this->error("Failed to generate reminder for installment {$installment->id}");
            }
        }

        $this->info("Completed. {$count} reminders generated/sent.");
    }
}
