<?php

namespace FannyPack\LaracombeeMultiDB\Console\Commands;


use FannyPack\LaracombeeMultiDB\Console\LaracombeeCommand;

class SeedCommand extends LaracombeeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laracombee:seed
                            {type : Catalog type (user or item)}
                            {--chunk= : total chunk}
                            {--db=default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed records into recombee db';

    /**
     * The default chunk value.
     *
     * @var int
     */
    protected static $chunk = 100;

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
        $chunk = (int) $this->option('chunk') ?: self::$chunk;

        $class = config("laracombee-multi-db.databases.{$this->db()}.parties.{$this->argument('type')}");

        $records = $class::all();

        $total = $records->count();

        $bar = $this->output->createProgressBar($total / $chunk);

        $records->chunk($chunk)->each(function ($users) use ($bar) {
            $batch = $this->{'add'.ucfirst($this->argument('type')).'s'}($users->all());
            MultiDBFacade::db($this->db())->batch($batch)->then(function ($response) use ($bar) {
            })->otherwise(function ($error) {
                $this->info('');
                $this->error($error);
                die();
            })->wait();

            $bar->advance();
        });

        $bar->finish();

        $this->info('');
        $this->info('Done!');
    }
}
