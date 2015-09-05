<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*       The MIT License (MIT)

	*	Copyright (c) 2015 josh jimie <joshjimie@gmail.com>
	*
	*	Permission is hereby granted, free of charge, to any person obtaining a copy
	*	 of this software and associated documentation files (the "Software"), to deal
	*	 in the Software without restriction, including without limitation the rights
	*	 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	*	 copies of the Software, and to permit persons to whom the Software is
	*	 furnished to do so, subject to the following conditions:
	*
	*	The above copyright notice and this permission notice shall be included in
	*	all copies or substantial portions of the Software.
	*
	*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	*	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	*	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	*	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	*	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	*	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	*	THE SOFTWARE.
	*	 
	*/
	
class Vgniter_lib {
	
	
	
	function __construct()
	{
	
		$this->ci =& get_instance();
		//$this->ci->load->helper('url_helper');
		$this->ci->config->load('vgniter', TRUE);
		$this->ci->load->helper( 'url');
		$this->ci->load->helper('form');
		//$config['base_url'] = site_url((config_item('index_page') == '' ? SELF : '').$config['base_url']);

		//log_message('debug', 'HybridAuthLib Class Initalized');
	}
	
	public static $forminc = 0;
	public static $dtotal = 0;

    public function doforminc() {
      return  self::$forminc += 1;
    }
	
    public function dototal($price) {
      self::$dtotal += $price;
    }
	
	public function gettotal() {
      return  self::$dtotal;
    }
	//self::doforminc
	
	
	function voguepay_add_item( &$arr, $item = 'item', $desc = 'item description', $price = '')
	{
			// <input type='hidden' name='item_1' value='Face Cap' />
		// <input type='hidden' name='description_1' value='Blue Zizi facecap' />
		// <input type='hidden' name='price_1' value='2000' />
	 $unq = self::doforminc();
		$itemn = 'item_'.$unq;
		$descn = 'description_'.$unq;
		$pricen = 'price_'.$unq;
		
		 $arr .= form_hidden($itemn, $item);
		 $arr .= form_hidden($descn, $desc);
		 $arr .= form_hidden($pricen, $price);
		 self::dototal($price);
		 return $arr;
	}
	
	
	
