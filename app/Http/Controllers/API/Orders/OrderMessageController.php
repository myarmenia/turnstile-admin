<?php

namespace App\Http\Controllers\API\Orders;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderMessageRequest;
use App\Services\Orders\OrderMessageService;
use Illuminate\Http\Request;

class OrderMessageController extends BaseController
{
    public function __construct(protected OrderMessageService $service) {}

    public function index(OrderMessageRequest $request)
    {

        $store = $this->service->storeAndSendEmail($request->all());

        return $this->sendResponse(
            [],
            'Order message send successfully'
        );
    }
}
