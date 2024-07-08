<?php

namespace MWardany\HashIds\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use MWardany\HashIds\Events\HasHashSaved;
use MWardany\HashIds\Services\HashAttributeService;

class HashAttributes implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  HasHashSaved  $event
     * @return void
     */
    public function handle(HasHashSaved $event)
    {
        if ($event->model->allowHashingAfterInsert() ||  $event->model->allowHashingAfterUpdate()) {
            $service = new HashAttributeService($event->model);
        }
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(HasHashSaved $event): bool
    {
        return $event->model->saveHashAsynchronously();
    }
}
