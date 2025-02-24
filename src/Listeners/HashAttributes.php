<?php

namespace MWardany\HashIds\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use MWardany\HashIds\Events\HasHashSaved;
use MWardany\HashIds\Services\HashAttributeService;

class HashAttributes implements ShouldQueue
{
    use Dispatchable;

    /**
     * Handle the event.
     *
     * @param  HasHashSaved  $event
     * @return void
     */
    public function handle(HasHashSaved $event)
    {
        $service = new HashAttributeService($event->model);
        $service->execute();
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(HasHashSaved $event): bool
    {
        return $event->model->saveHashAsynchronously();
    }
}
