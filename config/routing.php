<?php
/** @var $this \Prim\Router */
$this->addGroup('/profiler', function(\Prim\Router $r) {
    $r->get('/profile/', 'ProfilerPack\Profiler', 'profile');

    $r->both('/show/', 'ProfilerPack\Profiler', 'show');
});