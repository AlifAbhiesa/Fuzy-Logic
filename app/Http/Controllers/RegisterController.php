<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\RegisterModel;
use App\MerchantModel;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    //

    public function store(Request $request){

        $token = Str::random(60);
        $ftoken = hash('sha256', $token);
        $act = 'first';
       

        $this->validate($request,[
    		'email' => 'required',
    		'phone' => 'required'
    	]);
 
        RegisterModel::create([
    		'email' => $request->email,
            'phone' => $request->phone,
            'api_token' => $ftoken,
        ]);
        
        
        Mail::to('abhiesa24@gmail.com')->send(new VerificationMail($ftoken,$act));

        $session = $this->getSessionData($ftoken);
 
    	return response()->json([
            'status' => '200',
            'response' => 'Ok',
            'user_data' => $session
        ]);
    }

    public function getSessionData($token){

        $result = RegisterModel::where('api_token', $token)->get();

        return $result;

    }

    public function verification(Request $request){
        $token = $request->input('token');
        $act = $request->input('act');

        if($act == "re"){

            $result = RegisterModel::where('temp_token', $token)->get();

            if(count($result) > 0){
                $date = date('Y-m-d H:i:s', time());
                $result = RegisterModel::where('temp_token', $token)->update([
                'email_verified_at' => $date,
                'api_token' => $token]);
                if($result > 0){
                    return response()->json([
                        'status' => '200',
                        'response' => 'Ok'
                    ]);
                }else{
                    return response()->json([
                        'status' => '500',
                        'response' => 'Failed'
                    ]);
                }
            }
            //$result['date'] = date('Y-m-d h:i:s a', time());
            
            
        }else if($act == "first"){
            $result = RegisterModel::where('api_token', $token)->get();

            if(count($result) > 0){
                $date = date('Y-m-d H:i:s', time());
                $result = RegisterModel::where('api_token', $token)->update(['email_verified_at' => $date]);
                if($result > 0){
                    return response()->json([
                        'status' => '200',
                        'response' => 'Ok'
                    ]);
                }else{
                    return response()->json([
                        'status' => '500',
                        'response' => 'Failed'
                    ]);
                }
            }
            //$result['date'] = date('Y-m-d h:i:s a', time());
            
        }else{

        }
        
    }

    

    public function LogOut(Request $request){
        $token = $request->input('token');
        $this->validate($request,[
            'token' => 'required'
        ]);

        $resultUpdate = RegisterModel::where('api_token', $token)->update([
            'email_verified_at' => null,
            'api_token' => ''
            ]);
       
        if($resultUpdate > 0){
            return response()->json([
                'response' => '200',
                'status' => 'Ok'
            ]);
        }

    }

    public function LoginData(Request $request){
        $email = $request->input('email');
        $this->validate($request,[
            'email' => 'required'
        ]);
        $act = 're';
        $resultGet = RegisterModel::where('email', $email)
        ->where('api_token','')
        ->where('email_verified_at',null)
        ->get();

        if(count($resultGet) > 0){
            $token = Str::random(60);
            $ftoken = hash('sha256', $token);
            
            
            $resultUpdate = RegisterModel::where('email', $email)->update([
            'temp_token' => $ftoken
            ]);
            
            if($resultUpdate > 0){

                Mail::to('abhiesa24@gmail.com')->send(new VerificationMail($ftoken,$act));

                return response()->json([
                    'response' => '200',
                    'status' => 'Ok',
                    'message' => 'Mohon verifikasi email'
                ]);
            }else{
                return response()->json([
                    'response' => '500',
                    'status' => 'Failed'
                ]);
            }
        }else{
            return response()->json([
                'response' => '500',
                'status' => 'Failed',
                'message' => 'Akun kamu telah login di perangkat lain'
            ]);
        }
    }

    public function checkAccount(Request $request){
        $token = $request->input('token');
        $email = $reques->input('email');

        $resultGet = RegisterModel::where('email', $email)->get();

        if(count($resultGet > 0)){
            $resultGet1 = RegisterModel::where('api_token', $token)->get();
            if($resultGet1 > 0){
                return response()->json([
                    'response' => '200',
                    'status' => 'Ok',
                    'message' => 'Aktif'
                ]);
            }else{
                return response()->json([
                    'response' => '500',
                    'status' => 'Failed',
                    'message' => 'Akun telah login diperangkat lain'
                ]);
            }
        }else{
            return response()->json([
                'response' => '500',
                'status' => 'Failed',
                'message' => 'Akun tidak terdaftar'
            ]);
        }
    }

    public function storeMerchantData(Request $request){

        $token = $request->input('token');
        $this->validate($request,[
    		'idUsers' => 'required',
            'merchantName' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'idCategory' => 'required'
        ]);

        $res = $this->tokenCheck($token);

        if($res == true){
            $result = $this->merchantCheck($request->idUsers);

            if($result == true){
                MerchantModel::create([
                    'idUsers' => $request->idUsers,
                    'merchantName' => $request->merchantName,
                    'longitude' => $request->longitude,
                    'latitude' => $request->latitude,
                    'address' => $request->address,
                    'idCategory' => $request->idCategory
                ]);
        
                return response()->json([
                    'status' => '200',
                    'response' => 'Ok'
                ]);
            }else{
                return response()->json([
                    'status' => '500',
                    'response' => 'Failed',
                    'message' => 'Anda sudah memiliki toko'
                ]);
            }
            
            
        }else{
            return response()->json([
                'status' => '500',
                'response' => 'Failed',
                'message' => 'Token tidak valid'
            ]);
        }
        
        
    }

    public function tokenCheck($token){
        $resultGet = RegisterModel::where('api_token', $token)->get();

        if(count($resultGet) > 0 ){
            return true;
        }else{
            return false;
        }
    }

    public function merchantCheck($idUsers){
        $resultGet = MerchantModel::where('idUsers', $idUsers)->get();

        if(count($resultGet) > 0 ){
            return false;
        }else{
            return true;
        }
    }
}
