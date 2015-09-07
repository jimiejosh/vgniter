## Vgniter - Codeigniter -Voguepay Payment Library
	library to integrate Voguepay Payment Gateway API on codeigniter

## Class Features

- Pay with voguepay!
- Access comand api to create user, pay, fetch e.t.c
- Add auto generated items 
- Light weight
- Support country and currency
- Need no instal and has no database, just copy files to the right folder
- Can be integrated to any Codeigniter application
- Tested with codeigniter version 3.0 but backward compactible with version 2.0
- Compatible with PHP 5.0 and later
- Much more!

## Why you might need it

 To manually integrate VoguePay into your website. When you're done, you will have added a VoguePay button and supporting code to your website so that customers can click to place orders through VoguePay.

## License

This software is distributed under the MIT license. Please read LICENSE.txt for information on the
software availability and distribution.

## Installation & loading



    Drop the provided files into the CodeIgniter project
    Configure your vougepay details inside the application/config/vgniter.php file. refer to http://voguepay.com/developer
    Modify the controller example supplied (application/controller/vgniter.php) to fit your needs

	
## A Simple Example

  To use Vgniter load the library in your controller
```php
  $this->load->library('Vgniter_lib');
```

   To add 
   Initiate the library with
```php
  //voguepay_start( memo , currency, store_id, recurrent, interval, demo);
  $form = $this->vgniter_lib->voguepay_start(1000,'','','','','','demo');
``` 
	To add items simply use
```php
  //voguepay_add_item( $form, name of item ,  description for the item, price of the item);
  $form = $this->vgniter_lib->$voguepay_add_item( &$form, 'Face Cap',  'beautiful facecap for use', 1000);
```
	Dont forget to close the form variable
	// fuction apends item total, submit button, and closes form
```php
  //vogniter_close( &$form , image, 'make_payment' , butcolor )
  $outputform = vogniter_close( &$form , true,  'make_payment' , 'blue');
  echo $outputform;
```


		View sample controller code below
```php
<?php
 	
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
	
}	
	
```

You'll find it easy to implement.

That's it. You should now be ready to use Vgniter!

## Localization
Vgniter defaults to English
