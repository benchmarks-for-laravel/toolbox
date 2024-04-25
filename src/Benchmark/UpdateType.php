<?php

namespace BenchmarksForLaravel\Toolbox\Benchmark;

enum UpdateType: string
{
    case Info = 'info';
    case Measurement = 'measurement';
    case Done = 'done';
}
