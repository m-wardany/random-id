<?php

namespace MWardany\HashIds\Services;

use Illuminate\Database\Eloquent\Model;
use MWardany\HashIds\Codecs\Text;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\Hashable;
use Symfony\Component\Routing\Exception\NoConfigurationException;

final class HashAttributeService
{
    function __construct(private Hashable|Model $model)
    {
    }

    function execute(): void
    {
        foreach ($this->model->getHashAttributes() as $attribute => $props) {
            if (!($props instanceof HashBuilder)) {
                throw new NoConfigurationException("Attribute value should be instance of HashBuilder class", 1);
            }
            $value = $this->model->$attribute;
            $newAttribute = $props->getHashedAttribute();

            $hashService = new HashService($value, $props, $this->model->getEncryptionKey());
            $this->model->$newAttribute = $hashService->execute();
        }
        $this->model->saveQuietly();
    }
}
