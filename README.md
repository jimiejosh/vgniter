## Vgniter - Codeigniter -Voguepay Payment Library
	library to integrate Voguepay Payment Gateway API on codeigniter

## Class Features

- Pay with vogupay!
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
    Modify the controller example supplied (application/controller/hauth.php) to fit your needs

	
## A Simple Example

  TO use Vgniter load the library in your controller
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
  //$voguepay_add_item( $form, name of item ,  description for the item, price of the item);
  $form = $this->vgniter_lib->$voguepay_add_item( &$form, 'Face Cap',  'beautiful facecap for use', 1000);
```
	Dont forget to close the form variable
	// fuction apends item total, submit button, and closes form
```php
  //$voguepay_add_item( $form, name of item ,  description for the item, price of the item);
  $form = $this->vgniter_lib->$voguepay_add_item( &$form, 'Face Cap',  'beautiful facecap for use', 1000);
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
	
```

You'll find it easy to implement.

That's it. You should now be ready to use PHPMailer!

## Localization
Vgniter defaults to English


## Documentation

Examples of how to use PHPMailer for common scenarios can be found in the [examples](examples/) folder. If you're looking for a good starting point, we recommend you start with [the gmail example](examples/gmail.phps).

There are tips and a troubleshooting guide in the [GitHub wiki](https://github.com/PHPMailer/PHPMailer/wiki). If you're having trouble, this should be the first place you look as it's the most frequently updated.

Complete generated API documentation is [available online](http://phpmailer.github.io/PHPMailer/).

You'll find some basic user-level docs in the [docs](docs/) folder, and you can generate complete API-level documentation using the [generatedocs.sh](docs/generatedocs.sh) shell script in the docs folder, though you'll need to install [PHPDocumentor](http://www.phpdoc.org) first. You may find [the unit tests](test/phpmailerTest.php) a good source of how to do various operations such as encryption.

If the documentation doesn't cover what you need, search the [many questions on StackOverflow](http://stackoverflow.com/questions/tagged/phpmailer), and before you ask a question about "SMTP Error: Could not connect to SMTP host.", [read the troubleshooting guide](https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting).

## Tests

There is a PHPUnit test script in the [test](test/) folder.

Build status: [![Build Status](https://travis-ci.org/PHPMailer/PHPMailer.svg)](https://travis-ci.org/PHPMailer/PHPMailer)

If this isn't passing, is there something you can do to help?

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/PHPMailer/PHPMailer/issues).

We're particularly interested in fixing edge-cases, expanding test coverage and updating translations.

With the move to the PHPMailer GitHub organisation, you'll need to update any remote URLs referencing the old GitHub location with a command like this from within your clone:

```sh
git remote set-url upstream https://github.com/PHPMailer/PHPMailer.git
```

Please *don't* use the SourceForge or Google Code projects any more.

## Sponsorship

Development time and resources for PHPMailer are provided by [Smartmessages.net](https://info.smartmessages.net/), a powerful email marketing system.

<a href="https://info.smartmessages.net/"><img src="https://www.smartmessages.net/img/smartmessages-logo.svg" width="250" height="28" alt="Smartmessages email marketing"></a>

Other contributions are gladly received, whether in beer ðŸº, T-shirts ðŸ‘•, Amazon wishlist raids, or cold, hard cash ðŸ’°.

## Changelog

See [changelog](changelog.md).

## History
- PHPMailer was originally written in 2001 by Brent R. Matzelle as a [SourceForge project](http://sourceforge.net/projects/phpmailer/).
- Marcus Bointon (coolbru on SF) and Andy Prevost (codeworxtech) took over the project in 2004.
- Became an Apache incubator project on Google Code in 2010, managed by Jim Jagielski.
- Marcus created his fork on [GitHub](https://github.com/Synchro/PHPMailer).
- Jim and Marcus decide to join forces and use GitHub as the canonical and official repo for PHPMailer.
- PHPMailer moves to the [PHPMailer organisation](https://github.com/PHPMailer) on GitHub.

### What's changed since moving from SourceForge?
- Official successor to the SourceForge and Google Code projects.
- Test suite.
- Continuous integration with Travis-CI.
- Composer support.
- Public development.
- Additional languages and language strings.
- CRAM-MD5 authentication support.
- Preserves full repo history of authors, commits and branches from the original SourceForge project.
