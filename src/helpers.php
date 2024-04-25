<?php

namespace BenchmarksForLaravel\Toolbox;

if (!function_exists('manager')) {
    function manager(): ToolboxManager
    {
        /** @var ToolboxManager $manager */
        $manager = resolve('benchmarks');

        return $manager;
    }
}
