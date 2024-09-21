<?php

namespace App\Providers;

use App\Repositories\GeneralParameters\GeneralParametersRepository;
use App\Repositories\GeneralParameters\Impl\GeneralParametersRepositoryImpl;
use App\Services\Auth\AuthService;
use App\Services\Auth\Impl\AuthServiceImpl;
use App\Services\CityService\CityService;
use App\Services\CityService\Impl\CityServiceImpl;
use App\Services\LocationService\Impl\LocationServiceImpl;
use App\Services\LocationService\LocationService;
use App\Services\Notes\Impl\NoteServiceImpl;
use App\Services\Notes\NoteService;
use App\Services\Outbound\Impl\OutboundServiceImpl;
use App\Services\Outbound\OutboundService;
use App\Services\PartnerService\Impl\PartnerServiceImpl;
use App\Services\PartnerService\PartnerService;
use App\Services\Pdf\Impl\PdfServiceImpl;
use App\Services\Pdf\PdfService;
use App\Services\PickingService\Impl\PickingByProcessServiceImpl;
use App\Services\PickingService\Impl\PickingProcessServicesImpl;
use App\Services\PickingService\Impl\PickingServiceImpl;
use App\Services\PickingService\Impl\PickingUtilityServiceImpl;
use App\Services\PickingService\PickingByProcessService;
use App\Services\PickingService\PickingProcessServices;
use App\Services\PickingService\PickingService;
use App\Services\PickingService\PickingUtilityService;
use App\Services\ProductService\Impl\ProductServiceImpl;
use App\Services\ProductService\ProductService;
use App\Services\SaleOrderService\Impl\SaleOrdersServiceImpl;
use App\Services\SaleOrderService\SaleOrdersService;
use App\Services\StockMoveService\Impl\StockMoveServiceImpl;
use App\Services\StockMoveService\StockMoveService;
use App\Services\StockQuantService\Impl\StockQuantServiceImpl;
use App\Services\StockQuantService\StockQuantService;
use App\Services\TccService\Impl\TccServiceImpl;
use App\Services\TccService\TccService;
use App\Services\User\Impl\UserServiceImpl;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        # Services
        $this->app->bind(AuthService::class, AuthServiceImpl::class);
        $this->app->bind(CityService::class, CityServiceImpl::class);
        $this->app->bind(LocationService::class, LocationServiceImpl::class);
        $this->app->bind(NoteService::class, NoteServiceImpl::class);
        $this->app->bind(OutboundService::class,OutboundServiceImpl::class);
        $this->app->bind(PartnerService::class, PartnerServiceImpl::class);
        $this->app->bind(PdfService::class, PdfServiceImpl::class);
        $this->app->bind(PickingByProcessService::class, PickingByProcessServiceImpl::class);
        $this->app->bind(PickingProcessServices::class, PickingProcessServicesImpl::class);
        $this->app->bind(PickingService::class, PickingServiceImpl::class);
        $this->app->bind(PickingUtilityService::class, PickingUtilityServiceImpl::class);
        $this->app->bind(ProductService::class, ProductServiceImpl::class);
        $this->app->bind(SaleOrdersService::class, SaleOrdersServiceImpl::class);
        $this->app->bind(StockMoveService::class, StockMoveServiceImpl::class);
        $this->app->bind(StockQuantService::class, StockQuantServiceImpl::class);
        $this->app->bind(TccService::class,TccServiceImpl::class);
        $this->app->bind(UserService::class, UserServiceImpl::class);

        # Repositories
        $this->app->bind(GeneralParametersRepository::class, GeneralParametersRepositoryImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
