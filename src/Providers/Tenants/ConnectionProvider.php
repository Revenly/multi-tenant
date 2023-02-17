<?php

/*
 * This file is part of the hyn/multi-tenant package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/hyn/multi-tenant
 */

namespace Hyn\Tenancy\Providers\Tenants;

use Hyn\Tenancy\Database\Connection;
use Hyn\Tenancy\Database\Console;
use Hyn\Tenancy\Database\Resolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class ConnectionProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Connection::class);
        $this->registerMigrationCommands();

        $this->overrideConnectionResolvers();
    }

    /**
     * Register the "migrate" migration command.
     *
     * @return void
     */
    protected function registerMigrationCommands()
    {
        $this->app->singleton(Console\Migrations\FreshCommand::class, fn(Application $app) => new Console\Migrations\FreshCommand(
            $app->make('migrator'),
            $app->make("events")
        ));
        $this->app->singleton(Console\Migrations\MigrateCommand::class, fn(Application $app) => new Console\Migrations\MigrateCommand(
            $app->make('migrator'),
            $app->make("events")
        ));
        $this->app->singleton(Console\Migrations\RollbackCommand::class, fn(Application $app) => new Console\Migrations\RollbackCommand(
            $app->make('migrator'),
            $app->make("events")
        ));
        $this->app->singleton(Console\Migrations\ResetCommand::class, fn(Application $app) => new Console\Migrations\ResetCommand(
            $app->make('migrator'),
            $app->make("events")
        ));
        $this->app->singleton(Console\Migrations\RefreshCommand::class, fn(Application $app) => new Console\Migrations\RefreshCommand(
            $app->make('migrator'),
            $app->make("events")
        ));
        $this->app->singleton(Console\Seeds\SeedCommand::class, fn(Application $app) => new Console\Seeds\SeedCommand($app['db']));

        $this->commands([
            Console\Migrations\FreshCommand::class,
            Console\Migrations\MigrateCommand::class,
            Console\Migrations\RollbackCommand::class,
            Console\Migrations\ResetCommand::class,
            Console\Migrations\RefreshCommand::class,
            Console\Seeds\SeedCommand::class
        ]);
    }

    public function overrideConnectionResolvers()
    {
        foreach (['system', 'tenant'] as $type) {
            $models = config("tenancy.db.force-$type-connection-of-models", []);

            if (is_countable($models) ? count($models) : 0) {
                $resolver = new Resolver(
                    $this->app->make(Connection::class)->{$type . 'Name'}(),
                    $this->app['db']
                );

                foreach ($models as $class) {
                    if (class_exists($class)) {
                        forward_static_call([$class, 'setConnectionResolver'], $resolver);
                    }
                }
            }
        }
    }
}
