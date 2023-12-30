<?php

namespace App\Listeners;

use App\Events\DuplicatedFundWarning;

class DuplicatedFundWarningListener
{
    /**
     * Handle the duplicate fund warning events triggered.
     *
     * @param  \App\Events\DuplicatedFundWarning  $event
     * @return void
     */
    public function handle(DuplicatedFundWarning $event)
    {
        //Handle the event here
    }
}
