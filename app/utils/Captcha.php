<?php 

namespace App\utils;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
class Captcha {
	public static function recaptcha($url)
	{
		// $curl = curl_init();
		// curl_setopt_array($curl, array(
		//     CURLOPT_RETURNTRANSFER => 1,
		//     CURLOPT_URL => $url,
		//     CURLOPT_USERAGENT => 'aaa',
		//     CURLOPT_SSL_VERIFYPEER => false, //Bỏ kiểm SSL

		// ));
		// $resp = curl_exec($curl);
		// curl_close($curl);
		// $resp = json_decode($resp); 
		$client = new Client([ 'verify' => false]);
   		$response = $client->request('get', $url);
   		$response = json_decode($response->getBody()->getContents());
		return $response;
	}	
}

 ?>