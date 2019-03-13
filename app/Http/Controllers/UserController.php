<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
	public function checkLogin(Request $request)
	{
		$this->validate($request, 
			[
				'email'=>'required|email',
			 	'password'=>'required',
		    ]);	
		$email= $request->email;
		$password= $request->password;
		$password= md5($password);
		$token=str_random(60);	
		$data= DB::table('tb_users')->where([['email', $email],['password', $password]])->get();
		if (count($data))
		{
			DB::table('tb_users')->where([['email', $email],['password', $password]])->update(['api_token'=>$token]);
			$data= DB::table('tb_users')->where([['email', $email],['password', $password]])->get();
			return response()->json([
	            'status'=> 200,
	            'message'=> 'Login successfully',
	            'data'=>$data,
        	]);
		}
		else
		{
			return response()->json([
	            'status'=> 200,
	            'message'=> 'Login failed',
        	]);
		}
	}
	public function logout($id)
	{
		DB::table('tb_users')->where('id',$id)->update(['api_token'=>null]);
		return response()->json([
	            'status'=> 200,
	            'message'=> 'Logout success',
        	]);
	}
	
}
