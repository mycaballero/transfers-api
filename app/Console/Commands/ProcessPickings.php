<?php

namespace App\Console\Commands;

use App\Services\PickingService\PickingByProcessService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPickings extends Command
{

    /**
     * @param PickingByProcessService $pickingByProcessService
     */
    public function __construct(
        protected PickingByProcessService  $pickingByProcessService,
    )
    {
        Parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-pickings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command loop the pickings and process its moves';

    /**
     * @return void
     */
    public function handle(): void
    {
        Log::debug("Inicia comando procesamiento de transferencias");
        $pickings = $this->pickingByProcessService->getPickingsWithTimeLimit()?? [];
        $this->pickingByProcessService->processPickings($pickings);
    }
}
