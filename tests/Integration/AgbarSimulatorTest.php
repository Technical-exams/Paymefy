<?php namespace Proweb21\Elevator\App\Simulator;

use PHPUnit\Framework\TestCase;
use Proweb21\Elevator\Application\Simulators\AgbarSimulator;

// NEEDED INFRASTRUCTURE FOR REAL-TIME STATS DATA CAPTURE 
use Proweb21\Elevator\Model\ElevatorsStateChangedService;
use Proweb21\Elevator\Model\ElevatorStatsRepository as StatsRepository;
use Proweb21\Elevator\Infrastructure\Common\Persistence\SQLite\SQLite3DatabaseConnection;
use Proweb21\Elevator\Infrastructure\Domain\Persistence\SQLiteElevatorStatsStore;
use Proweb21\Elevator\Infrastructure\Domain\Persistence\ElevatorStatsRepository;
use Proweb21\Elevator\Application\ElevatorCalls\Strategies\CloserElevatorStrategy;





class AgbarSimulatorTest
    extends TestCase
{

    /**
     * The Agbar building simulator
     * 
     * @var AgbarSimulator
     */
    protected static $simulator;

    /**
     * The Service responsible for collecting elevator stats during simulation
     * 
     * @var ElevatorsStateChangedService
     */
    protected static $StatsService;

    /**
     * The repository used for saving the stats
     *
     * @var StatsRepository
     */
    protected static $repository;
    
    public static function setUpBeforeClass(): void
    {

        // We have a building of 4 flats with 3 elevators called "Agbar Building"
        self::$simulator = AgbarSimulator::instance();
        $building = self::$simulator->getBuilding();
        // Now we choose the strategy for managing elevators when a call is received
        $elevator_management_strategy = new CloserElevatorStrategy();
        self::$simulator->setStrategy($elevator_management_strategy);
                  
        // We also need to collect stats each time the State changes        
        $conn = new SQLite3DatabaseConnection($_ENV['DB_FILE'],$_ENV['DB_PASSWORD']);
        $store = new SQLiteElevatorStatsStore($conn);
        self::$repository = new ElevatorStatsRepository($store);
        self::$StatsService = new ElevatorsStateChangedService(self::$repository, $building);
        self::$repository->removeAll();        
    }


    /**
     * @test     
     * @testdox: Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 2
     *           Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 3
     *           Cada 10 minutos de 9:00h a 10:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 1
     */
    public function from_9h_to_10h()
    {

        $start = \DateTimeImmutable::createFromFormat('H:i','09:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','10:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(51,$count);        
        $this->assertLessThanOrEqual(58,$count);
        return $count;
    }

    /**
     * @test
     * @depends from_9h_to_10h 
     * @testdox: Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 2
     *           Cada 5 minutos de 9:00h a 11:00h llaman al ascensor 
     *           desde la planta baja para ir a la planta 3
     */    
    public function from_10h_to_11h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','10:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','11:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();      

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(41+$previous_count,$count);        
        $this->assertLessThanOrEqual(48+$previous_count,$count);

        return $count;
    }

    /**
     * @test
     * @depends from_10h_to_11h 
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     *
     */    
    public function from_11h_to_14h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','11:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','14:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();       

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(18+$previous_count,$count);        
        $this->assertLessThanOrEqual(54+$previous_count,$count);

        return $count;

    }

    /**
     * @test
     * @depends from_11h_to_14h
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     *           Cada 4 minutos de 14:00h a 15:00h llaman al ascensor
     *           desde las plantas 1, 2 y 3 para ir a la planta baja
     */        
    public function from_14h_to_15h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','14:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','15:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();       

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(42+6+$previous_count,$count);        
        $this->assertLessThanOrEqual(90+18+$previous_count,$count);

        return $count;        
    }

    /**
     * @test
     * @depends from_14h_to_15h
     * @testdox: Cada 7 minutos de 15:00h a 16:00h llaman al ascensor 
     *           desde las plantas 2 y 3 para ir a la planta baja
     *           Cada 7 minutos de 15:00h a 16:00h llaman al ascensor
     *           desde la planta baja para ir a las plantas 1 y 3
     */        
    public function from_15h_to_16h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','15:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','16:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();      
        
        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(28+6+$previous_count,$count);        
        $this->assertLessThanOrEqual(64+18+$previous_count,$count);

        return $count;                
    }

    /**
     * @test
     * @depends from_15h_to_16h
     * @testdox: Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     */        
    public function from_16h_to_18h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','16:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','18:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();       

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(12+$previous_count,$count);        
        $this->assertLessThanOrEqual(36+$previous_count,$count);

        return $count;                
    }

    /**
     * @test
     * @depends from_16h_to_18h
     * @testdox: Cada 3 minutos de 18:00h a 20:00h llaman al ascensor 
     *           desde las plantas 1, 2 y 3 para ir a la planta baja
     *           Cada 20 minutos de 11:00h a 18:20h llaman al ascensor 
     *           desde la planta baja para ir a las plantas 1, 2 y 3
     */        
    public function from_18h_to_20h($previous_count){
        $start = \DateTimeImmutable::createFromFormat('H:i','18:00');
        $end = \DateTimeImmutable::createFromFormat('H:i','20:00');

        self::$simulator->setStartTime($start);
        self::$simulator->setEndTime($end);
        self::$simulator->start();       

        $count = self::$repository->count();

        $this->assertGreaterThanOrEqual(114+0+$previous_count,$count);        
        $this->assertLessThanOrEqual(240+6+$previous_count,$count);

        return $count;                

    }
    

}