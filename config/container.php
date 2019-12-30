<?php declare(strict_types=1);
/** @var $this \Prim\Container */

$this
    ->register('profilerService', \ProfilerPack\Service\Profiler::class);


$this->get('toolbarService')->addElement('Profiler', function() {

    return ' - <a href="/profiler/profile/">Profile</a> - <a href="/profiler/show/">Show</a>';
});
