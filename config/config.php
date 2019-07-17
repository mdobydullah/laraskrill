<?php
/**
 * LaraSkrill Configuration
 * Author: Md. Obydullah <obydul@makpie.com>.
 * Author URL: https://obydul.me
 */

return [
    'merchant_email' => 'demoqco@sun-fish.com',
    'api_password' => 'MD5 API/MQI password', // required for refund option only.
    'return_url' => 'RETURN URL',
    'cancel_url' => 'CANCEL URL',
    'status_url' => 'IPN URL or Email', // url or email
    'status_url2' => 'IPN URL or Email', // url or email (you can keep this blank)
    'refund_status_url' => 'IPN URL or Email', // url or email (only for refund, you can keep this blank)
    'logo_url' => 'WEBSITE LOGO',
];
