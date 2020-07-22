<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-top {
                align-items: top;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                margin-right: 10vw;
                margin-left: 10vw;                
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .table, .table thead, .table tbody, .table tr{
                display:block;
                width:100%;
            }
            .table .row .col{
                width: 20%;
                text-align: center;
            }
            .table .row, .table tr.row{                
                display: flex;                
                justify-content: space-between;
                align-items:stretch;
            }

            .table .row .index{
                font-weight: 800;
            }


            .table .row .cell{
                margin-top:0;
                padding-top: 0;
                line-height: 0.8;
                font-size: 0.8rem;
                display: flex;
                flex-direction: column;
                align-content: flex-start;
            }
            .table .row .cell li *{
                margin: 1ex 0;
            }
            .table .row .cell span{
                text-align: left;
            }
            
            .table .row ul{
                list-style: none;
                margin: 1rex;
                padding:0;
                margin-block-start: 0;
                margin-block-end: 0;
            }
            .table .row ul li{
                border-radius: 6px 6px 10px 6px;
                padding: 0.6ex;
            }
            @php 
                $counter = 0;
            @endphp

            @foreach ($state as $key => $value)
            .serial-{{ $key }} {
                background-color: hsla( {{ 90*($counter+1) - 10*$counter + 70 }},15%,70%,0.7);
            }
            @php
                $counter++;
            @endphp
            @endforeach
            

        </style>
    </head>
    <body>
        <div class="flex-top position-ref full-height">           
            <div class="content">
                <div class="title m-b-md">
                    Elevators summary
                </div>
                <table class="table">
                    <thead>
                        <tr class="row">
                            <th class="col index">Minute</th>
                            @foreach (range(0,3) as $flat)
                            <th class="col flat">Flat {{ $flat }}</th>    
                            @endforeach
                        </tr>
                    </thead>

                {{-- Initialization of variables --}}
                @php                    
                    $hour = (int)$start->format('H');;
                    $last_hour = (int)$end->format('H');                    
                @endphp

                @for (;$hour<$last_hour;$hour++) 
                @php
                    $str_hour = str_pad((string)$hour,2,0,\STR_PAD_LEFT);
                @endphp
                    @for ($minute = 0; $minute < 60; $minute++)                    
                    <tr class="row" id="{{ $hour.'-'.$minute }}">
                        {{-- States are updated from data --}}
                        @php
                            $str_min = str_pad((string)$minute,2,'0', \STR_PAD_LEFT);                            
                            $updating_state = true;
                            while ($data->valid() && $updating_state){                                
                                $time = $data->current()->last_update;                               
                                $updating_state = ((int)$time->format('H') == $hour) && ((int) $time->format('i') == $minute);
                                if ($updating_state){
                                    $state[$data->current()->elevator] = ['flat'=>$data->current()->flat_name, 
                                                                       'accum'=>$data->current()->total_moves, 
                                                                       'move'=>$data->current()->last_movement];
                                    $data->next();
                                }                            
                            }
                        @endphp


                        <td class="col index">
                            <h2 class="cell">
                            {{ $str_hour.':'.$str_min.' h' }}
                            </h2>
                        </td>
                        @foreach (range(0,3) as $flat)
                        <td class="col">                            
                            <ul class="cell">
                            @foreach ($state as $elevator => $el_data)                                
                            @if ($el_data['flat'] == $flat)
                                <li class="serial-{{ $elevator }}">
                                    <h4>{{ $elevator }}</h4>
                                    <span>M: {{ $el_data['move'] }}</span>
                                    <span>T: {{ $el_data['accum'] }}</span>                                                                            
                                </li>                                                            
                            @endif
                            @endforeach                          
                            </ul>  
                        </td>
                        @endforeach                        
                        </div>
                    </tr>
                    @endfor
                @endfor
                </table>
            </div>
        </div>
    </body>
</html>
