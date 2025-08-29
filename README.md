# 🚀 TRON API 3.0 - 专业的 TRON 区块链接口服务

[![PHP Version](https://img.shields.io/badge/PHP-7.3%2B-blue.svg)](https://php.net)
[![ThinkPHP](https://img.shields.io/badge/ThinkPHP-5.0-green.svg)](http://www.thinkphp.cn)
[![License](https://img.shields.io/badge/License-Commercial-orange.svg)](https://t.me/king_orz)

<div align="center">

**🌟 加入我们的 Telegram 社区，获得最佳支持！**

[![📢 官方频道](https://img.shields.io/badge/📢%20官方频道-@fpusdt-0088cc?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/fpusdt)
[![💬 交流群组](https://img.shields.io/badge/💬%20交流群组-@fpusdtcom-229ed9?style=for-the-badge&logo=telegram&logoColor=white)](https://t.me/fpusdtcom)

</div>

> 基于 ThinkPHP 5.0 开发的专业 TRON 区块链接口服务，支持 TRC20/TRC10 代币和 TRX 的完整操作，整合版本提供更简洁的 API 接口。

## 📚 在线文档

- **🌟 本地 API 文档**: [您的域名/doc](http://your-domain.com/doc)

- **📋 API 接口列表**: [您的域名/v1/getApiList](http://your-domain.com/v1/getApiList)

## 🎯 目录

- [💾 环境要求](#-环境要求)
- [🛠 安装配置](#-安装配置)
- [🔧 重要提醒](#-重要提醒)
- [📱 加入 Telegram 社区](#-加入-telegram-社区)
- [📋 API 接口](#-api接口)
  - [💳 钱包管理](#-钱包管理)
  - [💰 TRC20 (USDT)](#-trc20-usdt)
  - [⚡ TRX 原生代币](#-trx-原生代币)
  - [🪙 TRC10 代币](#-trc10-代币)
- [💬 技术支持](#-技术支持)

## 💾 环境要求

### 🔴 必需组件

| 组件         | 版本要求       | 说明                 |
| ------------ | -------------- | -------------------- |
| **PHP**      | `7.3` 或 `7.4` | 核心运行环境         |
| **GMP 扩展** | ✅ 必须安装    | 大整数运算，转账必需 |
| **ThinkPHP** | `5.0`          | 框架要求             |

### ⚙️ 服务器配置

- **运行目录**: `public`
- **伪静态**: 必须配置
- **HTTPS**: 建议启用（安全性）

## 🛠 安装配置

### 1️⃣ 下载部署

```bash
# 1. 上传代码到服务器
# 2. 设置网站根目录为 public 文件夹
# 3. 配置伪静态规则（根据服务器类型）
```

### 2️⃣ 伪静态配置

**Apache (.htaccess)**

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
```

**Nginx**

```nginx
location / {
    if (!-e $request_filename) {
        rewrite ^(.*)$ /index.php?s=/$1 last;
    }
}
```

### 3️⃣ 权限设置

```bash
chmod -R 755 runtime/
chmod -R 755 public/
```

## 🔧 重要提醒

> ### ⚠️ 关键注意事项

- **🔒 合约地址**: 系统内置，**切勿修改**
- **🔑 私钥安全**: 生产环境必须使用 HTTPS
- **🧪 测试环境**: 建议先在测试网验证
- **📊 日志监控**: 定期检查 runtime 日志
- **🚀 性能优化**: 可配置 Redis 缓存

---

## 📋 API 接口

### 🔗 基础信息

- **接口域名**: `http://your-domain.com`
- **API 版本**: `v1` (推荐使用 v1 路径)
- **数据格式**: `JSON`
- **字符编码**: `UTF-8`
- **请求方式**: `GET` / `POST`

### 🌐 API 路径说明

**推荐使用 v1 版本路径**：

- v1 路径：`http://your-domain.com/v1/接口名`
- 直接路径：`http://your-domain.com/api/index/接口名`

> 💡 **建议**：为了更好的版本管理和兼容性，推荐使用 `v1/` 前缀访问所有 API 接口。

### 📊 统一返回格式

```json
{
  "code": 1, // 状态码：1=成功，0=失败
  "msg": "success", // 状态消息
  "data": {}, // 返回数据
  "time": 1756395466 // 时间戳
}
```

---

## 💳 钱包管理

### 🎯 生成 TRON 地址（简单版）

**接口地址**: `GET /v1/createAddress`

```bash
curl "http://your-domain.com/v1/createAddress"
```

**返回示例**:

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

### 🌱 通过助记词生成地址

**接口地址**: `GET /v1/generateAddressWithMnemonic`

```bash
curl "http://your-domain.com/v1/generateAddressWithMnemonic"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "助记词地址生成成功",
  "data": {
    "address": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "privateKey": "7a0a01c930a4d3c83bad9e8493bdec2fccfaf070532f8b67d6b82f76175acf12",
    "mnemonic": "leg open globe profit orchard economy spider inside rabbit vocal spell build",
    "hexAddress": "41bc9bd6d0db7bf6e20874459c7481d00d3825117f"
  },
  "time": 1756395466
}
```

### 🔑 根据私钥获取地址

**接口地址**: `GET /v1/getAddressByKey`

| 参数       | 类型   | 必填 | 说明       |
| ---------- | ------ | ---- | ---------- |
| privateKey | string | ✅   | 私钥字符串 |

```bash
curl "http://your-domain.com/v1/getAddressByKey?privateKey=your_private_key_here"
```

**返回示例**:

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

## 💰 TRC20 代币 (USDT)

### 💵 查询 TRC20 代币余额

**接口地址**: `GET /v1/getTrc20Balance`

| 参数    | 类型   | 必填 | 说明          |
| ------- | ------ | ---- | ------------- |
| address | string | ✅   | TRON 钱包地址 |

> 💡 **说明**: 默认查询 USDT 余额，合约地址已内置（TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t）

```bash
curl "http://your-domain.com/v1/getTrc20Balance?address=TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "TRC20余额查询成功",
  "data": "100.500000",
  "time": 1756395466
}
```

### 💸 TRC20 代币转账

**接口地址**: `POST /v1/sendTrc20`

| 参数   | 类型   | 必填 | 说明       |
| ------ | ------ | ---- | ---------- |
| to     | string | ✅   | 接收方地址 |
| amount | string | ✅   | 转账金额   |
| key    | string | ✅   | 发送方私钥 |

```bash
curl -X POST "http://your-domain.com/v1/sendTrc20" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "amount": "10.5",
    "key": "your_private_key_here"
  }'
```

**返回示例**:

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

### 📋 查询 TRC20 交易回执

**接口地址**: `GET /v1/getTrc20TransactionReceipt`

| 参数 | 类型   | 必填 | 说明        |
| ---- | ------ | ---- | ----------- |
| txID | string | ✅   | 交易哈希 ID |

```bash
curl "http://your-domain.com/v1/getTrc20TransactionReceipt?txID=6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6"
```

**返回示例**:

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

> ⚠️ **安全提醒**: 私钥是敏感信息，生产环境请务必使用 HTTPS 协议。

---

## ⚡ TRX 原生代币

### 💰 查询 TRX 余额

**接口地址**: `GET /v1/getTrxBalance`

| 参数    | 类型   | 必填 | 说明          |
| ------- | ------ | ---- | ------------- |
| address | string | ✅   | TRON 钱包地址 |

```bash
curl "http://your-domain.com/v1/getTrxBalance?address=TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "TRX余额查询成功",
  "data": 1500000000,
  "time": 1756395466
}
```

> 💡 **说明**: 余额单位为 SUN（1 TRX = 1,000,000 SUN）

### 💸 TRX 转账

**接口地址**: `POST /v1/sendTrx`

| 参数    | 类型   | 必填 | 说明                  |
| ------- | ------ | ---- | --------------------- |
| to      | string | ✅   | 接收方地址            |
| amount  | float  | ✅   | 转账金额（单位：TRX） |
| key     | string | ✅   | 发送方私钥            |
| message | string | ❌   | 转账备注信息（可选）  |

```bash
curl -X POST "http://your-domain.com/v1/sendTrx" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "amount": 10.5,
    "key": "your_private_key_here",
    "message": "转账备注"
  }'
```

**返回示例**:

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

> 📝 **备注功能**: 支持在转账时添加备注信息，备注会自动转换为 UTF-8 编码。

---

## 🪙 TRC10 代币

### 💰 查询 TRC10 代币信息

**接口地址**: `GET /v1/getTrc10Info`

| 参数    | 类型   | 必填 | 说明                          |
| ------- | ------ | ---- | ----------------------------- |
| address | string | ❌   | TRON 钱包地址（默认测试地址） |
| tokenId | string | ❌   | TRC10 代币 ID（默认 1002992） |

```bash
curl "http://your-domain.com/v1/getTrc10Info?address=TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu&tokenId=1002992"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "TRC10信息查询成功",
  "data": {
    "getBalance": 1500000000,
    "getTokenBalance": 100,
    "getTokenByID": {
      "id": "1002992",
      "name": "TestToken",
      "abbr": "TT",
      "total_supply": 1000000000
    },
    "generateAddress": {
      "address_base58": "TNewAddressExample...",
      "private_key": "...",
      "address_hex": "..."
    }
  },
  "time": 1756395466
}
```

### 💸 TRC10 代币转账

**接口地址**: `POST /v1/sendTrc10`

| 参数    | 类型   | 必填 | 说明                          |
| ------- | ------ | ---- | ----------------------------- |
| to      | string | ✅   | 接收方地址                    |
| amount  | int    | ✅   | 转账金额                      |
| key     | string | ✅   | 发送方私钥                    |
| tokenId | string | ❌   | TRC10 代币 ID（默认 1002992） |

```bash
curl -X POST "http://your-domain.com/v1/sendTrc10" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu",
    "amount": 1,
    "key": "your_private_key_here",
    "tokenId": "1002992"
  }'
```

**返回示例**:

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

## 🔧 交易查询接口

### 📋 查询交易详情

**接口地址**: `GET /v1/getTransaction`

| 参数 | 类型   | 必填 | 说明        |
| ---- | ------ | ---- | ----------- |
| txID | string | ✅   | 交易哈希 ID |

```bash
curl "http://your-domain.com/v1/getTransaction?txID=6518e7a20dd174a4cac3465b3fd3a4414688efa67a635669ed7b2c5eea0bb0f6"
```

**返回示例**:

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

## ⛓️ 区块链信息查询

### 📈 获取当前区块高度

**接口地址**: `GET /v1/getBlockHeight`

```bash
curl "http://your-domain.com/v1/getBlockHeight"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "区块高度查询成功",
  "data": 88888888,
  "time": 1756395466
}
```

### 🧱 根据区块号查询区块信息

**接口地址**: `GET /v1/getBlockByNumber`

| 参数    | 类型   | 必填 | 说明   |
| ------- | ------ | ---- | ------ |
| blockID | string | ✅   | 区块号 |

```bash
curl "http://your-domain.com/v1/getBlockByNumber?blockID=88888888"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "区块信息查询成功",
  "data": {
    "blockID": "0a02243b220833446f3f0c8a1a4740c0e5c9898f335aa201",
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

## 🛠️ 工具接口

### 🔍 API 状态检查

**接口地址**: `GET /v1/status`

```bash
curl "http://your-domain.com/v1/status"
```

**返回示例**:

```json
{
  "code": 1,
  "msg": "TRON API服务运行正常",
  "data": {
    "version": "3.0",
    "node": "https://api.trongrid.io",
    "timestamp": 1756395466,
    "date": "2024-01-01 12:00:00"
  },
  "time": 1756395466
}
```

### 📋 获取 API 接口列表

**接口地址**: `GET /v1/getApiList`

```bash
curl "http://your-domain.com/v1/getApiList"
```

**返回示例**:

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
    }
  },
  "time": 1756395466
}
```

---

## 📊 API 接口总览

### 🔗 完整接口列表

| 功能分类   | 接口名称               | 请求方式 | 接口路径                        |
| ---------- | ---------------------- | -------- | ------------------------------- |
| 地址生成   | 生成 TRON 地址         | GET      | /v1/createAddress               |
| 地址生成   | 通过助记词生成地址     | GET      | /v1/generateAddressWithMnemonic |
| 地址生成   | 根据私钥获取地址       | GET      | /v1/getAddressByKey             |
| 余额查询   | 查询 TRX 余额          | GET      | /v1/getTrxBalance               |
| 余额查询   | 查询 TRC20 代币余额    | GET      | /v1/getTrc20Balance             |
| 余额查询   | 查询 TRC10 代币信息    | GET      | /v1/getTrc10Info                |
| 转账功能   | TRX 转账               | POST     | /v1/sendTrx                     |
| 转账功能   | TRC20 代币转账         | POST     | /v1/sendTrc20                   |
| 转账功能   | TRC10 代币转账         | POST     | /v1/sendTrc10                   |
| 交易查询   | 查询交易详情           | GET      | /v1/getTransaction              |
| 交易查询   | 查询 TRC20 交易回执    | GET      | /v1/getTrc20TransactionReceipt  |
| 区块链信息 | 获取当前区块高度       | GET      | /v1/getBlockHeight              |
| 区块链信息 | 根据区块号查询区块信息 | GET      | /v1/getBlockByNumber            |
| 工具接口   | API 状态检查           | GET      | /v1/status                      |
| 工具接口   | 获取 API 接口列表      | GET      | /v1/getApiList                  |

### 🎯 快速开始示例

```bash
# 1. 检查API状态
curl "http://your-domain.com/v1/status"

# 2. 生成新地址
curl "http://your-domain.com/v1/createAddress"

# 3. 查询TRX余额
curl "http://your-domain.com/v1/getTrxBalance?address=YOUR_ADDRESS"

# 4. 查询USDT余额
curl "http://your-domain.com/v1/getTrc20Balance?address=YOUR_ADDRESS"

# 5. 获取完整接口列表
curl "http://your-domain.com/v1/getApiList"
```

---

## 📊 错误码说明

| 错误码 | 说明     | 常见原因                     | 解决方案           |
| ------ | -------- | ---------------------------- | ------------------ |
| `1`    | 操作成功 | -                            | -                  |
| `0`    | 操作失败 | 参数错误、网络异常、余额不足 | 检查参数和网络连接 |

### 🔍 常见错误信息

| 错误信息             | 原因分析             | 解决方案                      |
| -------------------- | -------------------- | ----------------------------- |
| `地址不能为空`       | 缺少 address 参数    | 提供有效的 TRON 地址          |
| `私钥不能为空`       | 缺少 privateKey 参数 | 提供有效的私钥                |
| `参数不完整`         | 缺少必填参数         | 检查所有必填参数是否提供      |
| `交易ID不能为空`     | 缺少 txID 参数       | 提供有效的交易哈希            |
| `区块号不能为空`     | 缺少 blockID 参数    | 提供有效的区块号              |
| `TRX转账失败`        | 余额不足或网络错误   | 检查余额和网络连接            |
| `TRC20转账失败`      | 余额不足或能量不足   | 检查 USDT 余额和 TRX 能量费用 |
| `助记词地址生成失败` | 助记词格式错误       | 检查助记词格式和单词数量      |

### ⚠️ 重要提醒

1. **私钥安全**: 所有涉及私钥的接口都应在 HTTPS 环境下使用
2. **参数验证**: 请确保所有参数格式正确，特别是地址格式
3. **网络延迟**: TRON 网络确认需要时间，建议 3 分钟后查询交易状态
4. **能量费用**: TRC20 转账需要消耗 Energy 或 TRX 作为手续费
5. **测试环境**: 建议先在测试网验证功能后再在主网使用

---

## 🚀 性能优化建议

### 📈 缓存配置

```php
// config/cache.php
return [
    'default' => 'redis',
    'stores' => [
        'redis' => [
            'type'   => 'redis',
            'host'   => '127.0.0.1',
            'port'   => 6379,
            'prefix' => 'trc20_api:',
            'expire' => 3600,
        ],
    ],
];
```

### 🔄 并发限制

- 建议 QPS 不超过 100
- 批量操作使用队列处理
- 重要操作添加重试机制

---

## 📱 加入 Telegram 社区

### 🚀 实时交流与技术支持

<div align="center">

[![Telegram频道](https://img.shields.io/badge/📢%20官方频道-@fpusdt-0088cc?style=for-the-badge&logo=telegram)](https://t.me/fpusdt)
[![Telegram群组](https://img.shields.io/badge/💬%20交流群组-@fpusdtcom-229ed9?style=for-the-badge&logo=telegram)](https://t.me/fpusdtcom)

</div>

| 🔗 链接                                              | 📝 说明                          | 🎯 功能                               |
| ---------------------------------------------------- | -------------------------------- | ------------------------------------- |
| **📢 [官方频道 @fpusdt](https://t.me/fpusdt)**       | 获取最新动态、更新公告和重要通知 | ⚡ 实时更新、📋 版本发布、🛡️ 安全提醒 |
| **💬 [交流群组 @fpusdtcom](https://t.me/fpusdtcom)** | 技术交流、问题讨论和经验分享     | 🛠️ 技术支持、❓ 问题解答、💡 经验分享 |

### 🌟 社区优势

- **⚡ 实时响应**: 技术问题快速解答，社区活跃度高
- **📚 资源丰富**: 分享开发经验、最佳实践和代码示例
- **🔒 官方保障**: 官方维护，信息准确可靠
- **🌍 全球开发者**: 与世界各地的开发者交流合作

## 💬 技术支持

### 🛒 购买正版

- **💬 Telegram**: [@king_orz](https://t.me/king_orz)
- **🌐 官网**: [https://www.919968.xyz/](https://www.919968.xyz/)
- **📚 文档**: [您的域名/index/docs](http://your-domain.com/index/docs)

### ⚠️ 防诈骗提醒

**🚨 注意识别诈骗账号**:

- ❌ 假冒 TG 账号: `laowu2021`
- ❌ 诈骗 GitHub 仓库:
  - `https://github.com/nblaoliu2022`
  - `https://github.com/vitosmitzela012`
  - `https://github.com/dogpig4311`
  - `https://github.com/annalyciaijaz699`

> ✅ **正版渠道**: 仅通过 [@king_orz](https://t.me/king_orz) 销售

---

## 📄 更新日志

### v3.0.0 - 整合版本 (2025-01-07)

- 🔄 **API 整合**: 将所有 API 整合到统一控制器中
- 🎯 **v1 路由**: 新增 v1 版本路由，提供更好的版本管理
- ✨ **功能完善**: 支持 TRC10/TRC20/TRX 的完整操作
- 📝 **助记词支持**: 完善的助记词生成和地址派生功能
- 🔍 **交易查询**: 增强的交易详情和回执查询功能
- 🧱 **区块链信息**: 完整的区块链信息查询接口
- 🛠️ **工具接口**: API 状态检查和接口列表功能
- 📚 **文档优化**: 完善的接口文档和使用示例

### v2.0.0 (2023-12-18)

- 🚀 基础功能完善

- 🔐 安全性增强

---

## 🎯 项目特色

### ✨ 核心优势

- **🔧 统一接口**: 所有 TRON 相关操作集成在一个 API 中
- **🚀 v1 版本**: 提供稳定的 v1 版本 API 路径
- **🔐 安全可靠**: 支持 HTTPS，私钥本地处理
- **📱 易于集成**: RESTful API 设计，支持多种编程语言
- **📖 文档完善**: 详细的接口文档和代码示例
- **🛠️ 开发友好**: 提供 API 状态检查和接口列表功能

### 🎨 技术栈

- **后端框架**: ThinkPHP 5.0
- **区块链 SDK**: IEXBase TronAPI、Tron PHP SDK
- **加密算法**: BitWasp Bitcoin、Web3 Ethereum Utils
- **助记词支持**: BIP39 标准实现

---

<div align="center">

**🌟 TRON API 3.0 - 让区块链开发更简单**

[![PHP](https://img.shields.io/badge/PHP-7.3%2B-blue.svg)](https://php.net)
[![ThinkPHP](https://img.shields.io/badge/ThinkPHP-5.0-green.svg)](http://www.thinkphp.cn)
[![Telegram](https://img.shields.io/badge/💬-Telegram-blue.svg)](https://t.me/king_orz)

**📞 技术支持**: [Telegram @king_orz](https://t.me/king_orz) | **🌐 官网**: [919968.xyz](https://www.919968.xyz/)

_最后更新: 2025 年 1 月 7 日_

</div>
