<?php

namespace BenchmarksForLaravel\Toolbox\Benchmark;

use Closure;

abstract class Benchmark
{
    abstract public function getSlug(): string;

    abstract public function run(Closure $onUpdate): void;

    protected function update(
        UpdateType $type,
        string|null $group = null,
        string|null $description = null,
        float|null $measurement = null,
    ): Update
    {
        return Update::make(
            type: $type,
            group: $group,
            description: $description,
            measurement: $measurement,
        );
    }
}
