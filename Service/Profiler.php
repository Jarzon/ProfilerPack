<?php
namespace ProfilerPack\Service;

class Profiler
{
    protected $dir;

    function __construct($dir)
    {
        $this->dir = $dir;
    }

    function startTrace() {
        if(isset($_SESSION['profiling']) && $_SESSION['profiling']) {
            xdebug_start_trace("{$this->dir}lastTrace", 2);
        }
    }

    function parseFile($sortKey = 'time-own') {
        $parser = new XdebugTraceFileParser("{$this->dir}lastTrace.xt");
        $parser->parse();
        return $parser->getFunctions($sortKey);
    }

    function stopTrace() {
        if(isset($_SESSION['profiling']) && $_SESSION['profiling']) {
            xdebug_stop_code_coverage();
            $_SESSION['profiling'] = false;
        }

    }
}
