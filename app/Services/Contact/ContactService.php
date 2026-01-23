<?php

namespace App\Services\Contact;

use App\Interfaces\Categories\CategoryInterface;
use App\Mail\SendContactMessage;
use App\Services\BaseService;
use Illuminate\Support\Facades\Mail;

class ContactService {

    public function sendEmail($data)
    {
        return  Mail::to(env('MAIL_USERNAME'))->send(new SendContactMessage($data));

    }

}
