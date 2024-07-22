# Hash Id

This package is designed to hash sequential keys so that they appear as random characters, making them difficult to predict. There is no requirement to check if the key already exists in the database before saving, as each key is guaranteed to be unique

## Installation

### Composer installation

`composer require m-wardany/hash-ids`

### Publish Config

`php artisan vendor:publish --provider="MWardany\HashIds\Providers\ServiceProvider"`

## Usage

```

use Illuminate\Database\Eloquent\Model;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\Hashable;
use MWardany\HashIds\Traits\HasHashId as TraitsHasHashId;

class Post extends Model implements Hashable
{
    use TraitsHasHashId;

    protected $fillable = [
        'title',
    ];

    function getHashAttributes(): array
    {
        $pattern = config('hashid.hashed_attributed_pattern', '%s_hashed');
        return [
            // return mixed alphabet encrypted key, also text & int available
            'id' => HashBuilder::mixed('post_key')
                ->minLength(5)
                ->prefix('PK-')
                ->suffix(function (self $model) {
                    return $model->owner->isActive ? '-KD' : '-KP';
                })
        ];
    }
}
```
