<?php namespace Proweb21\Elevator\App\Simulator;

use PHPUnit\Framework\TestCase;

use Proweb21\Elevator\Model\Building;
use Proweb21\Elevator\Application\ElevatorCalledService;
use Proweb21\Elevator\Application\CloserElevatorStrategy;
use Proweb21\Elevator\Model\ElevatorsStateService;


use Proweb21\Elevator\Infrastructure\Common\Persistence\SQLite\SQLite3DatabaseConnection;

use Proweb21\Elevator\Infrastructure\Domain\Persistence\SQLiteElevatorStatsStore;
use Proweb21\Elevator\Infrastructure\Domain\Persistence\ElevatorStatsRepository;
use Proweb21\Elevator\Model\ElevatorsStateChangedService;


use Proweb21\Elevator\Infrastructure\Simulator\ElevatorCallSimulator;
use Proweb21\Elevator\Application\ElevatorCalled;
use Proweb21\Elevator\Application\ElevatorCallFactory;
use Proweb21\Elevator\Application\ElevatorCallsBus;

use Proweb21\Elevator\Infrastructure\Simulator\ChronoSimulator;
use Proweb21\Elevator\Events\MinutePassed;
use Proweb21\Elevator\Events\SystemTime;
use Proweb21\Elevator\Infrastructure\Simulator\TimeBus;



