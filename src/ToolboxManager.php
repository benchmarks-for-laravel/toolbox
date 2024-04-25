<?php

namespace BenchmarksForLaravel\Toolbox;

use BenchmarksForLaravel\Toolbox\Benchmark\Benchmark;
use BenchmarksForLaravel\Toolbox\Benchmark\Update;
use Closure;
use Illuminate\Support\Collection;

class ToolboxManager
{
    private Collection $classes;

    public function __construct()
    {
        $this->classes = collect();
    }

    public function addBenchmark(string $class): void
    {
        $this->classes->add($class);
    }

    public function removeBenchmark(string $class): void
    {
        $this->classes = $this->classes->filter(fn($item) => $item !== $class);
    }

    public function classes(): Collection
    {
        return $this->classes;
    }

    public function benchmarks(): Collection
    {
        return $this->classes->map(fn($class) => resolve($class));
    }

    public function benchmark(string $slug): Benchmark
    {
        return $this->benchmarks()->first(fn($item) => $item->getSlug() === $slug);
    }

    /**
     * Run all registered benchmarks
     */
    public function run(Closure|null $onUpdate = null): void
    {
        $benchmarks = $this->benchmarks();

        $onUpdate ??= function(Update $update) {};

        foreach ($benchmarks as $benchmark) {
            $benchmark->run($onUpdate);
        }
    }
}