	function voguepay_start( $memo = 'Payment form',  $cur = 'NGN', $store_id = null, $recurrent =null, $interval = null, $demo = null) {


	$merchant_id = isset($demo) ? "demo" : $this->ci->config->item('merchant_id', 'vgniter');
	$merchant_ref = $this->ci->config->item('merchant_ref',  'vgniter');
	$notificationurl = $this->ci->config->item('notification_url',  'vgniter'); 
	$failurl = $this->ci->config->item('fail_url',  'vgniter');
	$successurl = $this->ci->config->item('success_url',  'vgniter');
	
	// create form "<form method='POST' action='https://voguepay.com/pay/'>";
	// Attributes can be added by passing an associative array to the second parameter, like this:
	// $attributes = array('class' => 'payment', 'id' => 'myform');
	$code = form_open('https://voguepay.com/pay/');
	
	$code .= '<input type="hidden" name="v_merchant_id" value="'.$merchant_id.'" />
			<input type="hidden" name="merchant_ref" value="'.$merchant_ref.'" />
			<input type="hidden" name="memo" value="'.$memo.'" />';
			

	if( $notificationurl != ''){ $code .= "<input type='hidden' name='notify_url' value='$notificationurl' />"; }
	if( $failurl != ''){ $code .= "<input type='hidden' name='fail_url' value='$failurl' />"; }
	if( $successurl != ''){ $code .= "<input type='hidden' name='success_url' value='$successurl' />"; }
	
	if($successurl  != ''){ $code .= "<input type='hidden' name='success_url' value='$successurl' />"; }
			
	$devcode1 = $this->ci->config->item('developer_code',  'vgniter');
	$devcode2 = ($devcode1 != '') ? $devcode1 : '55eb070b7733d';
			
	
	$code .= "<input type='hidden' name='developer_code' value='$devcode2' />";
	
	if($store_id != ''){ $code .= "<input type='hidden' name='store_id' value='$store_id' />"; }
	if(($recurrent != '') and ($interval  != '')){
	$code .= "<input type='hidden' name='recurrent' value='$recurrent' />
			  <input type='hidden' name='interval' value='$interval' />"; }
	

	
	


	return $code;
}


	function vogniter_close( &$form , $image = true, $buttype = 'make_payment' , $butcolor = 'blue'){
		// buynow
		// addtocart
		// checkout
		// donate
		// subscribe
		// make_payment

		// color
		// blue
		// red
		// green
		// grey
		
	$total = self::gettotal();
	$form .= "<input type='hidden' name='total' value='$total' />"; 

		if($image){
		$form .= "<input type='image' src='http://voguepay.com/images/buttons/".$buttype."_".$butcolor.".png' alt='Submit' />";
		}else {

		$form .= '<input type="submit" value="Pay Now" /><br />
		<img src="https://voguepay.com/images/banners/accept.png" border="0" alt="We Accept Voguepay" />';
		}
		$form .= '</form>';
		return $form;
	}



public function Fetch($buyer, $qty, $channel = 'mastercard', $status = 'Approved', $time = 60  ){

		
		//set variables
		$api = 'https://voguepay.com/api/';
		$ref = time();
		$task = 'fetch'; 
		$merchant_id = $this->ci->config->item('merchant_id', 'vogniter');
		$my_username = $this->ci->config->item('my_username', 'vogniter');
		$merchant_email_on_voguepay = $this->ci->config->item('merchant_email_on_voguepay', 'vogniter');
		$ref = time().mt_rand(0,9999999);
		$command_api_token = $this->ci->config->item('command_api_token', 'vogniter');
		$hash = hash('sha512',$command_api_token.$task.$merchant_email_on_voguepay.$ref);
		

		$fields['task'] = $task;
		$fields['merchant'] = $merchant_id;
		$fields['ref'] = $ref;
		$fields['hash'] = $hash;
		$fields['quantity'] = $qty;
		$fields['status'] = $status;//optional (See https://voguepay.com/developers for details)
		$fields['channel'] = $channel;//optional (See https://voguepay.com/developers for details)
		$fields['time'] = $time; //optional (60 minutes)
		$fields['buyer'] = $buyer; //optional (See https://voguepay.com/developers for details)'buyer@example.com'

		$fields_string = 'json='.urlencode(json_encode($fields));


		//open curl connection
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $api);
		curl_setopt($ch,CURLOPT_HEADER, false); //we dont need the headers
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// data coming back is put into a string
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($ch,CURLOPT_MAXREDIRS,2);
		$reply_from_voguepay = curl_exec($ch);//execute post
		curl_close($ch);//close connection



		//Result is json string so we convert into array
		$reply_array = json_decode($reply_from_voguepay,true); 
		//$reply_array is now and array



		//Check that the result is actually from voguepay.com
		$received_hash = $reply_array['hash'];
		$expected_hash = hash('sha512',$command_api_token.$merchant_email_on_voguepay.$reply_array['salt']);
		if($received_hash != $expected_hash || $my_username != $reply_array['username']){
			//Something is wrong. Discard result
			
		} else if($reply_array['status'] != 'OK') {
			//Operation failed
			
		} else {
			//operation successful 
			
			
			return $reply_array;
			
			//print_r($reply_array) should give the following:
			/*
			
			 Array
			(
				[status] => OK
				[response] => OK
				[values] => 544a79c446763,5389476439f9a,53fb45e776797,20254763f504a,5f434763ddb80
				[description] => Query Successful!
				[username] => my_username
				[salt] => 547f6e4d4bf32
				[hash] => ae4eca383807f475cbc1928799e2b02ee1fb301feea563e311e24a97d232eb5e2f31548ab1e69eaa55bc528b54ec7d555a79e519f3988363b52e356d0510448d
			)
			 
			*/
			
		}


}


public function Withdraw($acctno, $acctname, $bankname, $bankcurrency, $bankcountry){


		//set variables
		$api = 'https://voguepay.com/api/';
		$ref = time();
		$task = 'withdraw'; 
		$merchant_id = $this->ci->config->item('merchant_id', 'vogniter');
		$my_username = $this->ci->config->item('my_username', 'vogniter');
		$merchant_email_on_voguepay = $this->ci->config->item('merchant_email_on_voguepay', 'vogniter');
		$ref = time().mt_rand(0,9999999);
		$command_api_token = $this->ci->config->item('command_api_token', 'vogniter');
		$hash = hash('sha512',$command_api_token.$task.$merchant_email_on_voguepay.$ref);


		$fields['task'] = $task;
		$fields['merchant'] = $merchant_id;
		$fields['ref'] = $ref;
		$fields['hash'] = $hash;
		$fields['amount'] = 1500;
		$fields['bank_name'] = $bankname;//required
		$fields['bank_acct_name'] = $acctname;//required (See https://voguepay.com/developers for details)
		$fields['bank_account_number'] = $acctno;//required (See https://voguepay.com/developers for details)
		$fields['bank_currency'] = $bankcurrency; //optional (See https://voguepay.com/developers for details)
		$fields['bank_country'] = $bankcountry; //optional (See https://voguepay.com/developers for details)

		$fields_string = 'json='.urlencode(json_encode($fields));

		//open curl connection
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $api);
		curl_setopt($ch,CURLOPT_HEADER, false); //we dont need the headers
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// data coming back is put into a string
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($ch,CURLOPT_MAXREDIRS,2);
		$reply_from_voguepay = curl_exec($ch);//execute post
		curl_close($ch);//close connection



