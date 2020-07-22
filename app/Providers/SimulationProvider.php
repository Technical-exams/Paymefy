<?php

namespace App\Providers;

use App\Http\Controllers\SimulationController;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Proweb21\Elevator\Application\ElevatorCalls\Strategies\CloserElevatorStrategy;
use Proweb21\Elevator\Application\ElevatorCalls\Strategies\ElevatorCalledStrategy;
use Proweb21\Elevator\Application\Simulators\AgbarSimulator;
use Proweb21\Elevator\Infrastructure\Common\Persistence\DataBaseConnection;
use Proweb21\Elevator\Infrastructure\Common\Persistence\ElevatorStatsStore;
use Proweb21\Elevator\Infrastructure\Common\Persistence\SQLite\SQLite3DataBaseConnection;
use Proweb21\Elevator\Infrastructure\Domain\Persistence\ElevatorStatsRepository as PersistenceElevatorStatsRepository;
use Proweb21\Elevator\Infrastructure\Domain\Persistence\SQLiteElevatorStatsStore;
use Proweb21\Elevator\Model\ElevatorsStateChangedService;
use Proweb21\Elevator\Model\ElevatorStatsRepository;

final class SimulationProvider extends ServiceProvider
{

    /**
     * The service collecting stats
     *
     * @var ElevatorsStateChangedService
     */
    protected $stats_service;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        // ELEVATOR DATABASE CONNECTION
        $this->app->alias(DataBaseConnection::class, 'database');
        $this->app->singleton( DataBaseConnection::class, function($app){            
            $file = env('DB_FILE', base_path('database/building.dist.db'));
            $password = env('DB_PASSWORD', '');
            return new SQLite3DataBaseConnection($file,$password);
        });

        // DATA STORE
        $this->app->alias(ElevatorStatsStore::class, 'stats.store');
        //$this->app->bind( ElevatorStatsStore::class, SQLiteElevatorStatsStore::class);
        $this->app->bind( ElevatorStatsStore::class, function ($app){
            $conn = $app->make('database');
            return new SQLiteElevatorStatsStore($conn);
        });

        // DATA REPOSITORY
        //$this->app->bind(ElevatorStatsRepository::class,PersistenceElevatorStatsRepository::class);
        /*$this->app->bind(ElevatorStatsRepository::class, function($app){
            $store = $this->app->make('stats.store');
            return new PersistenceElevatorStatsRepository($store);
        });*/
        $this->app->when(SimulationController::class)
                  ->needs(ElevatorStatsRepository::class)
                  ->give(function(){
                    $store = $this->app->make('stats.store');
                    return new PersistenceElevatorStatsRepository($store);
                  });
    
        // SIMULATOR
        $this->app->alias(ElevatorCalledStrategy::class, 'agbar.strategy');
        $this->app->singleton(ElevatorCalledStrategy::class, function($app){
            return new CloserElevatorStrategy();
        });
               
        $this->app->alias(AgbarSimulator::class, 'simulator');
        $this->app->singleton(AgbarSimulator::class, function($app){
            $result = AgbarSimulator::instance();
            $result->setStrategy( $app->make('agbar.strategy'));
            return $result;
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $building = $this->app->make('simulator')->getBuilding();
        $store = $this->app->make('stats.store');
        $repo = new PersistenceElevatorStatsRepository($store);
        $this->stats_service = $stats_service = new ElevatorsStateChangedService($repo,$building);
    }
}
