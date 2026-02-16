<?php

namespace App\Repositories\Orders;

use App\Interfaces\Orders\OrderMessageInterface;
use App\Models\OrderMessage;
use App\Repositories\BaseRepository;

class OrderMessageRepository extends BaseRepository implements OrderMessageInterface
{
    public function __construct()
    {
        parent::__construct(new OrderMessage());
    }


}
