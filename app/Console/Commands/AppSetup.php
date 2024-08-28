<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Elastic\Services\ElasticService;

class AppSetup extends Command
{
    protected $elasticSetupService;
    public function __construct(ElasticService $elasticSetupService)
    {
        parent::__construct();
        $this->elasticSetupService = $elasticSetupService;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up project';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->elasticSetupService->syncDatabaseToElasticsearch();
        $this->info('Project set up successfully');
    }
}
