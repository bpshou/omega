<?php

namespace router;

use origin\Request;

class app
{
    /**
     * 路由入口
     * @return void
     */
    public function run()
    {
        $request = new Request();
        if (!isset($request->get['s'])) {
            exit('service error');
        }
        $service = str_replace('/', '\\', $request->get['s']);
        new $service();
    }
}
