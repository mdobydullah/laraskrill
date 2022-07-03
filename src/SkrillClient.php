<?php

namespace Obydul\LaraSkrill;

use Illuminate\Support\Facades\Http;

class SkrillClient
{
    /** Skrill payment and refund URL */
    const PAY_URL = 'https://pay.skrill.com';
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
        if ($request != null) $this->request = $request;
        else echo "<h2>Exception, you need to set SkrillRequest!</h2><br>";
    }

    /**
     * Generate SID
     */
    public function generateSID()
    {
        // send request to skrill
        return Http::withoutVerifying()
            ->withOptions(["verify" => false])
            ->post(self::PAY_URL, $this->request->toArray())
            ->body();
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
        return self::PAY_URL . "?sid={$this->sid}";
    }

    /**
     * Prepare for full and partial refund
     * @return bool|string
     */
    public function prepareRefund()
    {
        // add required refund fields
        $this->request->action = 'prepare';

        return Http::withoutVerifying()
            ->withOptions(["verify" => false])
            ->post(self::REFUND_URL, $this->request->toArray())
            ->body();
    }

    /**
     * Do full and partial refund
     * @return bool|string
     */
    public function doRefund()
    {
        // add action
        $this->request->action = 'refund';

        return Http::withoutVerifying()
            ->withOptions(["verify" => false])
            ->post(self::REFUND_URL, $this->request->toArray())
            ->body();
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
