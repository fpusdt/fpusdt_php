<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz
 本地文档系统
 * 温馨提示：接受各种代码定制
 */

namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
	public function index()
	{
		// 动态获取当前域名
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$domainName = $_SERVER['HTTP_HOST'];
		$currentDomain = $protocol . $domainName;

		// 准备页面数据
		$pageData = $this->getPageData($currentDomain);

		// 传递数据到视图
		$this->assign('currentDomain', $currentDomain);
		$this->assign('pageData', $pageData);
		$this->assign('updateTime', date('Y年m月d日 H:i:s'));

		return $this->fetch();
	}

	/**
	 * 获取页面数据
	 */
	private function getPageData($domain)
	{
		return [
			'features' => [
				[
					'icon' => '💼',
					'title' => '钱包管理',
					'desc' => '支持批量生成TRON钱包地址，提供完整的私钥和助记词管理功能，确保资产安全'
				],
				[
					'icon' => '📊',
					'title' => '余额查询',
					'desc' => '实时查询TRX和各类TRC20代币余额，支持批量查询，响应速度快'
				],
				[
					'icon' => '🔐',
					'title' => '交易处理',
					'desc' => '提供安全可靠的转账功能，支持TRX和TRC20代币转账，手续费透明'
				],
				[
					'icon' => '⚡',
					'title' => '高性能',
					'desc' => '基于ThinkPHP 5.0框架，优化的接口性能，支持高并发访问'
				],
				[
					'icon' => '🛡️',
					'title' => '安全稳定',
					'desc' => '企业级安全架构，多重验证机制，确保交易和数据的安全性'
				],
				[
					'icon' => '📖',
					'title' => '文档完善',
					'desc' => '提供详细的API文档和示例代码，支持多种编程语言调用'
				]
			],
			'apiTests' => [
				[
					'icon' => '💳',
					'title' => '生成钱包地址',
					'url' => $domain . '/api/tool/generateAddressWithMnemonic',
					'class' => ''
				],
				[
					'icon' => '💵',
					'title' => '查询USDT余额',
					'url' => $domain . '/api/trc20/getAddressBalance?address=TTAUj1qkSVK2LuZBResGu2xXb1ZAguGsnu',
					'class' => ''
				],
				[
					'icon' => '⚡',
					'title' => '查询TRX余额',
					'url' => $domain . '/api/trx/getBalance?address=TEjKST74gKeKzjovquhuKUkvCuakmadwvP',
					'class' => ''
				],
				[
					'icon' => '📋',
					'title' => '完整接口文档',
					'url' => $domain . '/index/docs',
					'class' => 'special-card'
				]
			]
		];
	}
}
