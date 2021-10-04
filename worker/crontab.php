<?php

require_once __DIR__ . '/../autoload.php';

use Workerman\Worker;
use worker\MicroTimer;
use worker\LinuxCrontab;
use origin\Log;

// 设置时区，避免运行结果与预期不一致
date_default_timezone_set('PRC');
// 日志
Log::instance('work', __DIR__ . '/../../logs/omega_worker.log', 100);

$worker = new Worker();
// 4 processes
$worker->count = 4;

$worker->onWorkerStart = function($worker) {
    // 5个进程，每个进程都有一个这样的定时器
    (new MicroTimer)->timerList($worker->id);
    (new LinuxCrontab)->crontabList($worker->id);
};

// Run worker
Worker::runAll();
