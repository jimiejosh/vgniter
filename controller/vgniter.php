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
	
	
class Vgniter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('Vgniter_lib');  
		 
	}
	
	function index()
	{

	// create form with default values set in config i.e merchant_ref, merchant_id, e.t.c
	// and also add <form method='POST' action='https://voguepay.com/pay/'>
	$form = $this->vgniter_lib->voguepay_start(1000,'','','','','','demo');
	
	// function to add item you want to sell to form including item name, item descriptio
	// and item price, it will automatically generate total for all items
	$form = $this->vgniter_lib->voguepay_add_item( &$form, 'Face Cap',  'beautiful facecap for use', 1000);
	$form = $this->vgniter_lib->voguepay_add_item( &$form, 'Laban T-shirt', $desc = 'Labeled T-shirts', 4500);
	$finalform = $this->vgniter_lib->vogniter_close( $form , true, 'make_payment' ,  'blue');
	
		echo $finalform;
	}
	
	function notify()
	{

	if(isset($_POST['transaction_id'])){
	//get the full transaction details as an json from voguepay
	$json = file_get_contents('https://voguepay.com/?v_transaction_id='.$_POST['transaction_id'].'&type=json');
	//create new array to store our transaction detail
	$transaction = json_decode($json, true);
	
	/*
	Now we have the following keys in our $transaction array
	$transaction['merchant_id'],
	$transaction['transaction_id'],
	$transaction['email'],
	$transaction['total'], 
	$transaction['merchant_ref'], 
	$transaction['memo'],
	$transaction['status'],
	$transaction['date'],
	$transaction['referrer'],
	$transaction['method']
	*/
	
	if($transaction['total'] == 0)die('Invalid total');
	if($transaction['status'] != 'Approved'){ 
	
	redirect(base_url('vogniter/failed'));
	
	}
	
	/*You can do anything you want now with the transaction details or the merchant reference.
	You should query your database with the merchant reference and fetch the records you saved for this transaction.
	Then you should compare the $transaction['total'] with the total from your database.*/
	}
	}
	
	function failed()
	{
	
	
	}
	
	
	
	}
?>
