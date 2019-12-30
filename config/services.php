<?php declare(strict_types=1);

use ProfilerPack\Controller\Profiler;
use Prim\Container;
use ProfilerPack\Service\Profiler as ProfilerService;

return [
    Profiler::class => function(Container $dic) {
        return [
            $dic->get('profilerService')
        ];
    },
    ProfilerService::class => function(Container $dic) {
        return [
            $dic->options
        ];
    }
];
