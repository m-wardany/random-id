<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\Hashable;
use MWardany\HashIds\Traits\HasHashId;

class TestPost extends Model
{
    use HasHashId;

    protected $table = 'test_posts';

    protected $fillable = ['title'];


    public function getHashAttributes(): array
    {
        return [
            'id' => HashBuilder::mixed('post_key')
                ->minLength(5)
                ->prefix('PK-'),
        ];
    }
}