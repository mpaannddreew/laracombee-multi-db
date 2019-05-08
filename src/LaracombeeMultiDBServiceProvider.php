<?php
/**
 * Created by PhpStorm.
 * User: Mpande Andrew
 * Date: 08/05/2019
 * Time: 11:46
 */

namespace FannyPack\LaracombeeMultiDB;


use FannyPack\LaracombeeMultiDB\Console\Commands\AddColumnsCommand;
use FannyPack\LaracombeeMultiDB\Console\Commands\DropColumnsCommand;
use FannyPack\LaracombeeMultiDB\Console\Commands\MigrateCommand;
use FannyPack\LaracombeeMultiDB\Console\Commands\ResetDatabaseCommand;
use FannyPack\LaracombeeMultiDB\Console\Commands\RollbackCommand;
use FannyPack\LaracombeeMultiDB\Console\Commands\SeedCommand;
use Illuminate\Support\ServiceProvider;

class LaracombeeMultiDBServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laracombee-multi-db', function () {
            return new DatabaseManager($this->app);
        });

        collect($this->app['config']['laracombee-multi-db.databases'])->reject('default')->each(function ($value, $key) {
            MultiDBFacade::extend($key, function ($config) {
                return new Laracombee(
                    $config["database"], $config["token"], $config["protocol"], $config["timeout"]
                );
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laracombee-multi-db.php' => config_path('laracombee-multi-db.php'),
        ]);

        $this->commands(
            [
                SeedCommand::class,
                MigrateCommand::class,
                RollbackCommand::class,
                AddColumnsCommand::class,
                DropColumnsCommand::class,
                ResetDatabaseCommand::class,
            ]
        );
    }
}