<?php

declare(strict_types=1);

namespace MWardany\HashIds\Traits;

use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Services\HashAttributeService;

trait HasHashId
{
    /**
     * Get the encryption key used for hashing.
     *
     * @return string|null
     */
    public function getEncryptionKey(): ?string
    {
        return config('hashid.encryption_key');
    }

    /**
     * Define which attributes should be hashed and their configurations.
     *
     * Example:
     * ```php
     * return [
     *     'id' => HashBuilder::mixed('id_hashed')
     *         ->minLength(5)
     *         ->prefix('ID-'),
     *     'ref' => [
     *         HashBuilder::text('primary_ref')->minLength(8),
     *         HashBuilder::int('secondary_ref')->minLength(6),
     *     ],
     * ];
     * ```
     *
     * @return array<string, HashBuilder|HashBuilder[]>
     */
    public function getHashAttributes(): array
    {
        $pattern = config('hashid.hashed_attributed_pattern', '%s_hashed');
        
        return [
            'id' => HashBuilder::mixed(sprintf($pattern, 'id'))
                ->minLength(5)
                ->prefix('ID-'),
        ];
    }

    /**
     * Determine if hashing should occur after a model is created.
     *
     * @return bool
     */
    public function allowHashingAfterInsert(): bool
    {
        return config('hashid.allow_hashing_after_insert', true);
    }

    /**
     * Determine if hashing should occur after a model is updated.
     *
     * @return bool
     */
    public function allowHashingAfterUpdate(): bool
    {
        return config('hashid.allow_hashing_after_update', false);
    }

    /**
     * Boot the HasHashId trait for the model.
     *
     * Registers model event listeners to automatically hash attributes
     * when the model is saved.
     *
     * @return void
     */
    public static function bootHasHashId(): void
    {
        static::created(function ($model): void {
            if ($model->allowHashingAfterInsert()) {
                $model->processHashAttributes();
            }
        });

        static::updated(function ($model): void {
            if ($model->allowHashingAfterUpdate()) {
                $model->processHashAttributes();
            }
        });
    }

    /**
     * Process and hash the configured attributes.
     *
     * @return void
     */
    protected function processHashAttributes(): void
    {
        $service = new HashAttributeService($this);
        $service->execute();
    }
}