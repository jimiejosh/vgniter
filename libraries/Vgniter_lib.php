<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*       The MIT License (MIT)

	*	Copyright (c) 2015 Folajimi Seye <joshjimie@gmail.com>
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
	public $form = "";
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
	
	
	function voguepay_add_item( $item = 'item', $desc = 'item description', $price = '')
	{
			// <input type='hidden' name='item_1' value='Face Cap' />
		// <input type='hidden' name='description_1' value='Blue Zizi facecap' />
		// <input type='hidden' name='price_1' value='2000' />
		 $arr = "";
	 $unq = self::doforminc();
		$itemn = 'item_'.$unq;
		$descn = 'description_'.$unq;
		$pricen = 'price_'.$unq;
		
		 $arr .= form_hidden($itemn, $item);
		 $arr .= form_hidden($descn, $desc);
		 $arr .= form_hidden($pricen, $price);
		 self::dototal($price);
		 $this->form .= $arr;
	}
	
	
	
	function voguepay_start( $memo = 'Secure payment by Voguepay.com',  $cur = 'NGN', $store_id = null, $recurrent =null, $interval = null, $demo = null) {


	$merchant_id = $this->ci->config->item('merchant_id', 'vgniter');
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
	

	
	


	$this->form .= $code;
}


	function vogniter_close( $image = true, $buttype = 'make_payment' , $butcolor = 'blue'){
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
		$form = "";
		
	$total = self::gettotal();
	$form .= "<input type='hidden' name='total' value='$total' />"; 

		if($image){
		$form .= "<input type='image' src='http://voguepay.com/images/buttons/".$buttype."_".$butcolor.".png' alt='Submit' />";
		}else {

		$form .= '<input type="submit" value="Pay Now" /><br />
		<img src="https://voguepay.com/images/banners/accept.png" border="0" alt="We Accept Voguepay" />';
		}
		$form .= '</form>';
	$this->form .= $form;
		return $this->form;
	}
 

}
