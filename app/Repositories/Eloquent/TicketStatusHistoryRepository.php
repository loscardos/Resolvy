<?php

namespace App\Repositories\Eloquent;

use App\Models\TicketStatusHistory;
use App\Repositories\UserRepositoryInterface;

class TicketStatusHistoryRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(TicketStatusHistory $model)
    {
        parent::__construct($model);
    }
}
