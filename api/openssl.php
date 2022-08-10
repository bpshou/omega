<?php

namespace api;

use origin\Request;

class openssl extends Base
{
    /**
     * openssl
     * @return json
     */
    public function service()
    {
        $Request = new Request();
        $action = data_get($Request->get, 'action', '');
        $content = data_get($Request->get, 'content', '');
        $content = $this->openssl($action, $content);
        $this->json(200, [
            'action' => $action,
            'content' => $content,
        ]);
    }

    /**
     * openssl
     * @return json
     */
    public function openssl($action, $content)
    {
        $iv = '10000000000000000000000000000000';
        $key = 'origin123456';
        $action = $action == 'encrypt' ? '' : '-d';
        $command = sprintf("echo %s | openssl enc -e -aes-256-cbc -a -nosalt -pbkdf2 -iv %s -k %s %s", $content, $iv, $key, $action);
        return $command;
        system($command,$return);
    }
}
