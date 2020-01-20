<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'document_root' => EASYSWOOLE_ROOT . '/Static',
            'max_wait_time'=>3,
            'enable_static_handler' => true,
            'heartbeat_check_interval' => 30,    //心跳检测间隔（30s）
            'heartbeat_idle_time' => 60,        //心跳超时（60s）
        ],
        'TASK'=>[
            'workerNum'=>4,
            'maxRunningNum'=>128,
            'timeout'=>15,
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'MYSQL'  => [
        'host'          => '192.168.1.200',
        'port'          => 3306,
        'user'          => 'root',
        'password'      => '123456',
        'database'      => 'im',
        'timeout'       => 5,
        'charset'       => 'utf8mb4',
        'POOL_MAX_NUM' => '6',
        'POOL_TIME_OUT' => '0.1'
    ],
    'REDIS'         => [
        'host'          => '192.168.1.200',
        'port'          => '6379',
        'auth'          => '',
        'POOL_MAX_NUM'  => '6',
        'POOL_TIME_OUT' => '0.1',
    ],
    'HTTP_HOST' => 'http://192.168.1.200:9501',
    'RPC_SERVER' => [
        'ip' => '192.168.1.200',
        'port' => 9700,
        'secret_key' => 'asdf25l3k4j0987fl(&#^^@345jjalsfh254809#*#&5akf',
    ],
    'SIGN_KEY' => 'asdlfk#*(@(Y&df2348ugslfh3409fldsk#$@&*sdf',
    'GRPC_SERVER' => [
        'ip' => '192.168.1.244',
        'port' => 19007,
    ],
    'SENTRY' => [
        'dsn' => 'http://6ac48f6ba2f8483da762d28c00fb0366@192.168.1.200:9000/1'
    ]
];
