<?php

namespace App\Http\Controllers;
	
//Access-Control-Allow-Origin header with wildcard.
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods", "GET, POST, DELETE, PUT");
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\URL;
class UserController extends Controller
{
	public function login(Request $request)
    {
		$validate = Validator::make(
			$request->all(),
			[
				'email' => 'required|max:255|email',
				'password' => 'required',
			],
			[
				'required' => ':attribute không được để trống',
				'min' => ':attribute không được nhỏ hơn :min',
				'max' => ':attribute Không được lớn hơn :max',
				'email'=>'Định dạng email không hợp lệ',
			],
		);	
		if ($validate->fails()) 
		{   
			return response()->json([
				'status' =>400,
				'message'=>$validate->messages(),
			]);
		}
		else
		{
			$email= $request->email;
			$password= $request->password;
			$password= md5($password);
			$token=str_random(60);	
			$data= DB::table('tb_users')->where([['email', $email],['password', $password]])->get();
			if (count($data))
			{
				DB::table('tb_users')->where([['email', $email],['password', $password]])->update(['api_token'=>$token]);
				$data= DB::table('tb_users')->select('id','name','gender','email','api_token','image','description')->where([['email', $email],['password', $password]])->get();
				return response()->json([
					'status'=> 200,
					'message'=> 'Login thành công',
					'data'=>$data,
				]);
			}
			else
			{
				return response()->json([
					'status'=> 403,
					'message'=> 'Đăng nhập không thành công. Sai email hoặc password',
				]);
			}
		}
		
	}
	public function logout(Request $request)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1){
			DB::table('tb_users')->where('api_token',$request->header('token'))->update(['api_token'=>null]);
			return response()->json([
				'status'=> 200,
				'message'=> 'Logout thành công',
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
		$validate = Validator::make(
			$request->all(),
			[
				'email' => 'required|max:20|email',
				'password' => 'required|min:6|max:20',
				'name' => 'required|min:3|max:20 ',
				'gender'=>'required|Boolean',
				'description'=>'max:255 ký tự',
				'image'=>'image|max:1024',
			],
			[
				'required' => ':attribute không được để trống',
				'min' => ':attribute không được nhỏ hơn :min ký tự',
				'max' => ':attribute không được lớn hơn :max ký tự',
				'email'=>'Định dạng email không hợp lệ',
				'image' =>'chỉ định dạng JPG, PNG, GIF, JPEG được phép',
				'boolean'=>'chỉ cho phép nhập 2 giá trị 0 hoặc 1',
			],
		);	
		if ($validate->fails()) 
		{    
			return response()->json([
				'status' =>400,
				'message'=>$validate->messages(),
			]);
		}
		else
		{
			$name= $request->name;
			$gender=$request->gender;
			$email= $request->email;
			$description= $request->description;
			$password= $request->password;
			$password= md5($password);
			$file= $request->file('image');
			$email_unique= DB::table('tb_users')->select('email')->where('email',$email)->get();
			if (count($email_unique)==0)
			{
				if ($request->has('image'))
				{
					$img= "http://127.0.0.1:8000/upload/".$file->getClientOriginalName();
					$file->move('./upload/',$file->getClientOriginalName());
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
						'message'=> 'Tạo user mới thành công',
					]);			
				}
				else
				{
					DB::table('tb_users')->insert(
						[
							'name'=>$name,
							'gender'=>$gender,
							'email'=>$email,
							'password'=>$password,
							'description'=>$description,
						]);
					return response()->json([
						'status'=> 201,
						'message'=> 'Tạo user mới thành công',
					]);
				}			
			}
			else
			{
				return response()->json([
					'status'=> 409,
					'message'=> 'Email đăng ký đã bị trùng',
				]);
			}
		}
	}
	public function update(Request $request, $id)
	{
		$validate = Validator::make(
			$request->all(),
			[
				'password' => 'min:3|max:20',
				'name' => 'min:3|max:20',
				'gender'=>'Boolean',
				'image'=>'image',
				'description'=>'max:255',
			],

			[
				'min' => ':attribute không được nhỏ hơn :min ký tự',
				'max' => ':attribute không được lớn hơn :max ký tự',
				'boolean'=>'chỉ cho phép nhập 2 giá trị 0 hoặc 1',
			],
		);	
		if ($validate->fails()) 
		{    
			return response()->json([
				'status' =>400,
				'message'=>$validate->messages(),
			]);
		}
		else
		{
			$token=$request->header('token');
			$name= $request->name;
			$gender=$request->gender;
			$password=$request->password;
			$description= $request->description;
			$file= $request->file('image'); 
			$data= DB::table('tb_users')->where('id',$id)->get();
			if (count($data))
			{
				foreach ($data as $key) 
				{
					$name_old=$key->name;
					$gender_old=$key->gender;
					$password_old=$key->password;
					$description_old=$key->description;
					$image_old=$key->image;
					$token_old=$key->api_token;
				}
				if ($name==null )
				{
					$name=$name_old;
				}
				if ($gender==null) 
				{
					$gender=$gender_old;
				}
				if ($password==null)
				{
					$password=$password_old;
				}
				if ($description==null)
				{
					$description=$description_old;
				}
				if ($token==$token_old)
				{
				//$img= "C:/xampp/htdocs/baitap/public/upload/".$file->getClientOriginalName();
					DB::table('tb_users')->where('id',$id)->update(
						[
							'name'=>$name,
							'gender'=>$gender,
							'password'=> md5($password),
							'description'=>$description,

						]);
					return response()->json([
						'status' => 200,
						'message'=> 'Cập nhật thành công',
					]);			
				}
				else
				{
					return response()->json([
						'status' => 403,
						'message'=> 'Not allowed',
					]);	
				}
			}
			else
			{
				return response()->json([
					'status' => 204,
					'message'=> 'Không tồn tại user này',
				]);
			}		
		}
	}
	public function destroy(Request $request, $id)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1)
		{
			$data_1= DB::table('tb_users')->where('id',$id)->get();
			if (count($data_1))
			{
				DB::table('tb_users')->where('id',$id)->delete();
				return response()->json([
					'status'=> 204,
					'message'=> 'Deleted record',
				]);
			}
			else
			{
				return response()->json([
					'status'=> 204,
					'message'=> 'Không tồn tại user này',
				]);
			}			
		}
		else
		{
			return response()->json([
				'status'=> 204,
				'message'=> 'Must be login',
			]);
		}		
	}
	public function listUser(Request $request)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1)
		{
			$data= DB::table('tb_users')->select('id','name','gender','email','image','description','created_at','updated_at')->get();
			return response()->json([
				'status'=> 200,
				'message'=>'Dữ liệu trả về thành công',
				'data'=>$data,
			]);
		}
		else
		{
			return response()->json([
				'status'=> 204,
				'message'=> 'Must be login',
			]);
		}		
	}
	public function detailUser(Request $request, $id)
	{
		$data= DB::table('tb_users')->where('api_token',$request->header('token'))->get();
		if (count($data)==1)
		{
			$data_1= DB::table('tb_users')->select('id','name','gender','email','image','description','created_at','updated_at')->where('id',$id)->get();
			if (count($data_1))
			{
				return response()->json([
					'status' => 200,
					'message' =>'Dữ liệu chi tiết user trả về thành công',
					'data' => $data_1,
				]);
			}
			else
			{
				return response()->json([
					'status' => 204,
					'message'=>"Không có dữ liệu trả về",
				]);
			}	
		}
		else
		{
			return response()->json([
				'status' => 204,
				'message'=> 'Must be login',
			]);
		}
	}
	public function importExcel(Request $request)
	{
		$token= $request->header('token');
		$file= $request->file('excel');
		$data= DB::table('tb_users')->where('api_token',$token)->get();
		if (count($data)==1)
		{
			if ($request->has('excel'))
			{
				$type_file=$file->getClientOriginalExtension();
				if ($type_file=='xlsx' || $type_file=='csv')
				{
					Excel::import(new UsersImport, request()->file('excel'));
					return response()->json([
						'status' => 200,
						'message'=>'Imported file excel successfully',
					]);
				}
				else
				{
					return response()->json([
						'status' => 400,
						'message'=>'Chỉ định dạng XLSX,CSV được phép phép',
					]);
				}		
			}
			else
			{
				return response()->json([
					'status' => 204,
					'message'=>'nothing',
				]);
			}
		}
		else
		{
			return response()->json([
				'status' => 200,
				'message'=> 'Must be login',
			]);
		}	
	}
}
