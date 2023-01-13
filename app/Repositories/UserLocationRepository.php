<?php

namespace App\Repositories;

use App\Models\UserLocation;
use App\Models\User;

class UserLocationRepository extends AbstractRepository
{
    /**
     * @param UserLocation $model
     */
    public function __construct(
        protected UserLocation $model
    ) {

    }

    /**
     * @param User $user
     * @return $this
     */
    public function forUser(User $user): self
    {
        $this->model->query()
            ->where('user_id', $user->id);

        return $this;
    }
}
