# TRON API 接口文档

## 基础信息

- **基础 URL**: `http://your-domain.com/v1/` 或 `http://your-domain.com/api/index/`
- **返回格式**: JSON
- **字符编码**: UTF-8
- **请求方式**: 支持 GET 和 POST
- **API 版本**: v3.0

## 通用返回格式

```json
{
  "code": 1, // 状态码 1:成功 0:失败
  "msg": "操作成功", // 状态消息
  "data": {}, // 返回数据
  "time": 1756395466 // 时间戳
}
```

---

## 地址生成相关

### 1. 生成 TRON 地址

- **接口地址**: `GET/POST /v1/createAddress` 或 `/api/index/createAddress`
- **功能说明**: 随机生成一个新的 TRON 地址
- **请求参数**: 无
- **返回示例**:

```json
{
  "code": 1,
  "msg": "地址生成成功",
  "data": {
    "privateKey": "7a0a01c930a4d3c83bad9e8493bdec2fccfaf070532f8b67d6b82f76175acf12",
    "address": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "hexAddress": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f"
  },
  "time": 1756395466
}
```

### 2. 通过助记词生成地址

- **接口地址**: `GET/POST /v1/generateAddressWithMnemonic` 或 `/api/index/generateAddressWithMnemonic`
- **功能说明**: 自动生成助记词并转换为 TRON 地址
- **请求参数**: 无
- **返回示例**:

```json
{
  "code": 1,
  "msg": "助记词地址生成成功",
  "data": {
    "address": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "privateKey": "7a0a01c930a4d3c83bad9e8493bdec2fccfaf070532f8b67d6b82f76175acf12",
    "hexAddress": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f",
    "mnemonic": "leg open globe profit orchard economy spider inside rabbit vocal spell build"
  },
  "time": 1756395466
}
```

### 3. 私钥转地址

- **接口地址**: `GET/POST /v1/getAddressByKey` 或 `/api/index/getAddressByKey`
- **功能说明**: 根据私钥获取对应的 TRON 地址信息
- **请求参数**:
  - `privateKey` (string, 必填): 64 位十六进制私钥
- **返回示例**:

```json
{
  "code": 1,
  "msg": "获取地址成功",
  "data": {
    "address": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "privateKey": "7a0a01c930a4d3c83bad9e8493bdec2fccfaf070532f8b67d6b82f76175acf12",
    "hexAddress": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f"
  },
  "time": 1756395466
}
```

---

## 余额查询相关

### 4. 查询 TRX 余额

- **接口地址**: `GET/POST /v1/getTrxBalance` 或 `/api/index/getTrxBalance`
- **功能说明**: 查询指定地址的 TRX 余额
- **请求参数**:
  - `address` (string, 必填): TRON 地址
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRX余额查询成功",
  "data": 1000000,
  "time": 1756395466
}
```

### 5. 查询 TRC20 余额

- **接口地址**: `GET/POST /v1/getTrc20Balance` 或 `/api/index/getTrc20Balance`
- **功能说明**: 查询指定地址的 TRC20 代币余额（默认 USDT）
- **请求参数**:
  - `address` (string, 必填): TRON 地址
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRC20余额查询成功",
  "data": "1000.000000",
  "time": 1756395466
}
```

### 6. 查询 TRC10 代币信息

- **接口地址**: `GET/POST /v1/getTrc10Info` 或 `/api/index/getTrc10Info`
- **功能说明**: 查询指定地址的 TRC10 代币余额和代币信息
- **请求参数**:
  - `address` (string, 可选): TRON 地址，默认测试地址 `TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu`
  - `tokenId` (string, 可选): TRC10 代币 ID，默认 `1002992`
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRC10信息查询成功",
  "data": {
    "getBalance": 1000000,
    "getTokenBalance": 100,
    "getTokenByID": {
      "id": "1002992",
      "name": "TestToken",
      "abbr": "TT",
      "total_supply": 1000000000
    },
    "generateAddress": {
      "address_base58": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
      "address_hex": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f",
      "private_key": "7a0a01c930a4d3c83bad9e8493bdec2fccfaf070532f8b67d6b82f76175acf12"
    }
  },
  "time": 1756395466
}
```

---

## 转账相关

### 7. TRX 转账

- **接口地址**: `GET/POST /v1/sendTrx` 或 `/api/index/sendTrx`
- **功能说明**: 发送 TRX 转账交易
- **请求参数**:
  - `to` (string, 必填): 接收地址
  - `amount` (float, 必填): 转账金额（单位：TRX）
  - `key` (string, 必填): 发送方私钥
  - `message` (string, 可选): 附加消息
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRX转账成功",
  "data": {
    "result": true,
    "txid": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6",
    "txID": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6"
  },
  "time": 1756395466
}
```

