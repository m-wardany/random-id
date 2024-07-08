<?php

namespace MWardany\HashIds\Interfaces;

interface HasHashId
{
    /**
     * the key that will be used for the encryption
     *
     * @return string
     */
    function getEncryptionKey(): string;

    /**
     * Return a list of HashBuilder objects 
     * [
     *  'id' => HashBuilder::text()->perfix('PRC-')->length(5),
     *  'other_attribute' => HashBuilder::int()
     * ]
     * @return \MWardany\HashIds\Helpers\HashBuilder
     */
    function getHashAttributes(): array;

    /**
     * Allow save hashed attributes after creating a new model
     *
     * @return boolean
     */
    function allowHashingAfterInsert(): bool;

    /**
     * Allow updating the hashed attributes after updating the model
     *
     * @return boolean
     */
    function allowHashingAfterUpdate(): bool;

    /**
     * whether to save the hashed key sync or async
     *
     * @return boolean
     */
    function saveHashAsynchronously(): bool;
}
