<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    public function run()
    {
        $update = \Telegram::getWebhookUpdates();
        $message = $update->getMessage();
//        $keyboard = [
//            [
//                ['text'=>'google','url'=>'http://google.com']
//            ],
//            [
//                ['text'=>'google','url'=>'http://google.com']
//            ]
//        ];
//
//        $reply_markup = \Telegram::replyKeyboardMarkup([
//            'inline_keyboard' => $keyboard,
//            'one_time_keyboard'=>true
//        ]);
        if ($message !== null && $message->has('text')) {
            $chat_id = $message->getChat()->getId();
            $check = 0;
            $save = 1;
            $command = $message->getText();
            $text=
                'سلام به کارآموزی وستاک  خوش آمدید.';
            $check=1;
            \Telegram::sendMessage(
                [
                    'chat_id'=>$chat_id,
                    'text'=>$text,
//                    'reply_markup' => $reply_markup
                ]);
        }
    }
}
