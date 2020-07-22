<?php namespace Proweb21\Elevator\Application\Simulators;

use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCalled;
use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCalledService;
use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCallFactory;
use Proweb21\Elevator\Application\ElevatorCalls\ElevatorCallsBus;
use Proweb21\Elevator\Application\ElevatorCalls\Strategies\ElevatorCalledStrategy;
use Proweb21\Elevator\Application\Simulators\ElevatorCalls\ElevatorCallsSimulator;
use Proweb21\Elevator\Application\Simulators\Simulator;
use Proweb21\Elevator\Application\Simulators\SimulatorTrait;
use Proweb21\Elevator\Application\Simulators\Time\ChronoSimulator;
use Proweb21\Elevator\Events\EventHandler;
use Proweb21\Elevator\Events\Time\MinutePassed;
use Proweb21\Elevator\Events\Time\SystemTime;
use Proweb21\Elevator\Events\Time\TimeBus;
use Proweb21\Elevator\Model\Building;
use Proweb21\Elevator\Model\ElevatorsStateService;


final class AgbarSimulator
    implements Simulator,
               EventHandler
{

    use SimulatorTrait;

    const DEFAULT_START = '09:00';
    const DEFAULT_END = '20:00';

    const SIMULATION_FORMAT = '/^\s*(\d\d?):(\d\d?)\s+to+\s+(\d\d?):(\d\d?)\s+from\s+(\d+)\s+to\s+(\d+)\s+every\s+(\d+)\s+min/i';
    
    const DEFAULT_SIMULATIONS = [   "09:00 to 11:00 from 0 to 2 every 5 min",
                                    "09:00 to 11:00 from 0 to 3 every 5 min",
                                    "09:00 to 10:00 from 0 to 3 every 10 min",
                                    "11:00 to 18:20 from 0 to 1 every 20 min",
                                    "11:00 to 18:20 from 0 to 2 every 20 min",
                                    "11:00 to 18:20 from 0 to 3 every 20 min",
                                    "14:00 to 15:00 from 1 to 0 every 4 min",
                                    "14:00 to 15:00 from 2 to 0 every 4 min",
                                    "14:00 to 15:00 from 3 to 0 every 4 min",
                                    "15:00 to 16:00 from 2 to 0 every 7 min",
                                    "15:00 to 16:00 from 3 to 0 every 7 min",
                                    "15:00 to 16:00 from 0 to 1 every 7 min",
                                    "15:00 to 16:00 from 0 to 3 every 7 min",
                                    "18:00 to 20:00 from 1 to 0 every 3 min",
                                    "18:00 to 20:00 from 2 to 0 every 3 min",
                                    "18:00 to 20:00 from 3 to 0 every 3 min"
                                ];

    /**
     * The singleton instance
     *
     * @var AgbarSimulator
     */
    protected static $instance;

    /**
     * Singleton accessor
     *
     * @return AgbarSimulator
     */
    public static function instance() : AgbarSimulator
    {
        $result = new AgbarSimulator();
        return $result;
    }

    /**
     * The Agbar Building
     *
     * @var Building
     */
    protected $building;


    /**
     * State of the elevators during the simulation
     *
     * @var ElevatorsStateService;
     */
    protected $state;

    /**
     * Elevator Calls Service
     *
     * @var ElevatorCalledService
     */
    protected $elevator_calls_service;

    /**
     * The strategy that Elevator calls service must use
     *
     * @var ElevatorCalledStrategy
     */
    protected $strategy;

    /**
     * A list of call simulations to run expresed in the form
     * "START TIME (HH:MM) to END TIME (HH:MM) from CALLING FLAT (int) to DESTINATION FLAT (int) each MINUTES (int) min"
     * These these chains are queried in order to runn the simulation
     * 
     * @var array
     */
    protected $simulations = [];


    /**
     * A list of Call simulators used to run the simulations to activate
     * and a list of call simualtors used to run the simulations to deactivate
     *
     * @var array
     */    
    protected $program=["activations"=>[], "deactivations"=>[]];
    
    /**
     * With hour and minute will be simulated as the start time
     *
     * @var \DateTimeImmutable
     */
    protected $start_time;

    /**
     * With hour and minute will be simulated as the end time
     *
     * @var \DateTimeImmutable
     */
    protected $end_time;

    /**
     * TimeBus for controlling ElevatorCalls
     * 
     * @var TimeBus
     */
    protected $time_bus;


    /**
     * Clock simulator
     *
     * @var ChronoSimulator
     */
    protected $time_clock_simulator;

    /**
     * Bus for notifying elevator calls to elevator calls service
     * 
     * @var ElevatorCallsBus
     */
    protected $calls_bus;




    protected function __construct(array $flats = [0,1,2,3], int $elevators = 3)
    {
        // MODEL
        $this->building = new Building($flats);
        $this->setElevators($elevators);
        $this->state = new ElevatorsStateService($this->building);
        
        // SIMULATIONS SETUP
        $this->time_bus = new TimeBus();                
        $this->time_bus->subscribe(MinutePassed::class, SystemTime::instance() );
        // Our AGBAR simulator will trick the SystemTime using a ChronoSimulator
        // SystemTime will be subscribed to ChronoSimulators events through a TimeBus
      
        // Needed for managing the simulations program at real-time
        $this->time_bus->subscribe(MinutePassed::class, $this); 

        // Simulators calls will be broadcasted to this bus
        $this->calls_bus = new ElevatorCallsBus();
        
        $this->setStartTime(\DateTimeImmutable::createFromFormat('H:i',self::DEFAULT_START));
        $this->setEndTime(\DateTimeImmutable::createFromFormat('H:i',self::DEFAULT_END));
        $this->setCallSimulations(self::DEFAULT_SIMULATIONS);

    }


    /**
     * Sets the elevators of the building
     *
     * @param integer $elevators
     * @return void
     */
    protected function setElevators(int $elevators)
    {
        while ($elevators > 0){
            $elevators--;
            $this->building->createElevator();
        }
    }


    public function setStartTime(\DateTimeImmutable $start_time)
    {
        $this->start_time = $start_time;
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->start_time;
    }

    public function setEndTime(\DateTimeImmutable $end_time)
    {
        $this->end_time = $end_time;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return $this->end_time;
    }

    /**
     * Gets the Agbar Building Aggregate-Root Entity
     *
     * @return Building
     */
    public function getBuilding() : Building
    {
        return $this->building;
    }

    /**
     * Set the call simulations to run
     *
     * "START TIME (HH:MM) to END TIME (HH:MM) from CALLING FLAT (int) to DESTINATION FLAT (int) every MINUTES (int) min"
     * 
     * @param array $simulations
     * @return void
     */
    public function setCallSimulations(array $simulations = null)
    {
        if (empty($simulations))
            $simulations = self::DEFAULT_SIMULATIONS;
        else
            $this->validateSimulations($simulations);
        $this->simulations = $simulations;
        $this->resetProgram();
    }

    /**
     * Validates a list of call simulations
     *
     * @param array $simulations
     * @return void
     * @throws \InvalidArgumentException when there is a simulation not fulfilling the right format
     */
    protected function validateSimulations(array $simulations){
        foreach ($simulations as $simulation)
            if (0===preg_match(self::SIMULATION_FORMAT,$simulation))
                throw new \InvalidArgumentException("Invalid simulations provided");
    }

    /**
     * Sets the strategy to use when attending elevator calls
     *
     * @param ElevatorCalledStrategy $strategy
     * @return void
     */
    public function setStrategy(ElevatorCalledStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->elevator_calls_service = new ElevatorCalledService($this->building,$this->strategy,$this->state);        
        $this->calls_bus->subscribe(ElevatorCalled::class,$this->elevator_calls_service);
    }


    /**
     * Factorizes a new call simulator
     *
     * @param integer $calling_flat
     * @param integer $destination_flat
     * @param integer $frequency
     * @return void
     */
    protected function createSimulator(int $calling_flat, int $destination_flat, int $frequency): ElevatorCallsSimulator
    {
        $callsFactory = new ElevatorCallFactory($calling_flat,$destination_flat);
        return new ElevatorCallsSimulator($this->calls_bus, $callsFactory ,$frequency);        
    }


    protected function resetProgram()
    {
        $this->simulators = [];

        $matches = [];

        foreach ($this->simulations as $simulation){
            preg_match(self::SIMULATION_FORMAT,$simulation,$matches);
            $simulator = $this->createSimulator((int)$matches[5], (int)$matches[6], (int)$matches[7]);
            $this->programSimulator($simulator, (int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[4]);
        }

        $activations = $this->program["activations"];
        $deactivations = $this->program["deactivations"];
        ksort($activations);
        ksort($deactivations);
        $this->program["activations"] = $activations;
        $this->program["deactivations"] = $deactivations;
    }

    protected function programSimulator(ElevatorCallsSimulator $simulator, int $start_hour, int $start_minute, int $end_hour, int $end_minute)
    {                       
        $this->time_bus->subscribe(MinutePassed::class, $simulator);

        $start_time = str_pad($start_hour,2,"0",STR_PAD_LEFT).":".str_pad($start_minute,2,"0",STR_PAD_LEFT);
        $end_time = str_pad($end_hour,2,"0",STR_PAD_LEFT).":".str_pad($end_minute,2,"0",STR_PAD_LEFT);

        if (!array_key_exists($start_time,$this->program["activations"]))
            $this->program["activations"][$start_time]=[];
        $this->program["activations"][$start_time][] = $simulator;

        if (!array_key_exists($end_time,$this->program["deactivations"]))
            $this->program["deactivations"][$end_time]=[];
        $this->program["deactivations"][$end_time][] = $simulator;
    }


    protected function launchDeactivations($since)
    {
        if (!array_key_exists($since,$this->program["deactivations"]))
            return;
        $programmed_deactivations = $this->program["deactivations"];            
        $position = array_search($since,array_keys($programmed_deactivations));
        $pending_deactivations = array_splice($programmed_deactivations,0,$position+1);
        $this->program["deactivations"] = $programmed_deactivations;

        foreach($pending_deactivations as $deactivations){
            foreach($deactivations as $simulator)
                $simulator->stop();
        }
    }

    protected function launchActivations($until)
    {
        if (!array_key_exists($until,$this->program["activations"]))
            return;

        $programmed_activations = $this->program["activations"];            
        $position = array_search($until,array_keys($programmed_activations));
        $pending_activations = array_splice($programmed_activations,0,$position+1);
        $this->program["activations"] = $programmed_activations;

        foreach($pending_activations as $activations){
            foreach($activations as $simulator)
                $simulator->start();
        }
    }


    public function handle(MinutePassed $event)
    {              
        $deactivations = array_keys($this->program["deactivations"]);
        foreach ($deactivations as $deactivation){
            $time = \DateTimeImmutable::createFromFormat('H:i',$deactivation);
            if ($time <=$event->getTime())
                $this->launchDeactivations($deactivation);
        }
        $activations = array_keys($this->program["activations"]);
        foreach ($activations as $activation){
            $time = \DateTimeImmutable::createFromFormat('H:i',$activation);
            if ($time <=$event->getTime())
                $this->launchActivations($activation);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function doRun()
    {
        if (is_null($this->strategy))
            throw new \RuntimeException("Cannot run simulator, elevator calls strategy has not been defined yet");

        if ($this->end_time <= $this->start_time){
            $start = $this->start_time->format('H:i');
            $end = $this->end_time->format('H:i');    
            throw new \RuntimeException("Invalid start and end times. Start set at ${start} and end set at ${end}");
        }

        $hour = $this->start_time->format('H');
        $minute = $this->start_time->format('i');
        $diff = date_diff($this->end_time,$this->start_time,TRUE);
        $count = $diff->h*60+$diff->i;
        $this->time_clock_simulator=new ChronoSimulator($this->time_bus,(int)$count);
        $this->time_clock_simulator->setup((int)$hour, (int)$minute);

        $this->time_clock_simulator->start();
    }
    
   
}