# Hash Id

A Laravel package designed to hash sequential keys into random-appearing characters, making them difficult to predict. Each hashed key is guaranteed to be unique without requiring database existence checks before saving.

## Features

- 🔐 **Secure hashing** - Transform sequential IDs into unpredictable hashes
- 🎯 **Multiple hash types** - Support for mixed, text, and integer alphabets
- 🔧 **Flexible configuration** - Customize prefixes, suffixes, and minimum lengths
- 🔑 **Custom encryption keys** - Use different encryption keys per attribute
- 📦 **Multiple hashes** - Generate multiple hash variants for a single attribute
- ✨ **Laravel integration** - Seamless integration with Eloquent models

## Installation

### Step 1: Install via Composer

```bash
composer require m-wardany/hash-ids
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="MWardany\HashIds\Providers\ServiceProvider"
```

This will create a `config/hashid.php` file where you can customize the default settings.

## Configuration

Update `config/hashid.php` to set your global defaults:

```php
return [
    // Default encryption key (can be overridden per attribute)
    'encryption_key' => env('HASHID_ENCRYPTION_KEY', env('APP_KEY')),
    
    // Pattern for hashed attribute names (e.g., 'id' becomes 'id_hashed')
    'hashed_attributed_pattern' => '%s_hashed',
    
    // Default minimum length for hashed values
    'min_length' => 5,
];
```

## Usage

### Basic Setup

To use hash IDs in your model, implement the `Hashable` interface and use the `HasHashId` trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\Hashable;
use MWardany\HashIds\Traits\HasHashId;

class Post extends Model implements Hashable
{
    use HasHashId;

    protected $fillable = ['title', 'ref'];

    public function getHashAttributes(): array
    {
        return [
            'id' => HashBuilder::mixed('post_key')
                ->minLength(5)
                ->prefix('PK-'),
        ];
    }
}
```

### Hash Builder Methods

The `HashBuilder` class provides three static methods for creating hash configurations:

#### 1. Mixed Alphabet (Alphanumeric)
```php
HashBuilder::mixed('hashed_attribute_name')
```
Generates hashes using both letters and numbers (e.g., `aB3xK9`)

#### 2. Text Only
```php
HashBuilder::text('hashed_attribute_name')
```
Generates hashes using only letters (e.g., `aBxKmP`)

#### 3. Integer Only
```php
HashBuilder::int('hashed_attribute_name')
```
Generates hashes using only numbers (e.g., `839201`)

### Customization Options

#### Minimum Length
```php
HashBuilder::mixed('post_key')->minLength(10)
```
Sets the minimum length of the generated hash.

#### Prefix
```php
HashBuilder::mixed('post_key')->prefix('POST-')
```
Adds a static prefix to the hash (e.g., `POST-aB3xK9`).

#### Suffix (Static)
```php
HashBuilder::mixed('post_key')->suffix('-ACTIVE')
```
Adds a static suffix to the hash (e.g., `aB3xK9-ACTIVE`).

#### Suffix (Dynamic)
```php
HashBuilder::mixed('post_key')->suffix(function (Post $model) {
    return $model->is_active ? '-ACTIVE' : '-INACTIVE';
})
```
Adds a dynamic suffix based on model data.

#### Custom Encryption Key
```php
HashBuilder::mixed('post_key')->encryptionKey('custom_secret_key')
```
Uses a custom encryption key instead of the global default.

### Advanced Examples

#### Single Hash with Dynamic Suffix
```php
public function getHashAttributes(): array
{
    return [
        'id' => HashBuilder::mixed('post_key')
            ->minLength(8)
            ->prefix('POST-')
            ->suffix(function (self $model) {
                return $model->owner->isActive ? '-ACTIVE' : '-INACTIVE';
            }),
    ];
}
```

#### Multiple Hashes for One Attribute
```php
public function getHashAttributes(): array
{
    return [
        'ref' => [
            HashBuilder::mixed('primary_hash')
                ->minLength(6)
                ->prefix('PRI-')
                ->encryptionKey('primary_key'),
            
            HashBuilder::text('secondary_hash')
                ->minLength(8)
                ->prefix('SEC-')
                ->encryptionKey('secondary_key'),
        ],
    ];
}
```

#### Complex Multi-Attribute Setup
```php
public function getHashAttributes(): array
{
    return [
        'id' => HashBuilder::mixed('public_id')
            ->minLength(10)
            ->prefix('POST-'),
        
        'user_id' => HashBuilder::int('user_ref')
            ->minLength(8)
            ->prefix('USER-'),
        
        'reference' => [
            HashBuilder::mixed('internal_ref')
                ->minLength(12)
                ->encryptionKey(config('app.internal_key')),
            
            HashBuilder::text('external_ref')
                ->minLength(10)
                ->prefix('EXT-')
                ->encryptionKey(config('app.external_key')),
        ],
    ];
}
```

## How It Works

1. **Implement Interface**: Add the `Hashable` interface to your model
2. **Use Trait**: Include the `HasHashId` trait
3. **Define Attributes**: Specify which attributes to hash in `getHashAttributes()`
4. **Automatic Hashing**: Hashes are generated automatically when the model is saved
5. **Access Hashed Values**: Access via the configured attribute name (e.g., `$post->post_key`)

## Database Schema

Ensure your database has columns for the hashed attributes:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('post_key')->nullable()->unique(); // Hashed ID column
    $table->timestamps();
});
```

## Best Practices

- ✅ Use descriptive names for hashed attributes (e.g., `public_id`, `share_token`)
- ✅ Add database indexes to hashed columns for better query performance
- ✅ Use different encryption keys for different security contexts
- ✅ Set appropriate minimum lengths based on your uniqueness requirements
- ✅ Add prefixes to make hash types easily identifiable
- ⚠️ Never expose the original sequential ID in public APIs

## Security Considerations

- The package uses secure hashing algorithms to prevent prediction attacks
- Each encryption key creates a unique hash set - keep your keys secure
- Hashed values are deterministic (same input + key = same output)
- Consider using different encryption keys for public vs internal hashes

## License

This package is open-source software licensed under the MIT license.