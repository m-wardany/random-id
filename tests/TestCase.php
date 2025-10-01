<?php

declare(strict_types=1);

namespace MWardany\HashIds\Tests;

use Illuminate\Database\Schema\Blueprint;
use MWardany\HashIds\Providers\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Set up database
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up package config
        $app['config']->set('hashid.encryption_key', 'test-encryption-key');
        $app['config']->set('hashid.hashed_attributed_pattern', '%s_hashed');
        $app['config']->set('hashid.allow_hashing_after_insert', true);
        $app['config']->set('hashid.allow_hashing_after_update', false);
    }

    protected function setUpDatabase(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}