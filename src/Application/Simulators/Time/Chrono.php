<?php namespace Proweb21\Elevator\Application\Simulators\Time;

/**
 * Chrono acts like a chronometer
 * Counts minutes and accumulated hours
 * 
 * The chrono current count can be queried calling "minutes" function
 */
class Chrono
{
    /**
     * Which hour has been reached by the chrono
     *
     * @var integer
     */
    protected $hour = 0;

    /**
     * Whose minutes have been reached by the chrono
     *
     * @var integer
     */
    protected $minutes = 0;

    /**
     * How many minutes to count
     *
     * @var integer
     */
    protected $count = 60;

    
    protected $counted= 0;

    public function __construct(int $count = 60, int $hour=null)
    {
        $this->reset($hour);
        $this->count = $count;
    }

    public function reset(int $hour=null, int $minutes=0){
        $this->minutes = $minutes;
        $this->hour = (!is_null($hour) && (0<=$hour && 24>$hour))?$hour:$this->hour;

    }

    public function minutes()
    {
        $count = 0;
        // We must chrono minutes while count is reached.
        while ($count < $this->count) {            
            // When minutes reach 60, minutes and hour are readjusted
            if ($this->minutes >= 60) {
                $this->reset(
                    $this->hour + intval($this->minutes / 60),
                    $this->minutes % 60
                );
            }
            
            yield ["hour"=>$this->hour,"minute"=>$this->minutes];

            $count++;
            $this->minutes++;
        }        
    }

}