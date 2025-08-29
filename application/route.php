<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz

 * 温馨提示：接受各种代码定制
 */


return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

    // API v1 路由 - 将 v1/* 映射到 api/index/*
    'v1/createAddress' => 'api/index/createAddress',
    'v1/generateAddressWithMnemonic' => 'api/index/generateAddressWithMnemonic',
    'v1/getAddressByKey' => 'api/index/getAddressByKey',
    'v1/getTrxBalance' => 'api/index/getTrxBalance',
    'v1/getTrc20Balance' => 'api/index/getTrc20Balance',
    'v1/getTrc10Info' => 'api/index/getTrc10Info',
    'v1/sendTrx' => 'api/index/sendTrx',
    'v1/sendTrc20' => 'api/index/sendTrc20',
    'v1/sendTrc10' => 'api/index/sendTrc10',
    'v1/getTransaction' => 'api/index/getTransaction',
    'v1/getTrc20TransactionReceipt' => 'api/index/getTrc20TransactionReceipt',
    'v1/getBlockHeight' => 'api/index/getBlockHeight',
    'v1/getBlockByNumber' => 'api/index/getBlockByNumber',
    'v1/status' => 'api/index/status',
    'v1/getApiList' => 'api/index/getApiList',

    // 文档路由 - 将 /doc 映射到 index/docs/index
    'doc' => 'index/docs/index',

];