### 8. TRC20 转账

- **接口地址**: `GET/POST /v1/sendTrc20` 或 `/api/index/sendTrc20`
- **功能说明**: 发送 TRC20 代币转账（默认 USDT）
- **请求参数**:
  - `to` (string, 必填): 接收地址
  - `amount` (string, 必填): 转账金额，默认 "1.000001"
  - `key` (string, 必填): 发送方私钥
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRC20转账成功",
  "data": {
    "result": true,
    "txid": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6",
    "txID": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6"
  },
  "time": 1756395466
}
```

### 9. TRC10 转账

- **接口地址**: `GET/POST /v1/sendTrc10` 或 `/api/index/sendTrc10`
- **功能说明**: 发送 TRC10 代币转账
- **请求参数**:
  - `to` (string, 必填): 接收地址
  - `amount` (int, 必填): 转账金额，默认 1
  - `key` (string, 必填): 发送方私钥
  - `tokenId` (string, 可选): TRC10 代币 ID，默认 "1002992"
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRC10转账成功",
  "data": {
    "result": true,
    "txid": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6",
    "txID": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6"
  },
  "time": 1756395466
}
```

---

## 交易查询相关

### 10. 查询交易详情

- **接口地址**: `GET/POST /v1/getTransaction` 或 `/api/index/getTransaction`
- **功能说明**: 根据交易哈希查询交易详情
- **请求参数**:
  - `txID` (string, 必填): 交易哈希
- **返回示例**:

```json
{
  "code": 1,
  "msg": "交易查询成功",
  "data": {
    "ret": [{ "contractRet": "SUCCESS" }],
    "txID": "6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6",
    "raw_data": {
      "contract": [
        {
          "parameter": {
            "value": {
              "amount": 1100000,
              "owner_address": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f",
              "to_address": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f"
            }
          }
        }
      ]
    }
  },
  "time": 1756395466
}
```

### 11. 查询 TRC20 交易回执