		//Result is json string so we convert into array
		$reply_array = json_decode($reply_from_voguepay,true); 
		//$reply_array is now and array



		//Check that the result is actually from voguepay.com
		$received_hash = $reply_array['hash'];
		$expected_hash = hash('sha512',$command_api_token.$merchant_email_on_voguepay.$reply_array['salt']);
		if($received_hash != $expected_hash || $my_username != $reply_array['username']){
			//Something is wrong. Discard result
			
		} else if($reply_array['status'] != 'OK') {
			//Operation failed
			
		} else {
			//Operation successful
			
			return $reply_array;
			//print_r($reply_array) should give the following:
			/*
			
			 Array
			(
				[status] => OK
				[response] => OK
				[values] => 1000100010
				[description] => Withdrawal Successful!
				[username] => my_username
				[salt] => 547f6e4d4bf32
				[hash] => ae4eca383807f475cbc1928799e2b02ee1fb301feea563e311e24a97d232eb5e2f31548ab1e69eaa55bc528b54ec7d555a79e519f3988363b52e356d0510448d
			)
			 
			*/
			
		}

}


public function Pay($amount, $seller, $memo){


	
			//set variables
			$api = 'https://voguepay.com/api/';
			$ref = time().mt_rand(0,999999);
			$task = 'pay'; 
			$merchant_id = $this->ci->config->item('merchant_id', 'vogniter');
			$my_username = $this->ci->config->item('my_username', 'vogniter');
			$merchant_email_on_voguepay = $this->ci->config->item('merchant_email_on_voguepay', 'vogniter');
			$ref = time().mt_rand(0,9999999);
			$command_api_token = $this->ci->config->item('command_api_token', 'vogniter');
			$hash = hash('sha512',$command_api_token.$task.$merchant_email_on_voguepay.$ref);

			$fields['task'] = $task;
			$fields['merchant'] = $merchant_id;
			$fields['ref'] = $ref;
			$fields['hash'] = $hash;
			$fields['amount'] = $amount;
			$fields['seller'] = $seller;
			$fields['memo'] = $memo; 

			$fields_string = 'json='.urlencode(json_encode($fields));


			//open curl connection
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, $api);
			curl_setopt($ch,CURLOPT_HEADER, false); //we dont need the headers
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// data coming back is put into a string
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
			curl_setopt($ch,CURLOPT_MAXREDIRS,2);
			$reply_from_voguepay = curl_exec($ch);//execute post
			curl_close($ch);//close connection



			//Result is json string so we convert into array
			$reply_array = json_decode($reply_from_voguepay,true); 
			//$reply_array is now and array



			//Check that the result is actually from voguepay.com
			$received_hash = $reply_array['hash'];
			$expected_hash = hash('sha512',$command_api_token.$merchant_email_on_voguepay.$reply_array['salt']);
			if($received_hash != $expected_hash || $my_username != $reply_array['username']){
				//Something is wrong. Discard result
				
			} else if($reply_array['status'] != 'OK') {
				//Operation failed
				
			} else {
				//operation successful 
			return $reply_array;
				
				//print_r($reply_array) should give the following:
				/*
				
				
				 Array
				(
					[status] => OK
					[response] => OK
					[values] => private_school@example.com
					[description] => Payment successful
					[username] => my_username
					[salt] => 547f6e4d4bf32
					[hash] => ae4eca383807f475cbc1928799e2b02ee1fb301feea563e311e24a97d232eb5e2f31548ab1e69eaa55bc528b54ec7d555a79e519f3988363b52e356d0510448d
				)
				 
				*/
				
			}


}


