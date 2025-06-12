<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Mail\NewEmail;
use App\Mail\SendMailable;
use Validator;
use Exception;

class MailController extends BaseController
{     
    public function sendEmail(Request $request) {
        $validator = Validator::make($request->all(), [
            'to' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        
        if ($validator->fails())
           return $this->sendError('Error validation', $validator->errors()->getMessages(), 400); 
        else                                      
            try {
                $to = $request->input('to');
                $subject = $request->input('subject');
                $message = $request->input('message');
                Mail::to($to)
                    ->send(new SendMailable($subject, $message));
                 
                return $this->sendResponse('', 'Email sent Successfully', 200);
            } catch (Exception $exception) {
                // $status = 554;
                return $this->sendError('', 'Email has not been sent. Error: ' . $exception->getMessage(), 500);
            }
    }
}
