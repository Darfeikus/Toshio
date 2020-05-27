<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    /**
     * Send mail function
     *
     */
    public function sendMail($user,$password)
    {
    	Mail::to($user.'@itesm.mx')
    		->send(new TestMail($user,$password));
	    
	    if (Mail::failures()) {
	        // return with failed message
	    }
	    // return with success message
    }
}