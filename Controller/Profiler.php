<?php
namespace ProfilerPack\Controller;

use Prim\AbstractController;

class Profiler extends AbstractController
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
