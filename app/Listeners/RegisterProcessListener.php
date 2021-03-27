<?php

namespace App\Listeners;

use App\Events\RegisterProcess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterProcessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RegisterProcess  $event
     * @return void
     */
    public function handle(RegisterProcess $event)
    {
        echo route('setAction','home');
    }
}
