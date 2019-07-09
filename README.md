# LaraSkrill
[![Latest Stable Version](https://poser.pugx.org/obydul/laraskrill/v/stable)](https://packagist.org/packages/obydul/laraskrill)
[![Total Downloads](https://poser.pugx.org/obydul/laraskrill/downloads)](https://packagist.org/packages/obydul/laraskrill)
[![Latest Unstable Version](https://poser.pugx.org/obydul/laraskrill/v/unstable)](https://packagist.org/packages/obydul/laraskrill)
[![License](https://poser.pugx.org/obydul/laraskrill/license)](https://packagist.org/packages/obydul/laraskrill)
<a name="introduction"></a>
## Introduction

By using this plugin you can process or refund payments from Skrill in your Laravel application. You may read this article and can see the output of this package. Article link: [Laravel Skrill Payment Gateway Integration with LaraSkrill](https://www.mynotepaper.com/laravel-skrill-payment-gateway-integration-with-laraskrill.html)

<a name="installation"></a>
## Installation

* Use the following command to install:

```bash
composer require obydul/laraskrill
```

* Add the service provider to your `$providers` array in `config/app.php` file like: 

```php
Obydul\LaraSkrill\LaraSkrillServiceProvider::class
```

* Run the following command to publish configuration:

```bash
php artisan vendor:publish
```
*  Then choose 'Obydul\LaraSkrill\LaraSkrillServiceProvider':

![laraskrill-publish](https://user-images.githubusercontent.com/13184472/54486163-67883580-48ae-11e9-815a-b3b043b5572a.png)

Installation completed.

<a name="configuration"></a>
## Configuration

* After installation, you will need to configure laraskrill. Following is the code you will find in **config/laraskrill.php**, which you should update accordingly.

```php
return [
    'merchant_email' => 'demoqco@sun-fish.com',
    'api_password' => 'MD5 API/MQI password', // required for refund option only.
    'return_url' => 'RETURN URL',
    'cancel_url' => 'CANCEL URL',
    'status_url' => 'IPN URL or Email', // url or email
    'refund_status_url' => 'IPN URL or Email', // url or email
    'logo_url' => 'WEBSITE LOGO',
];
```
* Now clear config cache: `php artisan config:cache`. You can also clear cache: `php artisan cache:clear`.
* Still if there is a problem in your Laravel project, the config `config/laraskrill.php` may not work. At this situation you can try by entering API Credentials at `YourProject/vendor/obydul/laraskrill/config/config.php`.

#### API/MQI password
In your Skrill account, go to Settings > Developer Settings > Change MQI/API password.

![API/MQI password](https://user-images.githubusercontent.com/13184472/54486371-a4096080-48b1-11e9-897f-30cb9b2bf0b0.png)

<a name="usage"></a>
## Usage

Following are some ways through which you can access the LaraSkrill provider:

```php
// Import the class namespaces first, before using it directly
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;

// Create object instance
$request = new SkrillRequest();
$client = new SkrillClient($request);

// Methods
$sid = $client->generateSID();
$client->paymentRedirectUrl($sid);
$refund_prepare_response = $client->prepareRefund();
$do_refund = $client->doRefund();
```

#### Make a Payment
```php
// Create object instance of SkrillRequest
$request = new SkrillRequest();
$request->transaction_id      = 'MNPSK09789'; // generate transaction id
$request->amount              = '15.26';
$request->currency            = 'EUR';
$request->language            = 'EN';
$request->prepare_only        = '1';
$request->merchant_fields     = 'site_name, customer_email';
$request->site_name           = 'Your Website';
$request->customer_email      = 'customer@example.com';
$request->detail1_description = 'Product ID:';
$request->detail1_text        = '10001';

// Create object instance of SkrillClient
$client = new SkrillClient($request);
$sid = $client->generateSID(); //return SESSION ID

// handle error
$jsonSID = json_decode($sid);
if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
    return $jsonSID->message;

// do the payment
$redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
return Redirect::to($redirectUrl); // redirect user to Skrill payment page
```

#### Refund
```php
// Create object instance of SkrillRequest
$prepare_refund_request = new SkrillRequest();
$prepare_refund_request->transaction_id    = 'MNPSK09789';
$prepare_refund_request->amount            = '5.56';
$prepare_refund_request->refund_note       = 'Product no longer in stock';
$prepare_refund_request->merchant_fields   = 'site_name, customer_email';
$prepare_refund_request->site_name         = 'Your Website';
$prepare_refund_request->customer_email    = 'customer@example.com';

// do prepare refund request
$client_prepare_refund = new SkrillClient($prepare_refund_request);
$refund_prepare_response = $client_prepare_refund->prepareRefund(); // return sid or error code

// refund requests
$refund_request = new SkrillRequest();
$refund_request->sid = $refund_prepare_response;

// do refund
$client_refund = new SkrillClient($refund_request);
$do_refund = $client_refund->doRefund();
var_dump($do_refund); // display response
```

<a name="note"></a>
## Note

#### Table 1: LaraSkrill Config Parameters

| Field | Description | Example |
| --- | --- | --- |
| merchant_email | Email address of your Skrill merchant account. | demoqco@sun-fish.com |
| api_password | Your MD5 API/MQI password. | 60cede4a5aee9a3827f212ba45f88c61
| return_url |  URL to which the customer is returned if the payment is successful. | http://example.com/payment_completed.html |
| cancel_url |  URL to which the customer is returned if the payment is cancelled or fails. If no cancel URL is provided the Cancel button is not displayed. | http://example.com/payment_cancelled.html |
| status_url, refund_status_url | URL to which the transaction details are posted after the payment process is complete. Alternatively, you may specify an email address where the results are sent. If the status_url is omitted, no transaction details are sent | http://example.com/process_payment.php or mailto:info@example.com
| logo_url |  The URL of the logo which you would like to appear in the top right of the Skrill page. The logo must be accessible via HTTPS or it will not be shown. | https://www.example.com/logo.jpg (max length: 240) |

####  Checkout Parameters
There are many parameters of Skrill checkout. Please take a look at the page 13. [Skrill Quick Checkout Integration Guide -  v7.9](https://www.skrill.com/fileadmin/content/pdf/Skrill_Quick_Checkout_Guide.pdf)

`Note:` 'pay_to_email', 'return_url', 'cancel_url', 'status_url' and 'logo_url' are already included in the config file. You can add other fields at checkout without these fields.

#### Table 2: Refund Parameters

| Field | Description | Required | Example
| --- | --- | --- | --- |
| transaction_id | Your transaction ID to be refunded. | Yes | MNPSK09789
| amount | Amount to refund in the currency used by the merchant account. This field is only used for partial refunds. | No | 5.56
| refund_note | Refund note sent to the customer. This note forms part of the email sent to the customer to inform them that they have received a refund. If no ‘amount’ value is submitted, the refund will be for the full amount of the original transaction. | No | Product no longer in stock
| merchant_fields | A comma-separated list of field names that are passed back to your server when the refund payment is confirmed (maximum 5 fields). | No |  Field1,Field2
| Field1 | An additional field you can include, containing your own unique parameters. | No |  Value1
| Field2 | An additional field you can include, containing your own unique parameters. | No |  Value2

More parameters: You can add more fields. Please take a look at the page 24. [Skrill Automated Payments Interface (API) Guide - v2.8](https://www.skrill.com/fileadmin/content/pdf/Skrill_Automated_Payments_Interface_Guide.pdf)

`Note:` 'action', 'email', 'password', 'status_url' are already included. You can add other fields at refund without these fields.

**Skrill IPN (status_url):** If you want to get data from 'status_url' instead of receiving email, then use this code to your ipn listener: [Skrill IPN by Md. Obydullah](https://gist.github.com/mdobydullah/8b0399c5c6368c05d98239837a20fb19)

<a name="information"></a>
## Information
- [Skrill Quick Checkout Integration Guide](https://www.skrill.com/fileadmin/content/pdf/Skrill_Quick_Checkout_Guide.pdf) - version 7.9
- [Skrill Automated Payments Interface (API) Guide](https://www.skrill.com/fileadmin/content/pdf/Skrill_Automated_Payments_Interface_Guide.pdf) -  version 2.8
- Skrill test merchant email: demoqco@sun-fish.com, demoqcoflexible@sun-fish.com, demoqcofixedhh@sun-fish.com
- MQI/API password and secret word: **mqi: skrill123, secretword: skrill**
- Skrill test card numbers: VISA: **4000001234567890** | MASTERCARD: **5438311234567890** | AMEX: **371234500012340**

<a name="license"></a>
## License

The MIT License (MIT). Please see [License File](https://github.com/mdobydullah/laraskrill/blob/master/LICENSE) for more information.

<a name="others"></a>
## Others
`Note:` I've taken the main concept from [skrill-quick](https://github.com/mikicaivosevic/skrill-quick) and thank you, [Mikica Ivosevic](https://github.com/mikicaivosevic).

In case of any issues, kindly create one on the [Issues](https://github.com/mdobydullah/laraskrill/issues) section.

Thank you for installing LaraSkrill.
