<?php
namespace ProfilerPack\Service\Profiler;

trait Profiler {
    /**
     * @return \ProfilerPack\Service\Profiler
     */
    public function getProfiler()
    {
        $obj = 'profiler';

        $tracesPath = ROOT . '/data/';

        $this->setDefaultParameter($obj, '\ProfilerPack\Service\Profiler');

        $profiler = $this->init($obj, $tracesPath);

        $toolbar = $this->getToolbarService();

        $toolbar->addElement('Profiler', function() {

            return ' - <a href="/profiler/profile/">Profile</a> - <a href="/profiler/show/">Show</a>';
        });

        return $profiler;
    }
}