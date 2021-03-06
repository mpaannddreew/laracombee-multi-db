<?php

namespace FannyPack\LaracombeeMultiDB\Console\Commands;


use FannyPack\LaracombeeMultiDB\Console\LaracombeeCommand;
use FannyPack\LaracombeeMultiDB\MultiDBFacade;

class DropColumnsCommand extends LaracombeeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laracombee:drop
                            {columns* : Columns}
                            {--from= : table}
                            {--db=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop columns form recombee db';

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
     * @return mixed
     */
    public function handle()
    {
        if (!$this->option('from')) {
            $this->error('--from option is required!');
            die();
        }

        MultiDBFacade::db($this->db())->batch($this->loadColumns($this->argument('columns'))->all())
            ->then(function ($response) {
                $this->info('Done!');
            })
            ->otherwise(function ($error) {
                $this->error($error);
            })
            ->wait();
    }

    /**
     * Load columns.
     *
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection
     */
    public function loadColumns(array $columns)
    {
        return collect($columns)->map(function (string $column) {
            return $this->{'delete'.ucfirst($this->option('from')).'Property'}($column);
        });
    }
}
