<?php

namespace api;

class api extends Base
{
    /**
     * api
     * @return json
     */
    public function service()
    {
        $this->json(200, ['api' => 'success']);
    }
}
