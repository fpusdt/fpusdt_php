<?php

/**
 * Date: 2023年12月18日13:46:27
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz

 * 温馨提示：接受各种代码定制
 */

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'demo'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view'],
        'controller' => ['Index', 'Test', 'UserType'],
        'model'      => ['User', 'UserType'],
        'view'       => ['index/index'],
    ],
    // 其他更多的模块定义
];
