<?php

namespace Obydul\LaraSkrill;

class SkrillClient
{
    /** Skrill payment and refund URL */
    const APP_URL = 'https://pay.skrill.com';
    const REFUND_URL = 'https://www.skrill.com/app/refund.pl';

    /** @var  SkrillRequest $request */
    private $request;
    /** @var  string $sid */
    private $sid;

    /**
     * SkrillClient constructor.
     * @param SkrillRequest $request
     */
    public function __construct(SkrillRequest $request = null)
    {
        if ($request != null)
            $this->request = $request;
        else
            echo "<h2>Exception, you need to set SkrillRequest!</h2><br><br>";
    }

    /**
     * Skrill SID
     * generate SID
     */
    public function generateSID()
    {
        // add required refund fields
        $this->request->pay_to_email = config('laraskrill.merchant_email');
        $this->request->return_url = config('laraskrill.return_url');
        $this->request->cancel_url = config('laraskrill.cancel_url');
        $this->request->logo_url = config('laraskrill.logo_url');
        $this->request->status_url = config('laraskrill.status_url');

        // check status_url2
        $status_url2 = config('laraskrill.status_url2');
        if (isset($status_url2) && $status_url2 != null)
            $this->request->status_url2 = config('laraskrill.status_url2');

        $ch = curl_init(self::APP_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); //
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->toArray());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return $response;
    }


    /**
     * Skrill Payment Redirect Url
     * generate SID and generate full payment URL
     */
    public function paymentRedirectUrl($sid = null)
    {
        $this->sid = $sid;
        if (!$this->sid) {
            $this->sid = $this->generateSID();
        }
        return self::APP_URL . "?sid={$this->sid}";
    }

    /**
     * Skrill Prepare Refund
     * prepare for full and partial refund
     *
     * @return resource
     */
    public function prepareRefund()
    {
        // add required refund fields
        $this->request->action = 'prepare';
        $this->request->email = config('laraskrill.merchant_email');
        $this->request->password = config('laraskrill.api_password');
        $this->request->refund_status_url = config('laraskrill.refund_status_url');

        $ch = curl_init(self::REFUND_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); //
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->toArray());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return $response;
    }

    /**
     * Skrill Refund
     * full and partial refund
     *
     * @return resource
     */
    public function doRefund()
    {
        // add action
        $this->request->action = 'refund';

        $ch = curl_init(self::REFUND_URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); //
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // -0
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->toArray());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        return $response;
    }

    /**
     * @return SkrillRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param SkrillRequest $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
}
