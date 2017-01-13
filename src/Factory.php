<?php

namespace React\EventLoop;

/**
 * Class Factory
 * @package React\EventLoop
 *
 * @deprecated use Loop::get()
 *
 */
class Factory
{
    public static function create()
    {
        return Loop::get();
    }
}
