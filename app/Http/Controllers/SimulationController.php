<?php

namespace App\Http\Controllers;

use App\Facades\AgbarSimulator;
use Illuminate\Http\Request;
use Psr\Container\ContainerInterface;

class SimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ContainerInterface $container)
    {
        $repository = $container->make('stats.repository');
        $repository->removeAll();
        AgbarSimulator::start();
        //TODO MOVE LOGIC TO STATE SERVICE
        $building_elevators = AgbarSimulator::getBuilding()->getElevators();
        $state=[];
        foreach($building_elevators as $name => $elevator_data)
            $state[$name] = ['flat'=>0, 'accum'=>0, 'move'=>0];
        
        return view('summary',['state'=>$state, "start" => AgbarSimulator::getStartTime(), "end"=> AgbarSimulator::getEndTime(), "data" => $repository->findAll($summarized=true)]);
    }
}
