<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    /**
     * @param User $model
     */
    public function __construct(
        protected User $model
    ) {

    }

    /**
     * @param User $user
     * @return $this
     */
    public function for(User $user): self
    {
        $this->model->query()
            ->where('id', $user->id);

        return $this;
    }
}
