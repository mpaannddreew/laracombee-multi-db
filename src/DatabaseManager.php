<?php
/**
 * Created by PhpStorm.
 * User: Mpande Andrew
 * Date: 08/05/2019
 * Time: 11:49
 */

namespace FannyPack\LaracombeeMultiDB;


use Illuminate\Support\Manager;

class DatabaseManager extends Manager
{
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * DatabaseManager constructor.
     * @param \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['laracombee-multi-db.default'];
    }

    /**
     * Create new db access
     *
     * @param null $db
     * @return Laracombee
     */
    public function db($db = null) {
        return $this->driver($db);
    }

    /**
     * Create access to default db
     *
     * @return Laracombee
     */
    public function createDefaultDriver() {
        return $this->createInstance($this->getDefaultDriver());
    }

    /**
     * Call a custom driver creator.
     *
     * @param  string  $driver
     * @return mixed
     */
    protected function callCustomCreator($driver)
    {
        $config = $this->getConfig($driver);

        return $this->customCreators[$driver]($config);
    }

    /**
     * Get the payment method configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["laracombee-multi-db.databases.{$name}"];
    }

    protected function createInstance($name) {
        $config = $this->getConfig($name);

        return new Laracombee(
            $config["database"], $config["token"], $config["protocol"], $config["timeout"]
        );
    }
}