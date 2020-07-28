<?php namespace Proweb21;

abstract class CommandBus implements Bus
{
    use DispatcherTrait;

    abstract public function name(): string;
}
