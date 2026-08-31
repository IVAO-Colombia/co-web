<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EventStatus;
use App\Models\Event;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

#[Signature('events:finalize-ended')]
#[Description('Marks active events that have already ended as finalized, including recurring templates whose last occurrence has ended.')]
class FinalizeEndedEvents extends Command
{
    public function handle(): int
    {
        $this->finalizeEndedEvents();
        $this->finalizeCompletedRecurringTemplates();

        return self::SUCCESS;
    }

    /**
     * Finalize active, non-recurring events (including generated occurrences)
     * whose end date has passed.
     */
    private function finalizeEndedEvents(): void
    {
        $count = Event::query()
            ->where('status', EventStatus::ACTIVE)
            ->ended()
            ->update(['status' => EventStatus::FINALIZED]);

        $this->info("Finalized {$count} ended event(s).");
    }

    /**
     * Finalize active recurring templates once every one of their occurrences
     * has finalized (or been cancelled).
     */
    private function finalizeCompletedRecurringTemplates(): void
    {
        $count = Event::query()
            ->where('status', EventStatus::ACTIVE)
            ->where('is_recurring', true)
            ->has('occurrences')
            ->whereDoesntHave('occurrences', fn (Builder $query) => $query->where('status', EventStatus::ACTIVE))
            ->update(['status' => EventStatus::FINALIZED]);

        $this->info("Finalized {$count} completed recurring template(s).");
    }
}
