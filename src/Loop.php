<?php

namespace React\EventLoop;

use React\EventLoop\Timer\TimerInterface;

final class Loop
{
    /** @var  LoopInterface */
    private static $loop;

    public static function create(LoopInterface $loop = null)
    {
        if (static::$loop) {
            return;
        }

        if ($loop) {
            static::$loop = $loop;
            return;
        }

        // @codeCoverageIgnoreStart
        if (function_exists('event_base_new')) {
            static::$loop = new LibEventLoop();
        } elseif (class_exists('libev\EventLoop', false)) {
            static::$loop = new LibEvLoop;
        } elseif (class_exists('EventBase', false)) {
            static::$loop = new ExtEventLoop;
        }

        static::$loop = new StreamSelectLoop();
        // @codeCoverageIgnoreEnd
    }

    /**
     * @return mixed
     */
    public static function get()
    {
        if (!static::$loop) {
            static::create();
        }

        return self::$loop;
    }

    /**
     * Register a listener to be notified when a stream is ready to read.
     *
     * @param resource $stream The PHP stream resource to check.
     * @param callable $listener Invoked when the stream is ready.
     */
    public static function addReadStream($stream, callable $listener)
    {
        $loop = static::$loop ?: static::get();
        $loop->addReadStream($stream, $listener);
    }

    /**
     * Register a listener to be notified when a stream is ready to write.
     *
     * @param resource $stream The PHP stream resource to check.
     * @param callable $listener Invoked when the stream is ready.
     */
    public static function addWriteStream($stream, callable $listener)
    {
        $loop = static::$loop ?: static::get();
        $loop->addWriteStream($stream, $listener);
    }

    /**
     * Remove the read event listener for the given stream.
     *
     * @param resource $stream The PHP stream resource.
     */
    public static function removeReadStream($stream)
    {
        $loop = static::$loop ?: static::get();
        $loop->removeReadStream($stream);
    }

    /**
     * Remove the write event listener for the given stream.
     *
     * @param resource $stream The PHP stream resource.
     */
    public static function removeWriteStream($stream)
    {
        $loop = static::$loop ?: static::get();
        $loop->removeWriteStream($stream);
    }

    /**
     * Remove all listeners for the given stream.
     *
     * @param resource $stream The PHP stream resource.
     */
    public static function removeStream($stream)
    {
        $loop = static::$loop ?: static::get();
        $loop->removeStream($stream);
    }

    /**
     * Enqueue a callback to be invoked once after the given interval.
     *
     * The execution order of timers scheduled to execute at the same time is
     * not guaranteed.
     *
     * @param int|float $interval The number of seconds to wait before execution.
     * @param callable $callback The callback to invoke.
     *
     * @return TimerInterface
     */
    public static function addTimer($interval, callable $callback)
    {
        $loop = static::$loop ?: static::get();
        return $loop->addTimer($interval, $callback);
    }

    /**
     * Enqueue a callback to be invoked repeatedly after the given interval.
     *
     * The execution order of timers scheduled to execute at the same time is
     * not guaranteed.
     *
     * @param int|float $interval The number of seconds to wait before execution.
     * @param callable $callback The callback to invoke.
     *
     * @return TimerInterface
     */
    public static function addPeriodicTimer($interval, callable $callback)
    {
        $loop = static::$loop ?: static::get();
        return $loop->addPeriodicTimer($interval, $callback);
    }

    /**
     * Cancel a pending timer.
     *
     * @param TimerInterface $timer The timer to cancel.
     */
    public static function cancelTimer(TimerInterface $timer)
    {
        $loop = static::$loop ?: static::get();
        $loop->cancelTimer($timer);
    }

    /**
     * Check if a given timer is active.
     *
     * @param TimerInterface $timer The timer to check.
     *
     * @return boolean True if the timer is still enqueued for execution.
     */
    public static function isTimerActive(TimerInterface $timer)
    {
        $loop = static::$loop ?: static::get();
        return $loop->isTimerActive($timer);
    }

    /**
     * Schedule a callback to be invoked on the next tick of the event loop.
     *
     * Callbacks are guaranteed to be executed in the order they are enqueued,
     * before any timer or stream events.
     *
     * @param callable $listener The callback to invoke.
     */
    public static function nextTick(callable $listener)
    {
        $loop = static::$loop ?: static::get();
        $loop->nextTick($listener);
    }

    /**
     * Schedule a callback to be invoked on a future tick of the event loop.
     *
     * Callbacks are guaranteed to be executed in the order they are enqueued.
     *
     * @param callable $listener The callback to invoke.
     */
    public static function futureTick(callable $listener)
    {
        $loop = static::$loop ?: static::get();
        $loop->futureTick($listener);
    }

    /**
     * Perform a single iteration of the event loop.
     */
    public static function tick()
    {
        $loop = static::$loop ?: static::get();
        $loop->tick();
    }

    /**
     * Run the event loop until there are no more tasks to perform.
     */
    public static function run()
    {
        $loop = static::$loop ?: static::get();
        $loop->run();
    }

    /**
     * Instruct a running event loop to stop.
     */
    public static function stop()
    {
        $loop = static::$loop ?: static::get();
        $loop->stop();
    }
}
