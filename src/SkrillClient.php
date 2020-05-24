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
            echo "<h2>Exception, you need to set SkrillRequest!</h2><br>";
    }

    /**
     * Generate SID
     */
    public function generateSID()
    {
        // send request to skrill
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
     * Generate payment URL
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
     * Prepare for full and partial refund
     * @return bool|string
     */
    public function prepareRefund()
    {
        // add required refund fields
        $this->request->action = 'prepare';

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
     * Do full and partial refund
     * @return bool|string
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
