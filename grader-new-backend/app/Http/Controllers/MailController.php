<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Mail\GroupMail;

class MailController extends Controller
{
    /**
     * Send mail function
     *
     */
    public static function sendMailLogin($user,$password)
    {
    	Mail::to($user.'@itesm.mx')
    		->send(new TestMail($user,$password));
	    
	    if (Mail::failures()) {
	        // return with failed message
	    }
	    // return with success message
    }

    public static function sendMailGroup($user,$name)
    {
    	Mail::to($user.'@itesm.mx')
    		->send(new GroupMail($name));
	    
	    if (Mail::failures()) {
	        // return with failed message
	    }
	    // return with success message
    }
}