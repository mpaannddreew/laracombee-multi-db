<?php

namespace FannyPack\LaracombeeMultiDB\Console\Commands;


use FannyPack\LaracombeeMultiDB\Console\LaracombeeCommand;
use FannyPack\LaracombeeMultiDB\MultiDBFacade;

class MigrateCommand extends LaracombeeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laracombee:migrate
    						{type : Catalog type (user or item)}
    						{--db=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate to recombee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $scope = $this->prepareScope()->all();

        MultiDBFacade::db($this->db())->batch($scope)
            ->then(function ($response) {
                $this->info('Done!');
            })
            ->otherwise(function ($error) {
                $this->error($error);
            })
            ->wait();
    }

    /**
     * Prepare scope.
     *
     * @return mixed
     */
    public function prepareScope()
    {
        $class = config("laracombee-multi-db.databases.{$this->db()}.parties.{$this->argument('type')}");

        $properties = $class::$laracombee;

        return collect($properties)->map(function (string $type, string $property) {
            return $this->{'add'.ucfirst($this->argument('type')).'Property'}($property, $type);
        });
    }
}
