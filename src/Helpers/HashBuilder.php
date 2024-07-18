<?php

namespace MWardany\HashIds\Helpers;

use Closure;
use MWardany\HashIds\Enums\TypeEnum;

class HashBuilder
{

    private string $_characters;

    private int $_length = 5;

    private string $_prefix;

    private string $_suffix;


    private function __construct(private TypeEnum $_type, private string $_attribute)
    {
    }

    static function mixed(string $attribute): self
    {
        return new static(TypeEnum::TYPE_MIX, $attribute);
    }

    static function text(string $attribute): self
    {
        return new static(TypeEnum::TYPE_TEXT, $attribute);
    }

    static function int(string $attribute): self
    {
        return new static(TypeEnum::TYPE_INT, $attribute);
    }

    /**
     * length of hashed key without prefix and suffix
     * default = 5
     * @param integer $value
     * @return self
     */
    function minLength(int|Closure $value): self
    {
        if (is_callable($value)) {
            $this->_length = $value($this);
        } else {
            $this->_length = $value;
        }
        return $this;
    }

    /**
     * String to be added to the Top of the hashed text
     *
     * @param string $value
     * @return self
     */
    function prefix(string|Closure $value): self
    {
        if (is_callable($value)) {
            $this->_prefix = $value($this);
        } else {
            $this->_prefix = $value;
        }
        return $this;
    }

    /**
     * String to be added to the End of the hashed text
     *
     * @param [type] $value
     * @return self
     */
    function suffix(string|Closure $value): self
    {
        if (is_callable($value)) {
            $this->_suffix = $value($this);
        } else {
            $this->_suffix = $value;
        }
        return $this;
    }

    function getType()
    {
        return $this->_type;
    }

    function getHashedAttribute()
    {
        return $this->_attribute;
    }

    function getLength()
    {
        return $this->_length ?? config('hashid.min_length');
    }

    function getPrefix()
    {
        return $this->_prefix ?? '';
    }

    function getSuffix()
    {
        return $this->_suffix ?? '';
    }
}
