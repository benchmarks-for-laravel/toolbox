<?php

namespace BenchmarksForLaravel\Toolbox\Benchmark;

class Update
{
    private UpdateType $type;

    private string|null $group;

    private string|null $description;

    private float|null $measurement;

    public function type(UpdateType|null $type = null): UpdateType|static
    {
        if ($type === null) {
            return $this->type;
        }

        $this->type = $type;
        return $this;
    }

    public function group(string|null $group = null): string|static
    {
        if ($group === null) {
            return $this->group;
        }

        $this->group = $group;
        return $this;
    }

    public function description(string|null $description = null): string|static
    {
        if ($description === null) {
            return $this->description;
        }

        $this->description = $description;
        return $this;
    }

    public function measurement(float|null $measurement = null): float|static
    {
        if ($measurement === null) {
            return $this->measurement;
        }

        $this->measurement = $measurement;
        return $this;
    }

    public static function make(...$attributes): static
    {
        $instance = new static();

        foreach ($attributes as $key => $value) {
            $instance->$key = $value;
        }

        return $instance;
    }
}
