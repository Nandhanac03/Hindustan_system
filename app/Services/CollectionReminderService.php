<?php

namespace App\Services;

use App\Models\CustomerInstallment;
use App\Models\CollectionReminder;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CollectionReminderService
{
    /**
     * Determine the required reminder level for an installment.
     * Returns null if no reminder is needed.
     */
    public function determineReminderLevel(CustomerInstallment $installment, int $daysOverdue): ?string
    {
        if ($daysOverdue <= 0) {
            return null;
        }

        $thresholds = config('collection.reminders', [
            'First Reminder' => 5,
            'Second Reminder' => 15,
            'Third Reminder' => 30,
            'Final Notice' => 45,
            'Collection Escalation' => 60,
            'Critical Recovery' => 90,
        ]);

        // Sort by highest threshold first
        arsort($thresholds);

        $targetLevel = null;
        foreach ($thresholds as $level => $thresholdDays) {
            if ($daysOverdue >= $thresholdDays) {
                $targetLevel = $level;
                break;
            }
        }

        if (!$targetLevel) {
            return null;
        }

        // Check if this level was already sent for this installment
        $alreadySent = CollectionReminder::where('installment_id', $installment->id)
            ->where('reminder_level', $targetLevel)
            ->exists();

        if ($alreadySent) {
            return null; // Already reminded at this level
        }

        return $targetLevel;
    }

    /**
     * Create a new reminder record and attempt to send.
     */
    public function generateReminder(CustomerInstallment $installment, string $level, string $channel = 'Email')
    {
        $sale = $installment->sale;
        $customer = $sale->customer;

        $reminder = CollectionReminder::create([
            'customer_id' => $customer->id,
            'sale_id' => $sale->id,
            'installment_id' => $installment->id,
            'reminder_level' => $level,
            'channel' => $channel,
            'scheduled_at' => Carbon::now(),
            'status' => 'pending',
            'message' => "Payment reminder for installment {$installment->installment_no}. Overdue amount: " . number_format($installment->amount - $installment->paid_amount, 2),
        ]);

        $this->sendReminder($reminder);

        return $reminder;
    }

    /**
     * Actually dispatch the reminder (mocked for SMS/WhatsApp if credentials missing).
     */
    public function sendReminder(CollectionReminder $reminder)
    {
        try {
            // Here you would integrate with actual SMS/WhatsApp/Email providers.
            // For now, we simulate success and log if channel is SMS/WhatsApp.

            if (in_array($reminder->channel, ['SMS', 'WhatsApp'])) {
                Log::info("Simulating {$reminder->channel} reminder ID {$reminder->id} to customer {$reminder->customer_id}");
                // Mock logic: if credentials missing, we could mark as 'failed' or 'queued'.
                // Assuming success for simulation
            } else {
                // Email logic here (e.g., Mail::to($reminder->customer->email)->send(...))
                Log::info("Simulating Email reminder ID {$reminder->id}");
            }

            $reminder->update([
                'status' => 'sent',
                'sent_at' => Carbon::now(),
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to send reminder {$reminder->id}: " . $e->getMessage());
            $reminder->update([
                'status' => 'failed',
                'response' => $e->getMessage()
            ]);
        }
    }
}
