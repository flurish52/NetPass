<?php

namespace App\Console\Commands;

use App\Services\SessionService;
use Illuminate\Console\Command;

class ExpireVoucherSessions extends Command
{
    protected $signature = 'sessions:expire';

    protected $description = 'Mark overdue voucher sessions and their vouchers as expired.';

    public function handle(SessionService $sessionService): int
    {
        $count = $sessionService->expireOverdueSessions();

        $this->info("Expired {$count} session(s).");

        return self::SUCCESS;
    }
}
