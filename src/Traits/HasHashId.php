<?php

namespace MWardany\HashIds\Traits;

use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Services\HashAttributeService;

trait HasHashId
{

    function getEncryptionKey(): ?string
    {
        return config('hashid.encryption_key');
    }
    /**
     * Return a list of HashBuilder objects 
     * [
     *  'id' => HashBuilder::text()->perfix('PRC-')->length(5),
     *  'other_attribute' => HashBuilder::int()
     * ]
     * @return \MWardany\HashIds\Helpers\HashBuilder[]
     */
    function getHashAttributes(): array
    {
        $pattern = config('hashid.hashed_attributed_pattern', '%s_hashed');
        return [
            'id' => HashBuilder::mixed(sprintf($pattern, 'id'))->minLength(5)->prefix('ID-')
        ];
    }

    /**
     * Allow save hashed attributes after creating a new model
     *
     * @return boolean
     */
    function allowHashingAfterInsert(): bool
    {
        return config('hashid.allow_hashing_after_insert', true);
    }

    /**
     * Allow updating the hashed attributes after updating the model
     *
     * @return boolean
     */
    function allowHashingAfterUpdate(): bool
    {
        return config('hashid.allow_hashing_after_update', true);
    }

    /**
     * whether to save the hashed key sync or async
     *
     * @return boolean
     */
    function saveHashAsynchronously(): bool
    {
        return config('hashid.queue.enabled', false);
    }

    /**
     * 
     * @return void
     */
    public static function bootHasHashId(): void
    {
        static::saved(function ($model) {
            $service = new HashAttributeService($model);
            $service->execute();
        });
    }
}
