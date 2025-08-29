<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz

 * 温馨提示：接受各种代码定制
 */

namespace app\index\controller;


use GuzzleHttp\Client;
use think\Controller;
use think\Request;
use Tron\Address;

class Trc20 extends Controller
{
    protected $config;
    protected $uri;
    protected $privateKey;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->uri = 'https://api.trongrid.io'; /*API地址*/
        $this->privateKey = '8fb5bcf216e7d30c2a1*************24a8cdeeb40e1fa5e95b34111c'; /*私钥地址*/
        /*基础配置*/
        $this->config = [
            'contract_address' => 'TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t', // USDT TRC20
            'decimals' => 6, /*精度*/
        ];
    }

    /**
     * 根据私钥获取地址
     * $privateKey 私钥
     */
    public function getAddress()
    {
        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
        $TRC20 = new \Tron\TRC20($api, $this->config);
        /*私钥地址*/
        $privateKeyToAddress = $TRC20->privateKeyToAddress($this->privateKey);
        return $privateKeyToAddress;
    }


    /**
     * 获取余额
     * $address 地址对象
     */
    public function getBalance($address)
    {
        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
        $TRC20 = new \Tron\TRC20($api, $this->config);
        /*私钥地址*/
        $balance = $TRC20->balance($address);
        return $balance;
    }


    /**
     * 地址转换对象
     * $address 地址对象
     * TODO 地址转对象拿不到私钥，不过不影响转账
     */
    public function address2hexAddress($address)
    {
        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
        $TRC20 = new \Tron\TRC20($api, $this->config);
        $hexAddress = $TRC20->tron->address2HexString($address);
        $to = new Address($address, '', $hexAddress);
        return $to;
    }

    /**
     * @return \think\response\Json
     * 交易转账（离线签名）
     * 目前转自己：TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu
     * $from  转出账户对象
     * $to    接收账户对象
     * $amout 转出金额，最小单位为1 精度为6
     * $hexAddress  又address通过特殊方法转换得来，接收转账的$to 需要这个
     */
    public function transfer()
    {
        $toaddress = $this->request->get("toaddress", "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu");
        $amount = $this->request->get("amount", "1.000001");

        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));

        $TRC20 = new \Tron\TRC20($api, $this->config);

        $from = self::getAddress();

        $hexAddress = $TRC20->tron->address2HexString($toaddress);

        $to = new Address($toaddress, '', $hexAddress);

        $ret = $TRC20->transfer($from, $to, $amount);

        return json(['code' => 200, 'data' => $ret]);
    }

    /**
     * 查询交易信息
     * txHash 为转账返回结果参数 txID
     */
    public function transData()
    {

        $txHash = $this->request->get("txHash", "2507f9860d3db67cf06d9f1bf701fa8b627894e3d56933356830351082ed18e3");

        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));

        $TRC20 = new \Tron\TRC20($api, $this->config);;

        $ret = $TRC20->transactionReceipt($txHash);

        return json(['code' => 1, 'data' => $ret]);
    }

    /**
     * 生成地址
     * http:www.xxx.com/api/index/generateAddress
     */
    public function generateAddress()
    {
        $tron = new \IEXBase\TronAPI\Tron();
        $generateAddress = $tron->generateAddress(); // or createAddress()
        $address = (array)($generateAddress)->getRawData();
        $retdata['privateKey'] = $address['private_key'];
        $retdata['address'] = $address['address_base58'];
        $retdata['hexAddress'] = $address['address_hex'];
        return json(['code' => 1, 'data' => $retdata]);
    }

    /**
     * 综合方法
     * 生成地址
     * 验证地址
     * 查询余额
     * 根据私钥得到地址
     */
    public function index()
    {
        $api = new \Tron\Api(new Client(['base_uri' => $this->uri]));
        $TRC20 = new \Tron\TRC20($api, $this->config);

        /*生成地址*/
        $addressData = self::generateAddress();

        /*验证地址*/
        $validateAddress = $TRC20->validateAddress($addressData);
        if ($validateAddress) {

            /*根据私钥得到地址*/
            $privateKeyToAddress = $TRC20->privateKeyToAddress($addressData->privateKey);

            /*查询余额*/
            $balance = $TRC20->balance($addressData);
            return json(['code' => 1, 'msg' => "success", 'data' => compact("addressData", "validateAddress", "privateKeyToAddress", "balance")]);
        } else {
            return json(['code' => 0, 'msg' => "validateAddressfail", "data" => '']);
        }

        /*{
            "code": 1,
            "msg": "success",
            "data": {
                "addressData": {
                    "privateKey": "0xe2ad74294c273467027f80*********6cc2e9a9cd4214ef3418b818d48e66",
                    "address": "TLowZwvVHCQSKH8Pjwgo67TPe2dea7grWa",
                    "hexAddress": "4176e8c1d6e77d1ce87a0b242366c26f550556b689"
                },
                "validateAddress": true,
                "privateKeyToAddress": {
                    "privateKey": "0xe2ad74294c273467027f80******cc2e9a9cd4214ef3418b818d48e66",
                    "address": "TLowZwvVHCQ*****wgo67TPe2dea7grWa",
                    "hexAddress": "4176e8c1d6e7*****242366c26f550556b689"
                },
                "balance": "0"
            }
        }*/
    }
}
