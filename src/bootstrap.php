<?php

require __DIR__ . '/../vendor/autoload.php';

register_shutdown_function(function () {
    \React\EventLoop\Loop::run();
});
