<?php

/**
 * TRON区块链API接口控制器 - 整合版本
 *
 * 功能说明：
 * - 支持TRC10代币操作
 * - 支持TRC20代币操作（包括USDT）
 * - 支持TRX原生代币操作
 * - 支持助记词生成地址
 * - 支持区块链查询功能
 *
 * 作者：纸飞机(Telegram): https://t.me/king_orz
 * 日期：2025年8月28日
 *
 * 温馨提示：接受各种代码定制
 */

namespace app\api\controller;

use GuzzleHttp\Client;
use think\Controller;
use think\Request;
use Tron\Address;
use IEXBase\TronAPI\TronAwareTrait;
use BitWasp\Bitcoin\Key\Factory\HierarchicalKeyFactory;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39SeedGenerator;
use Web3p\EthereumUtil\Util;
use BitWasp\Bitcoin\Crypto\Random\Random;
use BitWasp\Bitcoin\Mnemonic\Bip39\Bip39Mnemonic;
use BitWasp\Bitcoin\Mnemonic\MnemonicFactory;

include_once VENDOR_PATH . 'autoload.php';

class Index extends Controller
{
    use TronAwareTrait;

    protected $config;
    protected $uri;
    protected $privateKey;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        // TRON API 地址
        $this->uri = 'https://api.trongrid.io';

