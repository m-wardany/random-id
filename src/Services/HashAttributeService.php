<?php

declare(strict_types=1);

namespace MWardany\HashIds\Services;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Interfaces\Hashable;

final class HashAttributeService
{
    public function __construct(
        private readonly Hashable|Model $model
    ) {
    }

    public function execute(): void
    {
        $hashAttributes = $this->model->getHashAttributes();

        foreach ($hashAttributes as $attribute => $configuration) {
            $this->processAttribute($attribute, $configuration);
        }

        $this->model->saveQuietly();
    }

    private function processAttribute(string $attribute, mixed $configuration): void
    {
        if ($configuration instanceof HashBuilder) {
            $this->hashAttribute($attribute, $configuration);
            return;
        }

        if (is_array($configuration)) {
            $this->processArrayConfiguration($attribute, $configuration);
            return;
        }

        throw new InvalidArgumentException(
            'Hash configuration must be an instance of HashBuilder or an array of HashBuilder instances.'
        );
    }

    private function processArrayConfiguration(string $attribute, array $configurations): void
    {
        foreach ($configurations as $configuration) {
            if (!$configuration instanceof HashBuilder) {
                throw new InvalidArgumentException(
                    'All array elements must be instances of HashBuilder.'
                );
            }
            $this->hashAttribute($attribute, $configuration);
        }
    }

    private function hashAttribute(string $attribute, HashBuilder $builder): void
    {
        $value = $this->model->$attribute;
        $hashedAttributeName = $builder->getHashedAttribute();
        $encryptionKey = $this->resolveEncryptionKey($builder);

        $hashService = new HashService($value, $builder, $encryptionKey);
        $this->model->$hashedAttributeName = $hashService->execute();
    }

    private function resolveEncryptionKey(HashBuilder $builder): string
    {
        return $builder->getEncryptionKey() ?? $this->model->getEncryptionKey();
    }
}