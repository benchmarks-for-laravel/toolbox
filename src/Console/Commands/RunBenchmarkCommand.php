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

    public function handle(): void
    {
        $benchmark = manager()->benchmark($this->argument('slug'));

        $previousGroup = null;

        $benchmark->run(onUpdate: function(Update $update) use (&$previousGroup) {
            switch ($update->type()) {
                case UpdateType::Done: {
                    $this->line('');
                    $this->line('Done.');
                    break;
                }

                case UpdateType::Measurement: {
                    $newGroup = $update->group();

                    if ($previousGroup !== $newGroup) {
                        $this->line('');
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
