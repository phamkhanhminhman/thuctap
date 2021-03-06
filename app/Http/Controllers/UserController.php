<?php

namespace App\Http\Controllers;
	
//Access-Control-Allow-Origin header with wildcard.
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods", "GET, POST, DELETE, PUT");
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Config;
use App\utils\Captcha;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\TbUser;
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
				'captcha' => 'required'
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
			$captcha_response = $request->captcha;
			$url = config('var.url_secret').config('var.google_secret').'&response='.$captcha_response;
			$resp= Captcha::recaptcha($url);
			if($resp->success == true) 
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
			else
			{
				return response()->json([
					'status'=> 403,
					'message'=> 'Failed',
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
				'image'=>'image',
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
					$img= "http://127.0.0.1:1111/upload/".$file->getClientOriginalName();
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
				'name' => 'max:20',
				'gender'=>'boolean|max:1',
				'groupID' =>'integer|max:20',
				'image'=>'image',
				'description'=>'max:255',
			],

			[
				'min' => ':attribute không được nhỏ hơn :min ký tự',
				'max' => ':attribute không được lớn hơn :max ký tự',
				'integer' => 'Must be an integer',
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
			$groupID=$request->groupID;
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
					$groupID_old=$key->groupID;
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
				if ($description==null)
				{
					$description=$description_old;
				}
				if ($groupID==null)
				{
					$groupID=$groupID_old;
				}
				if ($request->has('image'))
				{
					$img= "http://127.0.0.1:8000/upload/".$file->getClientOriginalName();
					DB::table('tb_users')->where('id',$id)->update(
						[
							'name'=>$name,
							'gender'=>$gender,
							'groupID'=>$groupID,
							'description'=>$description,
							'image'=>$img,

						]);
					return response()->json([
						'status' => 200,
						'message'=> 'Cập nhật thành công',
					]);			
				}
				else
				{
					DB::table('tb_users')->where('id',$id)->update(
						[
							'name'=>$name,
							'gender'=>$gender,
							'groupID'=>$groupID,
							'description'=>$description,

						]);
					return response()->json([
						'status' => 200,
						'message'=> 'Cập nhật thành công',
					]);;	
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
				TbUser::find($id)->delete();
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

		$all_user = DB::table('tb_users')->whereNull('deleted_at')->get();
		$total_user = count($all_user);
		$page = request()->page;
		$page_size = request()->pageSize;
		$sort = $_GET['sort'];
		if ($page_size == "")
		{	
			$page_size=$total_user;
		}
		if ($page == "")
		{
			$page = 1;
		}
		$start_index = ($page - 1) * $page_size;
		$query = $_GET['query'];
		if ($sort == null) 
		{
			$data = TbUser::leftjoin('tb_group', 'tb_users.groupID', '=' , 'tb_group.groupID')
							->select('id','name','email','gender','dob','description','image','tb_users.groupID','tb_group.groupID','tb_group.groupName') 
							->where('name', 'LIKE', "%{$query}%")
							->orWhere('email', 'LIKE', "%{$query}%")  	
							->get()->toArray();
		}
		else
		{
			$data = TbUser::leftjoin('tb_group', 'tb_users.groupID', '=' , 'tb_group.groupID')
							->select('id','name','email','gender','dob','description','image','tb_users.groupID','tb_group.groupID','tb_group.groupName')
							->where('name', 'LIKE', "%{$query}%")
							->orWhere('email', 'LIKE', "%{$query}%")
							->orderBy('tb_users.groupID', $sort)->get()->toArray();
		}
		 $data = array_slice($data, $start_index , $page_size);
		//dd(array_slice($data, 1));
		return response()->json([
			'status'=> 200,
			'message'=>'Dữ liệu trả về thành công',
			'data'=>$data,
			'length'=>$total_user,
		]);	
	}
	public function detailUser(Request $request, $id)
	{
		$data_1= DB::table('tb_users')->leftjoin('tb_group', 'tb_users.groupID', '=' , 'tb_group.groupID')
									  ->select('id','name','tb_users.groupID','gender','email','image','description','created_at','updated_at','tb_group.groupName')
									  ->where('id',$id)->get();
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
	public function importExcel(Request $request)
	{
		$token= $request->header('token');
		$file= $request->file('excel');

			if ($request->has('excel'))
			{
				$type_file=$file->getClientOriginalExtension();
				if ($type_file=='xlsx' || $type_file=='csv')
				{
					Excel::import(new UsersImport, request()->file('excel'));
					//$data_new = DB::table('tb_users')->select('*')->get();
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
	public function changePassword(Request $request)
	{	
		$old_pass= $request->old_pass;
		$new_pass= $request->new_pass;
		$token= $request->header('token');
		$data= DB::table('tb_users')->where('api_token',$token)->get();
		if (count($data)==1) 
		{	
			foreach ($data as $key) 
			{
				$password=$key->password;
			}
			if (md5($old_pass)==$password)
			{
				DB::table('tb_users')->where('api_token',$token)->update(
						[
							'password'=>md5($new_pass),
						]);
				return response()->json([
					'status' => 200,
					'message'=>'Change password successfully',
					'data' => $new_pass,
				]);
			}
			else
			{
				return response()->json([
					'status' => 404,
					'message'=>'Mật khẩu cũ không chính xác',
				]);
			}
		}
		else
		{
			return response()->json([
				'status' => 404,
				'message'=>'Must be login',
			]);
		}
	}
	public function listGroup() 
	{
		$data = DB::table('tb_group')->select('groupID','groupName')->get();
		return response()->json([
				'status' => 200,
				'message' =>'Dữ liệu group trả về thành công',
				'data' => $data,
			]); 

	}
}
