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
        if ($message !== null) {
            $chat_id = $message->getChat()->getId();
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
                    $command = $message->getText();
                    switch ($command){
                        case '/start':
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                            break;
                        case '/help':
                        case 'راهنما':
                            $text='برای کار با بات مراحل زیر را دنبال نمایید : 
۱. برای کسب اطلاعات در مورد نحوه حضور و شرایط کارآموزی و هر گونه سوال مرتبط از منوی توضیح شرایط کارآموزی استفاده نمایید. 
۲. پس از کامل کردن اطلاعات خود اکنون در صورت تمایل میتوانید برای رزرو زمان مصاحبه اقدام نمایید برای این کار از منو گزینه ی وارد کردن اطلاعات برای رزرو مصاحبه را بزنید و مرحله به مرحله اقدامات لازم را انجام دهید . در صورت پذیرفته شدن برای مصاحبه با شما تماس گرفته خواهد شد.';
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            break;
                        case 'رزرو مصاحبه':
                            $text='مراحل زیر را دنبال نمایید تا اطلاعات شما برای ما ارسال شود و در صورت تایید بتوانیم پس از نماس با شما زمان مصاحبه را تعیین نماییم.';
                            $conversation->state=2;
                            $conversation->save();
                            \Telegram::sendMessage(
                                [
                                    'chat_id' => $chat_id,
                                    'text' => $text,
                                ]);
                            $text='نام و نام خانوادگی خود را به فارسی وارد نمایید.';
                            $keyboard = [
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
                                    'text'=>$text,
                                    'reply_markup' => $reply_markup
                                ]);
                            break;
                    }
                    break;
                case 1:
                    $command = $message->getText();
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
                                'text'=>'برای ادامه به منو مراجعه نمایید',
                                'reply_markup' => $reply_markup
                            ]);
                    }
                    else{
                        $text=
                            'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                        $keyboard = [
                            ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                case 2:
                    $command = $message->getText();
                    switch ($command){
                        case 'بازگشت':
                            $conversation->state=0;
                            $conversation->save();
                            $datas=Data::where('chat_id',$id)->get();
                            foreach ($datas as $data){
                                $data=Data::find($data->id);
                                $data->delete();
                            }
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                        default :
                            $data = new Data();
                            $data->chat_id = $id;
                            $data->state = 21;
                            $data->data = $command;
                            $data->save();
                            $conversation->state=3;
                            $conversation->save();
                            $text = 'لطفا میزان تحصیلات خود را انتخاب نمایید.';
                            $keyboard = [
                                ['زیر دیپلم', 'دیپلم', 'کارشناسی'], ['کارشناسی ارشد', 'دکتری', 'فوق دکتری'],
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
                case 3:
                    $command = $message->getText();
                    switch ($command){
                        case 'بازگشت':
                            $conversation->state=0;
                            $conversation->save();
                            $datas=Data::where('chat_id',$id)->get();
                            foreach ($datas as $data){
                                $data=Data::find($data->id);
                                $data->delete();
                            }
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                        default :
                            $data = new Data();
                            $data->chat_id = $id;
                            $data->state = 22;
                            $data->data = $command;
                            $data->save();
                            $conversation->state=4;
                            $conversation->save();
                            $text = 'لطفا جنسیت خود را انتخاب نمایید.';
                            $keyboard = [
                                ['زن', 'مرد'],
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
                case 4:
                    $command = $message->getText();
                    switch ($command){
                        case 'بازگشت':
                            $conversation->state=0;
                            $conversation->save();
                            $datas=Data::where('chat_id',$id)->get();
                            foreach ($datas as $data){
                                $data=Data::find($data->id);
                                $data->delete();
                            }
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                        case 'مرد':
                        case 'زن':
                            $data = new Data();
                            $data->chat_id = $id;
                            $data->state = 23;
                            $data->data = $command;
                            $data->save();
                            $conversation->state=5;
                            $conversation->save();
                            $text = 'لطفا محل سکونت خود را وارد نمایید.';
                            $dummy = \Config::get('majors.cities');
                            $keyboard = array();
                            foreach ($dummy as $key => $value) {
                                $keyboard[][] = $key;
                            }
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
                        default:
                            $text = 'لطفا جنسیت خود را انتخاب نمایید.';
                            $keyboard = [
                                ['زن', 'مرد'],
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
                case 5:
                    $command = $message->getText();
                    switch ($command){
                        case 'بازگشت':
                            $conversation->state=0;
                            $conversation->save();
                            $datas=Data::where('chat_id',$id)->get();
                            foreach ($datas as $data){
                                $data=Data::find($data->id);
                                $data->delete();
                            }
                            $text=
                                'سلام به بات کارآموزی وستاک خوش آمدید.لطفا از منوی تهیه شده روی گزینه مورد نظر خود اشاره نمایید.';
                            $keyboard = [
                                ['توضیح شرایط کارآموزی','رزرو مصاحبه','راهنما'],
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
                        default:
                            $array = array();
                            foreach (\Config::get('majors.cities') as $key => $value) {
                                $array[] = $key;
                            }
                            if (!in_array($command, $array)){
                                $text = 'لطفا محل سکونت خود را وارد نمایید.';
                                $dummy = \Config::get('majors.cities');
                                $keyboard = array();
                                foreach ($dummy as $key => $value) {
                                    $keyboard[][] = $key;
                                }
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
                            else{
                                $data = new Data();
                                $data->chat_id = $id;
                                $data->state = 24;
                                $data1 = \Config::get('majors.cities')[$command];
                                $data->data = $data1;
                                $data->save();
                                $conversation->state = 6;
                                $conversation->save();
                                $text='لطفا شماره تماس خود را برای ما ارسال نمایید.';
                                $keyboard = [
                                    [
                                        ['text' => 'ارسال اطلاعات تماس', 'request_contact'=>true],
                                    ],
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
                    }
                    break;
                case 6:
                    $text=$update->getMessage()->getContact()->getPhoneNumber();
                    \Telegram::sendMessage(
                            [
                                'chat_id' => $chat_id,
                                'text' => $text,
                            ]);
                    break;
            }
        }
    }
}
