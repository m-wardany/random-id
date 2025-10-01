<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests\Unit;

use InvalidArgumentException;
use MWardany\HashIds\Helpers\HashBuilder;
use MWardany\HashIds\Services\HashAttributeService;
use MWardany\HashIds\Tests\Models\TestPost;
use MWardany\HashIds\Tests\TestCase;

class HashAttributeServiceTest extends TestCase
{
    /** @test */
    public function it_hashes_single_attribute(): void
    {
        $post = TestPost::create(['title' => 'Test Post']);

        $this->assertNotNull($post->post_key);
        $this->assertStringStartsWith('PK-', $post->post_key);
        $this->assertGreaterThanOrEqual(8, strlen($post->post_key)); // PK- + 5 chars
    }

     /** @test */
    public function it_handles_array_of_hash_builders(): void
    {
        $post = new class extends TestPost {
            public function getHashAttributes(): array
            {
                return [
                    'id' => [
                        HashBuilder::mixed('first_hashing')
                            ->minLength(5)
                            ->prefix('FH-'),
                        HashBuilder::mixed('second_hashing')
                            ->minLength(5)
                            ->prefix('SH-'),
                    ],
                ];
            }
        };

        $post->title = 'Test';
        $post->save();

        $this->assertStringStartsWith('FH-', $post->first_hashing);
        $this->assertStringStartsWith('SH-', $post->second_hashing);
    }

}