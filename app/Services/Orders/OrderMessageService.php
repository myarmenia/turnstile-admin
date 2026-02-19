<?php

namespace App\Services\Orders;

use App\Interfaces\Orders\OrderMessageInterface;
use App\Mail\OrderSubmittedMail;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class OrderMessageService extends BaseService
{

    public function __construct(OrderMessageInterface $repository)
    {
        parent::__construct($repository);
    }

    public function storeAndSendEmail(array $data)
    {
        unset($data['captcha']);
        $order = $this->store($data);

        try {
            Mail::to(env('ORDER_MESSAGE_TO_MAIL'))->send(new OrderSubmittedMail($data));

        } catch (\Exception $e) {

            Log::error('Order email failed: ' . $e->getMessage());
        }

        return $order;
    }


}
