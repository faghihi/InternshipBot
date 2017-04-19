<?php

namespace App\Http\Controllers;
use League\Flysystem\Exception;
use Mail;

class EmailController extends Controller
{
    public function send_email($data,$page,$subject,$to_email){
        $Email=\Config::get('email.email_send');
        $name=\Config::get('email.name');
        try {
            Mail::send('Emails.'.$page, $data, function($message) use ($Email,$subject,$to_email,$name) {
                $message->to($to_email)->subject
                ($subject);
                $message->from($Email,$name);
            });
        } catch (Exception $e) {
            if (count(Mail::failures()) > 0) {
                return 0;
            }
        }
        catch(\Swift_SwiftException $se){
            return 0;
        }
        return 1;
    }

    public function info_email($data,$page,$subject){
        $Email=\Config::get('email.email_send');
        $to_email=\Config::get('email.email_to');
        $name=\Config::get('email.name');
        try {
            Mail::send('Emails.'.$page, $data, function($message) use ($Email,$subject,$name,$to_email) {
                $message->to($to_email)->subject
                ($subject);
                $message->from($Email,$name);
            });
        } catch (Exception $e) {
            if (count(Mail::failures()) > 0) {
                return 0;
            }
        }
        catch(\Swift_SwiftException $se){
            return 0;
        }
        return 1;
    }

    public function test()
    {
        if($this->send_email(array('a'=>10),'testEmail','تست ایمیل','faghihi@ce.sharif.edu'))
            return 1;
        else
            return 0;
    }
}