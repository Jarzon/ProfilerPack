<?php
namespace ProfilerPack\Controller;

use Prim\Controller;

class Profiler extends Controller
{
    public function profile()
    {
        $_SESSION['profiling'] = true;
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function show()
    {
        $this->setTemplate('prim', 'PrimPack');

        $profiler = $this->container->getXdebugTrace();

        $functions = $profiler->parseFile();

        $this->design('index', 'ProfilerPack', [
            'functions' => $functions
        ]);
    }

}