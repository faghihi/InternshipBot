<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Pdfcontroller;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function run()
    {
        $update = \Telegram::getWebhookUpdates();
        $message = $update->getMessage();
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
