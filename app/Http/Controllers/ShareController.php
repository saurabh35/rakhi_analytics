<?php

namespace App\Http\Controllers;

use App\RakhiCampaign;
use App\SYMBResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ShareController extends Controller
{
    public function share(Request $request){
        try {
            $this->validate($request, [
                "id" => "required|exists:rakhi_campaigns,id"
            ]);
        } catch (ValidationException $e) {
            abort(404, "Invalid Request");
        }

        $rakhi = RakhiCampaign::find($request->input('id'));

        $baseUrl = "http://34.93.119.226:3000/";
        $fileName = $baseUrl. $rakhi->id.".png";
        return view('share', ['rakhi' => $rakhi,'fileName' => $fileName]);
    }

    public function saveRakhi2cmData(Request $request){

        try {
            $this->validate($request, [
                "name" => "required|string",
                "mobile" => "required|string",
                "sandesh" => "string",
                "rakhi" => "required|string"
            ]);
        } catch (ValidationException $e) {
            abort(404, $e->getMessage());
        }

        $input = Input::all();
        $rakhi = new  RakhiCampaign();
        $rakhi->name = $input['name'];
        $rakhi->mobile = $input['mobile'];
        if (isset($input['rakhi'])){
            $rakhi->rakhi = $input['rakhi'];
        }

        if (isset($input['sandesh'])){
            $rakhi->sandesh = $input['sandesh'];
        }else{
            $rakhi->sandesh = "No Message";
        }
        $otp = rand(1111, 9999);

        $this->sendOtp($otp, $input['mobile']);

        $rakhi->otp = $otp;
        $rakhi->save();
        $message = "प्रति,\nश्री. देवेंद्र फडणवीस\nमाननीय मुख्यमंत्री\nमहाराष्ट्र प्रदेश (राज्य)\n\n";
        $message = $message. $rakhi->sandesh;
        $name = $rakhi->name;



        $url = 'http://34.93.119.226:3000/?message=' . urlencode($message). '&name='
            .urlencode($name).
            "&salutation=".urlencode("आपली बहीण,")."&filename=".$rakhi->id.".png";

        $client = new Client();
        try{
            $resp = $client->get($url);
            if ($resp->getStatusCode() == 200)
            $res = $resp->getBody();
            $resArr = json_decode($res, true);
            Log::info($resArr);
            if ($resArr['success']){
                $rakhi->imageUrl = $resArr['url'];
                $rakhi->save();
            }
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
        }

        return Response::json(SYMBResponse::getSuccessMessage("Please enter otp to send the Rakhi", $rakhi, 200 ), 200);
    }
    private function sendOtp($otp, $phone){

        $SMS_API_KEY = "Af239ef6f3d0b6e0ce2ecc1fa37782833";
        $SMS_SENDER = "BJPMAH";

        $url = 'https://api-alerts.solutionsinfini.com' . '/v4/?api_key=' . $SMS_API_KEY . "&method=sms&message=Your%20OTP%20is%20" .$otp . "&to=91" .$phone. "&sender=" . $SMS_SENDER;
        $ch = curl_init();
        $timeout = 60;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        Log::info($data);
    }
    public function verifyOTP(Request $request){
//        try {
//            $this->validate($request, [
//                "otp" => "required|string",
//                "id" => "required",
//                "mobile" => "required|string"
//            ]);
//        } catch (ValidationException $e) {
//            abort(404, $e->getMessage());
//        }

        $rakhi = RakhiCampaign::where('id', $request->input('id'))
            ->where('mobile', $request->input("mobile"))
            ->where('otp', $request->input('otp'))
            ->first();
        if ($rakhi){
            return Response::json(SYMBResponse::getSuccessMessage("Your rakhi is on the way", $rakhi, 200 ), 200);
        }else{
            return Response::json(SYMBResponse::getErrorMessage("Otp verification failed", array()), 200);
        }

    }
}
