<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests\Feature;

use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Tests\Models\TestPost;
use MWardany\HashIds\Tests\TestCase;

class HashingIntegrationTest extends TestCase
{
    /** @test */
    public function it_generates_unique_hashes_for_different_records(): void
    {
        $post1 = TestPost::create(['title' => 'Post 1']);
        $post2 = TestPost::create(['title' => 'Post 2']);

        $this->assertNotEquals($post1->post_key, $post2->post_key);
        $this->assertStringStartsWith('PK-', $post1->post_key);
        $this->assertStringStartsWith('PK-', $post2->post_key);
    }

    /** @test */
    public function it_generates_consistent_hash_for_same_id(): void
    {
        $post = TestPost::create(['title' => 'Test Post']);
        $hash1 = $post->post_key;

        // Force re-hash
        $post->processHashAttributes();
        $hash2 = $post->post_key;

        $this->assertEquals($hash1, $hash2);
    }

    /** @test */
    public function it_handles_complex_hash_configuration(): void
    {
        $post = new class extends TestPost {
            public function getHashAttributes(): array
            {
                return [
                    'id' => [
                        HashBuilder::mixed('first_hashing')
                            ->minLength(8)
                            ->prefix('F-')
                            ->encryptionKey('8Gz3WizqZWvAx6XnJJPWtNuGMr2D7BVz')
                            ->suffix('-END'),
                        HashBuilder::text('second_hashing')
                            ->minLength(8)
                            ->prefix('S-')
                            ->encryptionKey('8Gz3WizqZWvAx6XnJJPWtNuGMr2D7BVz')
                            ->suffix('-END'),
                    ],
                ];
            }
        };

        $post->title = 'Complex Test';
        $post->save();


        $this->assertStringStartsWith('F-', $post->first_hashing);
        $this->assertStringEndsWith('-END', $post->first_hashing);
        $this->assertStringStartsWith('S-', $post->second_hashing);
        $this->assertGreaterThanOrEqual(9, strlen($post->first_hashing)); // F- + 8 + -END
    }
}