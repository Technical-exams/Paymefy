<?php

namespace App\Http\Controllers;

use App\Facades\AgbarSimulator;
use Illuminate\Http\Request;
use Proweb21\Elevator\Model\ElevatorStatsRepository;

class SimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ElevatorStatsRepository $repository)
    {
        AgbarSimulator::start();
        

        return view('summary',["summary" => $repository->findAll($summarized=true)]);
    }
}
