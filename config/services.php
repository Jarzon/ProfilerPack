<?php declare(strict_types=1);

use ProfilerPack\Controller\Profiler;
use Prim\Container;

return [
    Profiler::class => function(Container $dic) {
        $toolbar = $dic->get('toolbarService');
        $toolbar->addElement('Profiler', function() {

            return ' - <a href="/profiler/profile/">Profile</a> - <a href="/profiler/show/">Show</a>';
        });

        return [
            $dic->get('profilerService')
        ];
    }
];