- **接口地址**: `GET/POST /v1/getTrc20TransactionReceipt` 或 `/api/index/getTrc20TransactionReceipt`
- **功能说明**: 查询 TRC20 交易的详细回执信息
- **请求参数**:
  - `txID` (string, 必填): 交易哈希
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRC20交易回执查询成功",
  "data": {
    "receipt": {
      "result": "SUCCESS",
      "energy_usage": 13345
    }
  },
  "time": 1756395466
}
```

---

## 区块链信息相关

### 12. 获取当前区块高度

- **接口地址**: `GET/POST /v1/getBlockHeight` 或 `/api/index/getBlockHeight`
- **功能说明**: 获取当前区块链的最新区块高度
- **请求参数**: 无
- **返回示例**:

```json
{
  "code": 1,
  "msg": "区块高度查询成功",
  "data": 88888888,
  "time": 1756395466
}
```

### 13. 根据区块号查询区块信息

- **接口地址**: `GET/POST /v1/getBlockByNumber` 或 `/api/index/getBlockByNumber`
- **功能说明**: 根据区块号查询区块详细信息
- **请求参数**:
  - `blockID` (string, 必填): 区块号
- **返回示例**:

```json
{
  "code": 1,
  "msg": "区块信息查询成功",
  "data": {
    "blockID": "00000000⭐⭐⭐virtualBlock123Demo456Hash⭐⭐⭐789abc",
    "block_header": {
      "raw_data": {
        "number": 88888888,
        "txTrieRoot": "...",
        "witness_address": "...",
        "parentHash": "...",
        "timestamp": 1756395466000
      }
    }
  },
  "time": 1756395466
}
```

---

## 工具接口

### 14. API 状态检查

- **接口地址**: `GET/POST /v1/status` 或 `/api/index/status`
- **功能说明**: 检查 API 服务运行状态
- **请求参数**: 无
- **返回示例**:

```json
{
  "code": 1,
  "msg": "TRON API服务运行正常",
  "data": {
    "version": "3.0",
    "node": "https://api.trongrid.io",
    "timestamp": 1756395466,
    "date": "2025-01-07 12:00:00"
  },
  "time": 1756395466
}
```

### 15. 获取接口列表

- **接口地址**: `GET/POST /v1/getApiList` 或 `/api/index/getApiList`
- **功能说明**: 获取所有可用的 API 接口列表
- **请求参数**: 无
- **返回示例**:

```json
{
  "code": 1,
  "msg": "接口列表获取成功",
  "data": {
    "地址生成": {
      "createAddress": "生成TRON地址",
      "generateAddressWithMnemonic": "通过助记词生成地址",
      "getAddressByKey": "根据私钥获取地址"
    },
    "余额查询": {
      "getTrxBalance": "查询TRX余额",
      "getTrc20Balance": "查询TRC20代币余额",
      "getTrc10Info": "查询TRC10代币信息"
    },
    "转账功能": {
      "sendTrx": "TRX转账",
      "sendTrc20": "TRC20代币转账",
      "sendTrc10": "TRC10代币转账"
    },
    "交易查询": {
      "getTransaction": "查询交易详情",
      "getTrc20TransactionReceipt": "查询TRC20交易回执"
    },
    "区块链信息": {
      "getBlockHeight": "获取区块高度",
      "getBlockByNumber": "根据区块号查询区块"
    },
    "工具接口": {
      "status": "API状态检查",
      "getApiList": "获取接口列表"
    }
  },
  "time": 1756395466
}
```

---

## 错误码说明

| 错误码 | 说明     |
| ------ | -------- |
| 0      | 请求失败 |
| 1      | 请求成功 |

## 常见错误消息

- `私钥不能为空` - 需要提供有效的私钥参数
- `地址不能为空` - 需要提供有效的 TRON 地址
- `参数不完整：需要接收地址、私钥和转账金额` - TRX 转账参数不完整
- `参数不完整：需要接收地址和私钥` - TRC20/TRC10 转账参数不完整
- `交易ID不能为空` - 查询交易时需要提供交易哈希
- `区块号不能为空` - 查询区块时需要提供区块号
- `地址生成失败` - 地址生成过程中出现错误
- `助记词地址生成失败` - 助记词生成地址过程中出现错误
- `TRX余额查询失败` - TRX 余额查询过程中出现错误
- `TRC20余额查询失败` - TRC20 余额查询过程中出现错误
- `TRC10信息查询失败` - TRC10 信息查询过程中出现错误
- `TRX转账失败` - TRX 转账过程中出现错误
- `TRC20转账失败` - TRC20 转账过程中出现错误
- `TRC10转账失败` - TRC10 转账过程中出现错误

## 注意事项

1. **安全提醒**：所有涉及私钥的接口请妥善保管私钥，避免泄露
2. **单位说明**：TRX 转账金额单位为 TRX（1 TRX = 1,000,000 SUN）
3. **手续费**：TRC20 转账需要消耗一定的 Energy 或 TRX 作为手续费
4. **交易确认**：转账操作有延迟，建议 3 分钟后再查询交易状态
5. **助记词安全**：助记词请妥善保管，丢失后无法找回
6. **测试环境**：建议在正式使用前先在测试网进行测试
7. **API 版本**：当前 API 版本为 v3.0，支持 v1 路由兼容
8. **请求方式**：所有接口均支持 GET 和 POST 请求方式
9. **返回格式**：所有接口均返回 JSON 格式数据，包含时间戳字段
10. **合约地址**：默认 TRC20 合约地址为 USDT (TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t)

## 技术支持

- **作者**：纸飞机(Telegram): https://t.me/king_orz

- **温馨提示**：接受各种代码定制

如有问题请联系技术支持。
