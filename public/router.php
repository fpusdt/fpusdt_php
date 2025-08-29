<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz
 * 温馨提示：接受各种代码定制
 */


if (is_file($_SERVER["DOCUMENT_ROOT"] . $_SERVER["SCRIPT_NAME"])) {
    return false;
} else {
    if (!isset($_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
    }
    require __DIR__ . "/index.php";
}
