<?php

namespace MWardany\HashIds\Services;

use Katoni\FFX\Codecs\Codec;
use Katoni\FFX\Codecs\Integer;
use Katoni\FFX\Codecs\Sequence;
use Katoni\FFX\Codecs\Text;
use MWardany\HashIds\Enums\TypeEnum;
use MWardany\HashIds\Helpers\HashBuilder;

final class HashService
{
    function __construct(private $text, private HashBuilder $props, private $encryptionKey = null)
    {
        if (empty($encryptionKey)) {
            $this->encryptionKey = config('hashid.encryption_key') ?? config('app.key');
        }
    }

    function execute(): string
    {
        $length = max(strlen((string)$this->text), (int) $this->props->getLength());
        $prefix = $this->props->getPrefix() ?? '';
        $sufffix = $this->props->getSuffix() ?? '';
        $ffx = $this->getFFX($length)->encrypt(str_pad((string) $this->text, $length, '0', STR_PAD_LEFT));
        return  sprintf('%s%s%s', $prefix, implode('', $ffx), $sufffix);
    }

    /**
     * 
     * @param int $length
     * @return Codec
     */
    function getFFX(int $length): Codec
    {
        switch ($this->props->getType()) {
            case TypeEnum::TYPE_MIX:
                return new Sequence($this->encryptionKey, config('hashid.mix_alphabet'), $length);
            case TypeEnum::TYPE_TEXT:
                return new Text($this->encryptionKey, config('hashid.text_alphabet'), $length);
            case TypeEnum::TYPE_INT:
                return new Integer($this->encryptionKey, config('hashid.int_alphabet'), $length);
        }
    }
}
