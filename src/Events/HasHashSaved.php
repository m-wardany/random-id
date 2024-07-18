<?php

namespace MWardany\HashIds\Events;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use MWardany\HashIds\Interfaces\Hashable;

class HasHashSaved
{
    use SerializesModels, Dispatchable;

    /**
     * Create a new event instance.
     *
     * @param $model
     * @return void
     */
    public function __construct(public Hashable $model)
    {
    }
}
