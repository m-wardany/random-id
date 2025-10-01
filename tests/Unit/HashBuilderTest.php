<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests\Unit;

use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Tests\TestCase;

class HashBuilderTest extends TestCase
{
    /** @test */
    public function it_creates_mixed_hash_builder(): void
    {
        $builder = HashBuilder::mixed('hashed_attr');

        $this->assertInstanceOf(HashBuilder::class, $builder);
        $this->assertEquals('hashed_attr', $builder->getHashedAttribute());
    }

    /** @test */
    public function it_creates_text_hash_builder(): void
    {
        $builder = HashBuilder::text('hashed_attr');

        $this->assertInstanceOf(HashBuilder::class, $builder);
    }

    /** @test */
    public function it_creates_int_hash_builder(): void
    {
        $builder = HashBuilder::int('hashed_attr');

        $this->assertInstanceOf(HashBuilder::class, $builder);
    }

    /** @test */
    public function it_sets_minimum_length(): void
    {
        $builder = HashBuilder::mixed('hashed_attr')->minLength(10);

        $this->assertEquals(10, $builder->getLength());
    }

    /** @test */
    public function it_sets_prefix(): void
    {
        $builder = HashBuilder::mixed('hashed_attr')->prefix('PRE-');

        $this->assertEquals('PRE-', $builder->getPrefix());
    }

    /** @test */
    public function it_sets_static_suffix(): void
    {
        $builder = HashBuilder::mixed('hashed_attr')->suffix('-SUF');

        $this->assertEquals('-SUF', $builder->getSuffix());
    }

    /** @test */
    public function it_sets_custom_encryption_key(): void
    {
        $builder = HashBuilder::mixed('hashed_attr')->encryptionKey('custom-key');

        $this->assertEquals('custom-key', $builder->getEncryptionKey());
    }
}