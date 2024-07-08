<?php

namespace MWardany\HashIds\Events;

use Illuminate\Queue\SerializesModels;
use MWardany\HashIds\Interfaces\HasHashId;

class HasHashSaved
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param $model
     * @return void
     */
    public function __construct(public HasHashId $model)
    {
    }
}