class AgbarSimulator
    extends TestCase
{
    /**
     * The AGBAR building 
     */
    static $Building;
    static $ElevatorsService;
    static $StatsService;
    static $repository;

    static $HourClockSimulator;
    static $TimeBus;
    static $SystemTime;

    static $ElevatorCallsBus;
    static $callsSimulators = [];

    public static function tearDownAfterClass(): void
    {
        self::$TimeBus->clearSubscribers();        
    }

    public static function setUpBeforeClass(): void
    {
        // We have a building of 4 flats with 3 elevators
        self::$Building = new Building([0,1,2,3]);     
        self::$Building->createElevator();
        self::$Building->createElevator();
        self::$Building->createElevator();
        
        // We need to manage the building elevators with a Management Service
        // its dependencies have to be created first

        // strategy of close elevator will suggest the closer elevator to the flat where comes an elevator call
        // as the elevator which must attend the call
        $elevator_management_strategy = new CloserElevatorStrategy();
        // the elevator state service will handle the state of the elevators in the building
        $elevator_state_service = new ElevatorsStateService(self::$Building);
        // this is our elevators managament service
        self::$ElevatorsService = new ElevatorCalledService(self::$Building, $elevator_management_strategy, $elevator_state_service);

        // The elevators management service will handle elevator calls
        // This calls will arrive through an eventbus: The Elevator Calls Bus
        self::$ElevatorCallsBus = new ElevatorCallsBus();        
        self::$ElevatorCallsBus->subscribe(ElevatorCalled::class,self::$ElevatorsService);

        // We also need to collect stats each time the State changes        
        $conn = new SQLite3DatabaseConnection($_ENV['DB_FILE'],$_ENV['DB_PASSWORD']);
        $store = new SQLiteElevatorStatsStore($conn);
        self::$repository = new ElevatorStatsRepository($store);
        self::$StatsService = new ElevatorsStateChangedService(self::$repository, self::$Building);

        // Our AGBAR simulator will trick the SystemTime using a ChronoSimulator
        self::$TimeBus = new TimeBus();
        self::$HourClockSimulator = new ChronoSimulator(self::$TimeBus,60); // 60 is the amount of minuts to simulate
        // SystemTime will be subscribed to ChronoSimulators events through a TimeBus

        self::$SystemTime = SystemTime::instance();
        self::$TimeBus->subscribe(MinutePassed::class, self::$SystemTime );

        self::createCallSimulators();
    }



    protected static function createCallSimulators(){
        // Planta baja a planta 2 cada 5 minutos
        $callsFactory1 = new ElevatorCallFactory(0,2);
        self::$callsSimulators["9-11 5min 0 a 2"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory1,5);
        // Planta baja a planta 3 cada 5 minutos
        $callsFactory2 = new ElevatorCallFactory(0,3);
        self::$callsSimulators["9-11 5min 0 a 3"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory2,5);
        // Planta baja a planta 1 cada 5 minutos        
        $callsFactory3 = new ElevatorCallFactory(0,1);
        self::$callsSimulators["9-10 10min 0 a 1"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory3,10);
        // Planta baja a planta 1 cada 20 minutos        
        $callsFactory4 = new ElevatorCallFactory(0,1);
        self::$callsSimulators["11-18.20 20min 0 a 1"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory4,20);
        // Planta baja a planta 1 cada 20 minutos        
        $callsFactory5 = new ElevatorCallFactory(0,2);
        self::$callsSimulators["11-18.20 20min 0 a 2"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory5,20);
        // Planta baja a planta 1 cada 20 minutos        
        $callsFactory6 = new ElevatorCallFactory(0,3);
        self::$callsSimulators["11-18.20 20min 0 a 3"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory6,20);
        // Planta 1 a planta baja cada 4 minutos        
        $callsFactory7 = new ElevatorCallFactory(1,0);
        self::$callsSimulators["14-15 4min 1 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory7,4);
        // Planta 2 a planta baja cada 4 minutos        
        $callsFactory8 = new ElevatorCallFactory(2,0);
        self::$callsSimulators["14-15 4min 2 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory8,4);
        // Planta 3 a planta baja cada 4 minutos        
        $callsFactory9 = new ElevatorCallFactory(3,0);
        self::$callsSimulators["14-15 4min 3 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory9,4);  
        // Planta 2 a planta baja cada 7 minutos        
        $callsFactory10 = new ElevatorCallFactory(2,0);
        self::$callsSimulators["15-16 7min 2 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory10,7);
        // Planta 3 a planta baja cada 7 minutos        
        $callsFactory11 = new ElevatorCallFactory(3,0);
        self::$callsSimulators["15-16 7min 3 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory11,7);
        // Planta baja a planta 1 cada 7 minutos        
        $callsFactory12 = new ElevatorCallFactory(0,1);
        self::$callsSimulators["15-16 7min 0 a 1"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory12,7);
        // Planta baja a planta 3 cada 7 minutos        
        $callsFactory13 = new ElevatorCallFactory(0,3);
        self::$callsSimulators["15-16 7min 0 a 3"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory13,7);
        // Planta 1 a planta baja cada 3 minutos        
        $callsFactory14 = new ElevatorCallFactory(1,0);
        self::$callsSimulators["18-20 3min 1 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory14,3);
        // Planta 2 a planta baja cada 3 minutos        
        $callsFactory15 = new ElevatorCallFactory(2,0);
        self::$callsSimulators["18-20 3min 2 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory15,3);
        // Planta 3 a planta baja cada 3 minutos        
        $callsFactory16 = new ElevatorCallFactory(2,0);
        self::$callsSimulators["18-20 3min 3 a 0"] = new ElevatorCallSimulator(self::$ElevatorCallsBus,$callsFactory16,3);

        foreach(self::$callsSimulators as $callsSimulator){
            self::$TimeBus->subscribe(MinutePassed::class, $callsSimulator);
        }

    }



    /**
     * @test
     * @doesNotPerformAssertions
     * @testdox: Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 2
     *           Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 3
     *           Cada 10 minutos de 9:00h a 10:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 1
     */
    public function from_9h_to_10h()
    {
        self::$callsSimulators["9-11 5min 0 a 2"]->start();
        self::$callsSimulators["9-11 5min 0 a 3"]->start();
        self::$callsSimulators["9-10 10min 0 a 1"]->start();

        // 9h to 10h
        self::$HourClockSimulator->start(9); // Chrono starts at hour #9

    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_9h_to_10h 
     * @testdox: Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 2
     *           Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 3
     */    
    public function from_10h_to_11h(){
        self::$callsSimulators["9-10 10min 0 a 1"]->stop();
        
        // 10h to 11h
        self::$HourClockSimulator->start(10);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_10h_to_11h 
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     *
     */    
    public function from_11h_to_14h(){
        self::$callsSimulators["9-11 5min 0 a 2"]->stop();
        self::$callsSimulators["9-11 5min 0 a 3"]->stop();

        self::$callsSimulators["11-18.20 20min 0 a 1"]->start();
        self::$callsSimulators["11-18.20 20min 0 a 2"]->start();
        self::$callsSimulators["11-18.20 20min 0 a 3"]->start();

        // 11 to 12h
        self::$HourClockSimulator->start(11); 
        // 12 to 13h
        self::$HourClockSimulator->start(12); 
        // 13 to 14h
        self::$HourClockSimulator->start(13); 
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_11h_to_14h
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     *           Cada 4 minutos de 14:00h a 15:00h llaman al ascensor
     *           desde las plantas 1, 2 y 3 para ir a la planta baja
     */        
    public function from_14h_to_15h(){

        self::$callsSimulators["14-15 4min 1 a 0"]->start();
        self::$callsSimulators["14-15 4min 2 a 0"]->start();
        self::$callsSimulators["14-15 4min 3 a 0"]->start();

        self::$HourClockSimulator->start(14); 
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_14h_to_15h
     * @testdox: Cada 7 minutos de 15:00h a 16:00h llaman al ascensor 
     *           desde las plantas 2 y 3 para ir a la planta baja
     *           Cada 7 minutos de 15:00h a 16:00h llaman al ascensor
     *           desde la planta baja para ir a las plantas 1 y 3
     */        
    public function from_15h_to_16h(){
        self::$callsSimulators["14-15 4min 1 a 0"]->stop();
        self::$callsSimulators["14-15 4min 2 a 0"]->stop();
        self::$callsSimulators["14-15 4min 3 a 0"]->stop();

        self::$callsSimulators["15-16 7min 2 a 0"]->start();
        self::$callsSimulators["15-16 7min 3 a 0"]->start();
        self::$callsSimulators["15-16 7min 0 a 1"]->start();
        self::$callsSimulators["15-16 7min 0 a 3"]->start();
        
        // 15h to 16h
        self::$HourClockSimulator->start(15); 
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_15h_to_16h
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     */        
    public function from_16h_to_18h(){
        self::$callsSimulators["15-16 7min 2 a 0"]->stop();
        self::$callsSimulators["15-16 7min 3 a 0"]->stop();
        self::$callsSimulators["15-16 7min 0 a 1"]->stop();
        self::$callsSimulators["15-16 7min 0 a 3"]->stop();

        // 16h to 17h
        self::$HourClockSimulator->start(16); 
        // 17h to 18h
        self::$HourClockSimulator->start(17); 
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @depends from_16h_to_18h
     * @testdox: Cada 3 minutos de 18:00h a 20:00h llaman al ascensor 
     *           desde las plantas 1, 2 y 3 para ir a la planta baja
     *           Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     */        
    public function from_18h_to_20h(){
        self::$HourClockSimulator = new ChronoSimulator(self::$TimeBus,20);
        
        self::$callsSimulators["18-20 3min 1 a 0"]->start();
        self::$callsSimulators["18-20 3min 2 a 0"]->start();
        self::$callsSimulators["18-20 3min 3 a 0"]->start();
        

        // 18h to 18:20h      
        self::$HourClockSimulator->start(18); 

        // 18:20h to 18:40h
        self::$callsSimulators["11-18.20 20min 0 a 1"]->stop();
        self::$callsSimulators["11-18.20 20min 0 a 2"]->stop();
        self::$callsSimulators["11-18.20 20min 0 a 3"]->stop();
        self::$HourClockSimulator->start(); 
        // 18:40h to 19:00h
        self::$HourClockSimulator->start(); 
        // 19:00h to 20:00h
        // unset(self::$HourClockSimulator);
        self::$HourClockSimulator = new ChronoSimulator(self::$TimeBus,60);
        self::$HourClockSimulator->start(19); 
    }


    /**
     * 
     * @doesNotPerformAssertions
     * @depends from_18h_to_20h
     */
    public function getSummary()
    {
        return self::$repository->findAll($summarized = true);
    }

}