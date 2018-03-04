<?php

namespace App\Http\Controllers;

use App\Conversation;
use Telegram\Bot\Api;
use Illuminate\Http\Request;

class telegramtestcontroller extends Controller
{
    public function index()
    {
        foreach(Conversation::all() as $conversation){
            \Telegram::sendMessage(
                [
                    'chat_id' => $conversation->chat_id,
                    'text' => 'بعد از رزو زمان مصاحبه توسط بات نیاز به انتظار برای تماس از سمت ما نیست . لطفا در زمان مقرر شده توسط بات در شرکت حضور داشته باشید :-)',
                ]);
        }
    }
}
