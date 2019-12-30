# ProfilerPack

A simple Profiler for Prim

    // public/index.php
    if(isset($_SESSION['profiling'])) $container->get('profilerService')->startTrace();
    $container = new Container($config);
    $container->get('application');
    if(isset($_SESSION['profiling'])) $container->get('profilerService')->stopTrace();