        // 基础配置
        $this->config = [
            'contract_address' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', // USDT TRC20 合约地址（固定）
            'decimals' => 6, // USDT 精度
        ];
    }

    // ==================== 地址生成相关接口 ====================

    /**
     * 生成TRON地址（简单版本）
     *
     * 接口地址：/api/index/createAddress
     * 请求方式：GET/POST
     *
     * @return \think\response\Json
     */
    public function createAddress()
    {
        try {
            $tron = new \IEXBase\TronAPI\Tron();
            $generated = $tron->generateAddress();
            $addressData = (array)$generated->getRawData();

            return json([
                'code' => 1,
                'msg' => '地址生成成功',
                'data' => [
                    'privateKey' => $addressData['private_key'],
                    'address' => $addressData['address_base58'],
                    'hexAddress' => $addressData['address_hex'],
                ],
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '地址生成失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 通过助记词生成TRON地址
     *
     * 接口地址：/api/index/generateAddressWithMnemonic
     * 请求方式：GET/POST
     *
     * @return \think\response\Json
     */
    public function generateAddressWithMnemonic()
    {
        try {
            // 生成随机熵值
            $random = new Random();
            $entropy = $random->bytes(Bip39Mnemonic::MIN_ENTROPY_BYTE_LEN);
            $bip39 = MnemonicFactory::bip39();

            // 生成助记词
            $mnemonic = $bip39->entropyToMnemonic($entropy);

            // 通过助记词生成地址
            $address = $this->mnemonicToTronAddress($mnemonic);
            $address['mnemonic'] = $mnemonic;

            // 获取十六进制地址
            $tron = new \IEXBase\TronAPI\Tron();
            $hexAddress = $tron->address2HexString($address['address']);
            $address['hexAddress'] = $hexAddress;

            return json([
                'code' => 1,
                'msg' => '助记词地址生成成功',
                'data' => $address,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '助记词地址生成失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 根据私钥获取地址信息
     *
     * 接口地址：/api/index/getAddressByKey
     * 请求方式：GET/POST
     * 参数：privateKey - 私钥
     *
     * @return \think\response\Json
     */
    public function getAddressByKey()
    {
        $privateKey = $this->request->param("privateKey", "");

        if (!$privateKey) {
            return json([
                'code' => 0,
                'msg' => '私钥不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);
            $addressInfo = $TRC20->privateKeyToAddress($privateKey);

            return json([
                'code' => 1,
                'msg' => '获取地址成功',
                'data' => $addressInfo,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '获取地址失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    // ==================== 余额查询相关接口 ====================

    /**
     * 查询TRX余额
     *
     * 接口地址：/api/index/getTrxBalance
     * 请求方式：GET/POST
     * 参数：address - 钱包地址
     *
     * @return \think\response\Json
     */
    public function getTrxBalance()
    {
        $address = $this->request->param('address', '');

        if (!$address) {
            return json([
                'code' => 0,
                'msg' => '地址不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $signServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $explorer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);

            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer, $signServer, $explorer);
            $balance = $tron->getBalance($address, true);

            return json([
                'code' => 1,
                'msg' => 'TRX余额查询成功',
                'data' => $balance,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRX余额查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 查询TRC20代币余额（如USDT）
     *
     * 接口地址：/api/index/getTrc20Balance
     * 请求方式：GET/POST
     * 参数：address - 钱包地址
     *
     * @return \think\response\Json
     */
    public function getTrc20Balance()
    {
        $address = $this->request->param("address", "");

        if (!$address) {
            return json([
                'code' => 0,
                'msg' => '地址不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);
            $tron = new \IEXBase\TronAPI\Tron();
            $hexAddress = $tron->address2HexString($address);
            $getBalance = new Address($address, '', $hexAddress);
            $balance = $TRC20->balance($getBalance);

            return json([
                'code' => 1,
                'msg' => 'TRC20余额查询成功',
                'data' => $balance,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRC20余额查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 查询TRC10代币余额和信息
     *
     * 接口地址：/api/index/getTrc10Info
     * 请求方式：GET/POST
     * 参数：
     *   - address: 钱包地址（可选，默认测试地址）
     *   - tokenId: TRC10代币ID（可选，默认1002992）
     *
     * @return \think\response\Json
     */
    public function getTrc10Info()
    {
        $address = $this->request->param("address", "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu");
        $tokenId = $this->request->param("tokenId", "1002992");

        try {
            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $signServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $explorer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $privateKey = "8fb5bcf216e7d30c2a*****5bac7e35958309224a8cdeeb40e1fa5e95b34222c";

            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer, $signServer, $explorer, $privateKey);
            $tron->setAddress($address);

            // 获取TRX余额
            $getBalance = $tron->getBalance(null, true);

            // 获取TRC10代币余额
            $getTokenBalance = $tron->getTokenBalance($tokenId, $address);

            // 获取代币信息
            $getTokenByID = $tron->getTokenByID($tokenId);

            // 生成新地址（演示用）
            $generateAddress = $tron->generateAddress();

            return json([
                'code' => 1,
                'msg' => 'TRC10信息查询成功',
                'data' => compact('getBalance', 'getTokenBalance', 'getTokenByID', 'generateAddress'),
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRC10信息查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    // ==================== 转账相关接口 ====================

    /**
     * TRX转账
     *
     * 接口地址：/api/index/sendTrx
     * 请求方式：GET/POST
     * 参数：
     *   - to: 接收地址
     *   - amount: 转账金额（TRX）
     *   - key: 发送方私钥
     *   - message: 转账备注（可选）
     *
     * @return \think\response\Json
     */
    public function sendTrx()
    {
        $to = $this->request->param('to', '');
        $amount = (float)$this->request->param('amount', 0);
        $key = $this->request->param('key', '');
        $message = $this->request->param('message', null);

        // 参数验证
        if (!$to || !$key || $amount <= 0) {
            return json([
                'code' => 0,
                'msg' => '参数不完整：需要接收地址、私钥和转账金额',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            // 处理转账备注
            if ($message) {
                $message = strToUtf8($message);
            }

            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $signServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $explorer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);

            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer, $signServer, $explorer, $key);

            // 获取发送方地址
            $fromAddress = $this->getAddressByPrivateKey($key);

            // 执行转账
            if ($message) {
                $ret = $tron->sendTrxMessage($to, $amount, $message, $fromAddress->address);
            } else {
                $ret = $tron->sendTrx($to, $amount, null, $fromAddress->address);
            }

            return json([
                'code' => 1,
                'msg' => 'TRX转账成功',
                'data' => $ret,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRX转账失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * TRC20代币转账（如USDT）
     *
     * 接口地址：/api/index/sendTrc20
     * 请求方式：GET/POST
     * 参数：
     *   - to: 接收地址
     *   - amount: 转账金额
     *   - key: 发送方私钥
     *
     * @return \think\response\Json
     */
    public function sendTrc20()
    {
        $to = $this->request->param("to", "");
        $key = $this->request->param("key", "");
        $amount = $this->request->param("amount", "1.000001");

        // 参数验证
        if (!$to || !$key) {
            return json([
                'code' => 0,
                'msg' => '参数不完整：需要接收地址和私钥',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);

            // 获取发送方地址
            $from = $this->getAddressByPrivateKey($key);

            // 构建接收方地址对象
            $tron = new \IEXBase\TronAPI\Tron();
            $hexAddress = $tron->address2HexString($to);
            $recipient = new Address($to, '', $hexAddress);

            // 执行转账
            $ret = $TRC20->transfer($from, $recipient, $amount);

            return json([
                'code' => 1,
                'msg' => 'TRC20转账成功',
                'data' => $ret,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRC20转账失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * TRC10代币转账
     *
     * 接口地址：/api/index/sendTrc10
     * 请求方式：GET/POST
     * 参数：
     *   - to: 接收地址
     *   - amount: 转账金额
     *   - key: 发送方私钥
     *   - tokenId: TRC10代币ID（可选，默认1002992）
     *
     * @return \think\response\Json
     */
    public function sendTrc10()
    {
        $tokenId = $this->request->param("tokenId", '1002992');
        $privateKey = $this->request->param("key", '');
        $amount = $this->request->param("amount", 1);
        $address = $this->request->param("to", '');

        // 参数验证
        if (!$privateKey || !$address) {
            return json([
                'code' => 0,
                'msg' => '参数不完整：需要私钥和接收地址',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $signServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $explorer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);

            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer, $signServer, $explorer, $privateKey);

            // 获取发送方地址
            $fromAddress = $this->getAddressByPrivateKey($privateKey);

            // 执行TRC10转账
            $sendToken = $tron->sendToken($address, $amount, $tokenId, $fromAddress->address);

            if (isset($sendToken['code'])) {
                return json([
                    'code' => 0,
                    'msg' => 'TRC10转账失败',
                    'data' => $sendToken,
                    'time' => time()
                ]);
            } else {
                if (isset($sendToken['result']) && $sendToken['result'] == true) {
                    return json([
                        'code' => 1,
                        'msg' => 'TRC10转账成功',
                        'data' => $sendToken,
                        'time' => time()
                    ]);
                } else {
                    return json([
                        'code' => 0,
                        'msg' => 'TRC10转账失败',
                        'data' => $sendToken,
                        'time' => time()
                    ]);
                }
            }
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRC10转账失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    // ==================== 交易查询相关接口 ====================

    /**
     * 查询交易详情（通用）
     *
     * 接口地址：/api/index/getTransaction
     * 请求方式：GET/POST
     * 参数：txID - 交易哈希
     *
     * @return \think\response\Json
     */
    public function getTransaction()
    {
        $txID = $this->request->param('txID', '');

        if (!$txID) {
            return json([
                'code' => 0,
                'msg' => '交易ID不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $signServer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);
            $explorer = new \IEXBase\TronAPI\Provider\HttpProvider($this->uri);

            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer, $signServer, $explorer);
            $ret = $tron->getTransaction($txID);

            return json([
                'code' => 1,
                'msg' => '交易查询成功',
                'data' => $ret,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '交易查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 查询TRC20交易回执
     *
     * 接口地址：/api/index/getTrc20TransactionReceipt
     * 请求方式：GET/POST
     * 参数：txID - 交易哈希
     *
     * @return \think\response\Json
     */
    public function getTrc20TransactionReceipt()
    {
        $txHash = $this->request->param("txID", "");

        if (!$txHash) {
            return json([
                'code' => 0,
                'msg' => '交易ID不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);
            $ret = $TRC20->transactionReceipt($txHash);

            return json([
                'code' => 1,
                'msg' => 'TRC20交易回执查询成功',
                'data' => $ret,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => 'TRC20交易回执查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    // ==================== 区块链信息查询接口 ====================

    /**
     * 获取当前区块高度
     *
     * 接口地址：/api/index/getBlockHeight
     * 请求方式：GET/POST
     *
     * @return \think\response\Json
     */
    public function getBlockHeight()
    {
        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);
            $block = $TRC20->blockNumber();

            return json([
                'code' => 1,
                'msg' => '区块高度查询成功',
                'data' => $block,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '区块高度查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    /**
     * 根据区块号查询区块信息
     *
     * 接口地址：/api/index/getBlockByNumber
     * 请求方式：GET/POST
     * 参数：blockID - 区块号
     *
     * @return \think\response\Json
     */
    public function getBlockByNumber()
    {
        $blockID = $this->request->param("blockID", "");

        if (!$blockID) {
            return json([
                'code' => 0,
                'msg' => '区块号不能为空',
                'data' => null,
                'time' => time()
            ]);
        }

        try {
            $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
            $TRC20 = new \Tron\TRC20($api, $this->config);
            $block = $TRC20->blockByNumber($blockID);

            return json([
                'code' => 1,
                'msg' => '区块信息查询成功',
                'data' => $block,
                'time' => time()
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '区块信息查询失败：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
        }
    }

    // ==================== 私有辅助方法 ====================

    /**
     * 助记词转TRON地址
     *
     * @param string $mnemonic 助记词
     * @return array 包含地址和私钥的数组
     */
    private function mnemonicToTronAddress($mnemonic)
    {
        if (empty($mnemonic)) {
            return [];
        }

        $seedGenerator = new Bip39SeedGenerator();
        $seed = $seedGenerator->getSeed($mnemonic);
        $hdFactory = new HierarchicalKeyFactory();
        $master = $hdFactory->fromEntropy($seed);

        $util = new Util();
        // TRON的派生路径是44'/195'/0'/0/0
        $hardened = $master->derivePath("44'/195'/0'/0/0");

        // 获取私钥
        $privateKey = $hardened->getPrivateKey()->getHex();

        // 生成地址
        $res = $util->publicKeyToAddress($util->privateKeyToPublicKey($hardened->getPrivateKey()->getHex()));
        $address_hx = substr_replace($res, '41', 0, 2);
        $address_58 = hexString2Base58check($address_hx);

        return [
            'address' => $address_58,
            'privateKey' => $privateKey,
        ];
    }

    /**
     * 根据私钥获取地址对象
     *
     * @param string $key 私钥
     * @return object 地址对象
     */
    private function getAddressByPrivateKey($key)
    {
        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
        $TRC20 = new \Tron\TRC20($api, $this->config);
        return $TRC20->privateKeyToAddress($key);
    }

    // ==================== 测试和工具接口 ====================

    /**
     * API状态检查
     *
     * 接口地址：/api/index/status
     * 请求方式：GET/POST
     *
     * @return \think\response\Json
     */
    public function status()
    {
        return json([
            'code' => 1,
            'msg' => 'TRON API服务运行正常',
            'data' => [
                'version' => '3.0',
                'node' => $this->uri,
                'timestamp' => time(),
                'date' => date('Y-m-d H:i:s')
            ],
            'time' => time()
        ]);
    }

    /**
     * 获取API接口列表
     *
     * 接口地址：/api/index/getApiList
     * 请求方式：GET/POST
     *
     * @return \think\response\Json
     */
    public function getApiList()
    {
        $apiList = [
            '地址生成' => [
                'createAddress' => '生成TRON地址',
                'generateAddressWithMnemonic' => '通过助记词生成地址',
                'getAddressByKey' => '根据私钥获取地址'
            ],
            '余额查询' => [
                'getTrxBalance' => '查询TRX余额',
                'getTrc20Balance' => '查询TRC20代币余额',
                'getTrc10Info' => '查询TRC10代币信息'
            ],
            '转账功能' => [
                'sendTrx' => 'TRX转账',
                'sendTrc20' => 'TRC20代币转账',
                'sendTrc10' => 'TRC10代币转账'
            ],
            '交易查询' => [
                'getTransaction' => '查询交易详情',
                'getTrc20TransactionReceipt' => '查询TRC20交易回执'
            ],
            '区块链信息' => [
                'getBlockHeight' => '获取区块高度',
                'getBlockByNumber' => '根据区块号查询区块'
            ],
            '工具接口' => [
                'status' => 'API状态检查',
                'getApiList' => '获取接口列表'
            ]
        ];

        return json([
            'code' => 1,
            'msg' => '接口列表获取成功',
            'data' => $apiList,
            'time' => time()
        ]);
    }
}
