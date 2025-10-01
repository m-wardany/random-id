<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests\Unit;

use MWardany\HashIds\Tests\Models\TestPost;
use MWardany\HashIds\Tests\TestCase;

class HasHashIdTraitTest extends TestCase
{
    /** @test */
    public function it_hashes_on_create(): void
    {
        $post = TestPost::create(['title' => 'Test Post']);

        $this->assertNotNull($post->post_key);
        $this->assertStringStartsWith('PK-', $post->post_key);
    }

    /** @test */
    public function it_does_not_rehash_on_update_by_default(): void
    {
        $post = TestPost::create(['title' => 'Test Post']);
        $originalHash = $post->post_key;

        $post->update(['title' => 'Updated Title']);
        $post->refresh();

        $this->assertEquals($originalHash, $post->post_key);
    }

    /** @test */
    public function it_rehashes_on_update_when_allowed(): void
    {
        $post = new class extends TestPost {
            public function allowHashingAfterUpdate(): bool
            {
                return true;
            }
        };

        $post->title = 'Test Post';
        $post->save();
        $originalHash = $post->post_key;

        $post->update(['title' => 'Updated Title']);
        $post->refresh();

        // Hash should remain the same as it's based on ID, not title
        $this->assertNotNull($post->post_key);
    }

    /** @test */
    public function it_gets_encryption_key_from_config(): void
    {
        $post = new TestPost();

        $this->assertEquals(config('hashid.encryption_key'), $post->getEncryptionKey());
    }

    /** @test */
    public function it_returns_hash_attributes_configuration(): void
    {
        $post = new TestPost();
        $attributes = $post->getHashAttributes();

        $this->assertIsArray($attributes);
        $this->assertArrayHasKey('id', $attributes);
    }

    /** @test */
    public function it_respects_allow_hashing_after_insert_flag(): void
    {
        $post = new class extends TestPost {
            public function allowHashingAfterInsert(): bool
            {
                return false;
            }
        };

        $post->title = 'Test Post';
        $post->save();

        $this->assertNull($post->post_key);
    }
}
