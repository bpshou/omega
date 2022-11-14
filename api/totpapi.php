<?php

namespace api;

use origin\Request;
use OTPHP\TOTP;

class totpapi extends Base
{
    /**
     * api
     * @return json
     */
    public function service()
    {
        $Request = new Request();

        $secret = $Request->post['google'];
        $otp = TOTP::create($secret);
        $result = $otp->now();

        $secret = $Request->post['google'];
        $otp = TOTP::create($secret, 60);
        $result_60 = $otp->now();

        $this->json(200, [
            'api' => 'success',
            'result' => $result,
            'result_60' => $result_60,
        ]);
    }
}
