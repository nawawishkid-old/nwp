<?php

namespace NWP;

interface EventCollectorInterface
{
    public function on(string $eventName, callable $eventHandler);
}
