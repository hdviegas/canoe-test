<?php

namespace App\Events;

use App\Models\Fund;

class DuplicatedFundWarning extends Event
{
    public function __construct(
        public Fund $fund
    ) {}
}
