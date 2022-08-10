<?php

namespace api;

use origin\Request;

class api extends Base
{
    /**
     * api
     * @return json
     */
    public function service()
    {
        $Request = new Request();
        $this->json(200, [
            'api' => 'success',
            'get' => $Request->get,
            'POST' => $Request->post,
        ]);
    }
}
