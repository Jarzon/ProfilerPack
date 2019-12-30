<?php declare(strict_types=1);
namespace ProfilerPack\Controller;

use Prim\AbstractController;
use Prim\View;
use ProfilerPack\Service\Profiler as ProfilerService;

class Profiler extends AbstractController
{
    public ProfilerService $profiler;

    public function __construct(View $view, array $options = [], ProfilerService $profiler)
    {
        parent::__construct($view, $options);

        $this->profiler = $profiler;
    }

    public function profile()
    {
        if(!isset($_SERVER['HTTP_REFERER'])) {
            die('Use the profiling link in the toolbar to start profiling');
        }

        $_SESSION['profiling'] = true;
        $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function show()
    {
        $this->setTemplate('prim', 'PrimPack');

        $functions = $this->profiler->parseFile();

        $this->design('index', 'ProfilerPack', [
            'functions' => $functions
        ]);
    }

}
