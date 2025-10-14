<?php

namespace App\Repositories\Eloquent;

use App\Models\ServicePackage;
use App\Repositories\ServicePackageRepositoryInterface;

class ServicePackageRepository extends BaseRepository implements ServicePackageRepositoryInterface
{

    public function __construct(ServicePackage $model)
    {
        parent::__construct($model);
    }
}
