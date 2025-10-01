<?php

namespace MWardany\HashIds\Enums;

enum TypeEnum: string
{
    case TYPE_TEXT  = 'text';

    case TYPE_INT   = 'int';

    case TYPE_MIX   = 'mix';

    function alphabet() : string {
        return match ($this) {
            self::TYPE_TEXT => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            self::TYPE_INT => '0123456789',
            self::TYPE_MIX => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        };
    }
}
