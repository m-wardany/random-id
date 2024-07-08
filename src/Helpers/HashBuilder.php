<?php

namespace MWardany\HashIds\Helpers;

use MWardany\HashIds\Enums\TypeEnum;

final class HashBuilder
{

    private string $_characters;

    private int $_length = 5;

    private string $_prefix;

    private string $_suffix;


    private function __construct(private TypeEnum $_type, private string $_attribute)
    {
    }

    static function mixed($attribute): self
    {
        return new static(TypeEnum::TYPE_MIX, $attribute);
    }

    static function text($attribute): self
    {
        return new static(TypeEnum::TYPE_TEXT, $attribute);
    }

    static function int($attribute): self
    {
        return new static(TypeEnum::TYPE_INT, $attribute);
    }

    /**
     * Character to be used in the output hashed string
     *
     * @param string $value
     * @return self
     */
    function characters(string $value): self
    {
        $this->_characters = $value;
        return $this;
    }

    /**
     * length of hashed key without prefix and suffix
     * default = 5
     * @param integer $value
     * @return self
     */
    function length(int $value): self
    {
        $this->_length = $value;
        return $this;
    }

    /**
     * String to be added to the Top of the hashed text
     *
     * @param string $value
     * @return self
     */
    function prefix(string $value): self
    {
        $this->_prefix = $value;
        return $this;
    }

    /**
     * String to be added to the End of the hashed text
     *
     * @param [type] $value
     * @return self
     */
    function suffix($value): self
    {
        $this->_suffix = $value;
        return $this;
    }

    protected function getType()
    {
        return $this->_type;
    }

    protected function getHashedAttribute()
    {
        return $this->_attribute;
    }

    protected function getCharacters()
    {
        if ($this->_characters !== null) {
            $this->_characters;
        }
        switch ($this->_type) {
            case TypeEnum::TYPE_MIX:
                return 'abcdefghijklmnopqrstuvwxyz0123456789';
            case TypeEnum::TYPE_TEXT:
                return 'abcdefghijklmnopqrstuvwxyz';
            case TypeEnum::TYPE_INT:
                return '0123456789';
            default:
                return 'abcdefghijklmnopqrstuvwxyz0123456789';
        }
    }

    protected function getLength()
    {
        return $this->_length ?? 5;
    }

    protected function getPrefix()
    {
        return $this->_prefix ?? '';
    }

    protected function getSuffix()
    {
        return $this->_suffix ?? '';
    }
}
