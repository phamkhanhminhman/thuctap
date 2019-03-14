<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\URL;
class UserController extends Controller
{
	public function login(Request $request)
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
	public function logout(Request $request)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1){
			DB::table('tb_users')->where('api_token',$request->header('token'))->update(['api_token'=>null]);
			return response()->json([
	            'status'=> 200,
	            'message'=> 'Logout success',
        	]);
		}
		else
		{
			return response()->json([
	            'status'=> 200,
	            'message'=> 'nothing',
        	]);
		}
		
	}
	public function store(Request $request)
	{
		$this->validate($request, 
			[
				'name'=>'required|string',
				'gender'=>'required|string',
				'email'=>'required|email|unique:tb_users',
			 	'password'=>'required',
			 	'image'=>'required'
		    ]);
		$name= $request->name;
		$gender=$request->gender;
		$email= $request->email;
		$description= $request->description;
		$password= $request->password;
		$password= md5($password);
		$file= $request->file('image');
		if ($request->hasFile('image'))
		{
			$type_img=$file->getClientOriginalExtension();
			if ($type_img=='jpg' || $type_img=='png')
			{
				$img= "upload/".$file->getClientOriginalName();
    			$file->move('./upload/',$file->getClientOriginalName());
			}			
		}     
		DB::table('tb_users')->insert(
			[
				'name'=>$name,
				'gender'=>$gender,
				'email'=>$email,
				'password'=>$password,
				'description'=>$description,
				'image'=>$img,
			]);
		return response()->json([
            'status'=> 201,
            'message'=> 'Created account successfully',
        ]);
	}
	public function update(Request $request, $id)
	{
		$name= $request->name;
		$gender=$request->gender;
		$description= $request->description; 
		DB::table('tb_users')->where('id',$id)->update(
			[
				'name'=>$name,
				'gender'=>$gender,
				'description'=>$description,
			]);
		return response()->json([
	            'message'=> 'Updated',
	            'data'=> $name,
        	]);
	}
	public function destroy($id)
	{
		DB::table('tb_users')->where('id',$id)->delete();
		return response()->json([
	            'status'=> 204,
	            'message'=> 'Deleted record',
        	]);
	}
	public function listUser(Request $request)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if(count($data)==1)
		{
			$data= DB::table('tb_users')->select('*')->get();
			return response()->json([
				'status'=> 200,
				'message'=>'List User',
				'data'=>$data,
			]);
		}
		
	}
	public function detailUser(Request $request, $id)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1)
		{
			$data= DB::table('tb_users')->select('*')->where('id',$id)->get();
			return response()->json([
				'status'=> 200,
				'message'=>'Detail User',
				'data'=>$data,
			]);
		}
		
	}
	public function importExcel(Request $request)
	{
		$token = $request->header('token');
		$data= DB::table('tb_users')->where('api_token',$token)->get();
		if (count($data)==1)
		{
			Excel::import(new UsersImport, request()->file('excel'));
			return response()->json([
				'status'=> 200,
				'message'=>'Imported',
			]);
		}
		else
		{
			return response()->json([
				'status'=> 200,
				'message'=>'nothing',
			]);
		}
		
	}
}
