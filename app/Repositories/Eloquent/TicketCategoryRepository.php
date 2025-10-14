<?php

namespace App\Repositories\Eloquent;

use App\Models\TicketCategory;
use App\Repositories\TicketCategoryRepositoryInterface;

class TicketCategoryRepository extends BaseRepository implements TicketCategoryRepositoryInterface
{

    public function __construct(TicketCategory $model)
    {
        parent::__construct($model);
    }
}
