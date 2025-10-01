<?php

namespace MWardany\HashIds\Services;

use Illuminate\Database\Eloquent\Model;
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
            if (($props instanceof HashBuilder)) {
                $this->hash($this->model->$attribute, $props);
            }
            elseif(is_array($props)) {
                foreach ($props as $prop) {
                    if ($prop instanceof HashBuilder) {
                        $this->hash($this->model->$attribute, $prop);
                    } else {
                        throw new NoConfigurationException("Attribute value should be instance of HashBuilder class", 1);
                    }
                }
            }
            else {
                throw new NoConfigurationException("Attribute value should be instance of HashBuilder class", 1);
            }
        }

        $this->model->saveQuietly();
    }

    function hash($value, HashBuilder $props): void
    {
        $newAttribute = $props->getHashedAttribute();

        $hashService = new HashService($value, $props, $props->getEncryptionKey() ?? $this->model->getEncryptionKey());
        $this->model->$newAttribute = $hashService->execute();
    }
}