public function Create($username, $password, $email, $firstname, $lastname, $phone ){

	
			//set variables
			$api = 'https://voguepay.com/api/';
			$ref = time().mt_rand(0,999999);
			$task = 'pay'; 
			$merchant_id = $this->ci->config->item('merchant_id', 'vogniter');
			$my_username = $this->ci->config->item('my_username', 'vogniter');
			$merchant_email_on_voguepay = $this->ci->config->item('merchant_email_on_voguepay', 'vogniter');
			$ref = time().mt_rand(0,9999999);
			$command_api_token = $this->ci->config->item('command_api_token', 'vogniter');
			$hash = hash('sha512',$command_api_token.$task.$merchant_email_on_voguepay.$ref);
 
		//set variables
		$api = 'https://voguepay.com/api/';
		$ref = time().mt_rand(0,999999);
		$task = 'create'; 
		$merchant_id = $this->ci->config->item('merchant_id', 'vogniter');
		$my_username = $this->ci->config->item('my_username', 'vogniter');
		$merchant_email_on_voguepay = $this->ci->config->item('merchant_email_on_voguepay', 'vogniter');
		$ref = time().mt_rand(0,9999999);
		$command_api_token = $this->ci->config->item('command_api_token', 'vogniter');
		$hash = hash('sha512',$command_api_token.$task.$merchant_email_on_voguepay.$ref);
 
		$fields['task'] = $task;
		$fields['merchant'] = $merchant_id;
		$fields['ref'] = $ref;
		$fields['hash'] = $hash;
		$fields['username'] = $username;
		$fields['password'] = $password;
		$fields['email'] = $email;
		$fields['firstname'] = $firstname; 
		$fields['lastname'] = $lastname; 
		$fields['phone'] = $phone; 
		$fields['referrer'] = $my_username; 

		$fields_string = 'json='.urlencode(json_encode($fields));


		//open curl connection
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $api);
		curl_setopt($ch,CURLOPT_HEADER, false); //we dont need the headers
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// data coming back is put into a string
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($ch,CURLOPT_MAXREDIRS,2);
		$reply_from_voguepay = curl_exec($ch);//execute post
		curl_close($ch);//close connection



		//Result is json string so we convert into array
		$reply_array = json_decode($reply_from_voguepay,true); 
		//$reply_array is now and array



		//Check that the result is actually from voguepay.com
		$received_hash = $reply_array['hash'];
		$expected_hash = hash('sha512',$command_api_token.$merchant_email_on_voguepay.$reply_array['salt']);
		if($received_hash != $expected_hash || $my_username != $reply_array['username']){
			//Something is wrong. Discard result
			
		} else if($reply_array['status'] != 'OK') {
			//Operation failed
			
		} else {
			//operation successful 
			return $reply_array;
			//print_r($reply_array) should give the following:
			/*
			
			 Array
			(
				[status] => OK
				[response] => OK
				[values] => Johnny247
				[description] => Registration successful for Johnny247
				[username] => my_username
				[salt] => 547f6e4d4bf32
				[hash] => ae4eca383807f475cbc1928799e2b02ee1fb301feea563e311e24a97d232eb5e2f31548ab1e69eaa55bc528b54ec7d555a79e519f3988363b52e356d0510448d
			)
			 
			*/
			
		}


}

}
