<?php

namespace MWardany\HashIds\Services;

use Illuminate\Database\Eloquent\Model;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\HasHashId;
use Symfony\Component\Routing\Exception\NoConfigurationException;

final class HashAttributeService
{
    function __construct(private HasHashId|Model $model)
    {
        if ($model->getEncryptionKey() === null) {
            throw new NoConfigurationException("Encryption key is required", 1);
        }
    }
    function execute(): void
    {
        foreach ($this->model->getHashAttributes() as $attribute => $props) {
            if (!($props instanceof HashBuilder)) {
                throw new NoConfigurationException("Attribute value should be instance of HashBuilder class", 1);
            }
            $value = $this->model->$attribute;
            $newAttribute = $props->getHashedAttribute();
            $length = $props->getLength();
            $alphabet = $props->getCharacters();
            $prefix = $props->getPrefix();
            $sufffix = $props->getSuffix();
            $ffx = new \Katoni\FFX\Codecs\Text($this->model->getEncryptionKey(), $alphabet, $length);
            $this->model->$newAttribute = sprintf('%%%', $prefix, $ffx->encrypt(str_pad((string) $value, $length, '0', STR_PAD_LEFT)), $sufffix);
        }
        $this->model->saveQuietly();
    }
}
