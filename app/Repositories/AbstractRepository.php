<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @property \Illuminate\Database\Eloquent\Model $model
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
abstract class AbstractRepository
{
    use ForwardsCalls;

    protected array $by = [];

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->forwardCallTo($this->model, $name, $arguments);
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function by(array $arguments): self
    {
        $this->by = array_merge($this->by, $arguments);

        return $this;
    }

    /**
     * @param array $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function orCreate(array $arguments): Model
    {
        return $this->firstOrCreate($this->by, $arguments);
    }

    /**
     * @return $this
     */
    public function latest(): self
    {
        $this->model->query()
            ->latest();

        return $this;
    }

    /**
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->model->first();
    }
}
