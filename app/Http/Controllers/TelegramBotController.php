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
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','وارد کردن اطلاعات برای رزرو مصاحبه','راهنما'],
                            ];

                            $reply_markup = \Telegram::replyKeyboardMarkup([
                                'keyboard' => $keyboard,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true
                            ]);
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                    'reply_markup' => $reply_markup
                                ]);
                            break;
                        case 'توضیح شرایط کارآموزی':
                            $text="از منوی تهیه شده گزینه مورد نظر خود را انتخاب نمایید.";
                            $conversation->state = 1;
                            $conversation->save();
                            $keyboard = [
                                [
                                    'درباره ی شرکت','موقعیت های موجود برای کارآموزی'

                                ],
                                ['زمان های کارآموزی','شرایط ورودی کارآموزی','مزایا'],
                                ['شرایط پس از کارآموزی','وظایف کاری در حین کارآموزی '],
                                ['بازگشت']
                            ];

                            $reply_markup = \Telegram::replyKeyboardMarkup([
                                'keyboard' => $keyboard,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true
                            ]);
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                    'reply_markup' => $reply_markup
                                ]);
                            break;
                    }
                    break;
                case 1:
                    $check=0;
                    switch ($command){
                        case 'درباره ی شرکت':
                            $text="
                            نام شرکت : وستا تاک آریا ( وستاک)
                            \n
شماره ثبت : ۵۰۶۷۴۹
                            \n
شرکت وستاک مجموعه ای از بهترین دانشحویان و فارغ التحصیلان دانشگاه های برتر تهران است که با هدفگذاری ارتباط دانشگاه با صنعت کار خود را آغاز نمود.
                            \n
این شرکت در زمینه های مختلفی همچون توسعه و طراحی وب ، توسعه و طراحی اپلیکیشن ،ساخت بستر های نرم افزاری ، تربیت نیروی کار ، برگزاری دره های آموزشی آکادمیک ، داده کاوی و اینترنت اشیاء در حال کار میباشد.
                            \n
فعالیت های داخل شرکت عموما خلاقانه بوده و فضای بسیار بازی را برای آموزش دیدن و پیشرفت کردن ساخته است و شما با مواردی روبه رو خواهید بود که در هیج جای دیگری مشابهی نخواهد داشت .
                            \n
محل کنونی شرکت در نزدیکی دانشگاه شریف واقع در خیابان حبیب الله ، پلاک 103 واحد دوم میباشد .
                            ";
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);

                            break;
                        case 'موقعیت های موجود برای کارآموزی':
                            $text="موقعیت های موجود به شرح زیر میباشند : ( الگو : موقعیت - نیازمندی های موقعیت ) 
                            \n";
                            $positions = \Config::get("conditions.opportunities");
                            foreach ($positions as $key=>$value) {
                                $sampletext=$key.' - '.$value."\n";
                                $text.=$sampletext;
                            }
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            break;
                        case 'زمان های کارآموزی':
                            $text="
                            زمان های کارآموزی در فصل بهار روز های شنبه تا ساعت ۴ ، و روز های 4 شنبه و 5 شنبه تا ساعت 6 میباشد و در فصل تابستان هر روز هفته(به جز جمعه) تا ساعت 4 میباشد کل زمان کارآموزی ۳ ماه است که از اولین روز کاری محاسبه خواهد شد.
                            \n
کارآموز در فصل بهار 15 ساعت در هفته و در فصل تابستان 30 ساعت در هفته باید در شرکت حضور داشته باشد.
                            ";
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            break;
                        case 'شرایط ورودی کارآموزی':
                            $text="برای ورود به کارآموزی باید شرایط زیر را داشته باشید :
                            \n";
                            $text.="
۱. حداقل دانشجوی دوره لیسانس باشید 
۲. داشتن زمان کافی برای حضور در شرکت در مدت زمان های تعیین شده
 ۳. علاقه مندی و داشتن حداقل نیازمندی های ذکر شده در بخش موقعیت های آزاد شرکت 
۴. قبولی در مصاحبه
۵. قبولی بعد از ۱۰ روز کار تستی در شرکت
\n
شرایط عمومی کارآموزی :
۱. ۳ ماه کارآموزی 
۲. بدون دریافتی و پرداختی 
۳. دریافت مبلغی برای تضمین حضور و بازگشت مبلغ در انتهای کارآموزی
۴. حضور در ساعات تعیین شده 
۵. پیشرفت قابل قبول در طول دوره 
                           ";
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            break;

                        case 'مزایا':
                            $text="برخی از مزایای حضور در دوره ی کارآموزی وستاک بدین شرح میباشد.\n";
                            $positions = \Config::get("conditions.goods");
                            foreach ($positions as $value) {
                                $sampletext=$value."\n";
                                $text.=$sampletext;
                            }

                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            break;
                        case 'بازگشت':
                            $conversation->state=0;
                            $conversation->save();
                            $check=1;
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','وارد کردن اطلاعات برای رزرو مصاحبه','راهنما'],
                            ];

                            $reply_markup = \Telegram::replyKeyboardMarkup([
                                'keyboard' => $keyboard,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true
                            ]);
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                    'reply_markup' => $reply_markup
                                ]);
                            break;
                    }
                    if(!$check){
                        $keyboard = [
                            [
                                'درباره ی شرکت','موقعیت های موجود برای کارآموزی'

                            ],
                            ['زمان های کارآموزی','شرایط ورودی کارآموزی'],
                            ['مزایا'],
                            ['بازگشت']
                        ];

                        $reply_markup = \Telegram::replyKeyboardMarkup([
                            'keyboard' => $keyboard,
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ]);
                        \Telegram::sendMessage(
                            [
                                'chat_id' => $chat_id,
                                'text' => $text,
                                'reply_markup' => $reply_markup
                            ]);
                    }

                    break;
            }
        }
    }
}
