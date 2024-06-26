<?php

namespace BenchmarksForLaravel\Toolbox\Console\Commands;

use BenchmarksForLaravel\Toolbox\Benchmark\Benchmark;
use BenchmarksForLaravel\Toolbox\Benchmark\Update;
use BenchmarksForLaravel\Toolbox\Benchmark\UpdateType;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function BenchmarksForLaravel\Toolbox\manager;
use function Laravel\Prompts\search;

class RunBenchmarkCommand extends Command implements PromptsForMissingInput
{
    /**
     * @var string
     */
    protected $signature = 'benchmarks-for-laravel:run-benchmark {slug}';

    /**
     * @var string
     */
    protected $description = 'Runs one or more benchmarks';

    public function handle(): int
    {
        $benchmark = manager()->benchmark($this->argument('slug'));

        if (!$benchmark) {
            $this->error('Could not find the requested benchmark');
            return static::FAILURE;
        }

        $previousGroup = null;

        $benchmark->run(onUpdate: function(Update $update) use (&$previousGroup) {
            switch ($update->type()) {
                case UpdateType::Done: {
                    $this->newLine();
                    break;
                }

                case UpdateType::Measurement: {
                    $newGroup = $update->group();

                    if (!$previousGroup) {
                        $this->line($previousGroup = $newGroup);
                    } else if ($previousGroup !== $newGroup) {
                        $this->newLine();
                        $this->line($previousGroup = $newGroup);
                    }

                    $this->info($update->description(). ' ('.number_format($update->measurement(), 3).'ms)');

                    break;
                }

                default: {
                    $this->line($update->description());
                    break;
                }
            }
        });

        return static::SUCCESS;
    }

    protected function promptForMissingArgumentsUsing(): array
    {
        $benchmarks = manager()
            ->benchmarks()
            ->map(fn(Benchmark $item) => $item->getSlug())
            ->toArray();

        return [
            'slug' => fn () => search(
                label: 'Search for a package:',
                options: fn ($value) => strlen($value) > 0
                    ? $benchmarks
                    : [],
                placeholder: count($benchmarks) > 0 ? 'E.g. '.$benchmarks[0] : null,
            ),
        ];
    }
}
