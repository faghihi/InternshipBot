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
            $command = $message->getText();
            $id=$message->getFrom()->getId();
            $conversation=Conversation::where('chat_id',$id)->first();
            if(is_null($conversation)){
                $conversation=new Conversation();
                $conversation->chat_id=$id;
                $conversation->state='0';
                $conversation->save();
            }
            switch ($conversation->state){
                case 0:
                    switch ($command){
                        case '/start':
                            $text=
                                'سلام به بات جاب یار خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','وارد کردن اطلاعات برای رزرو مصاحبه','راهنما'],
                            ];

                            $reply_markup = \Telegram::replyKeyboardMarkup([
                                'keyboard' => $keyboard,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true
                            ]);
                            break;
                    }
                    break;
            }
        }
    }
}
