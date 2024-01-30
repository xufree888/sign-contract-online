<?php

namespace SHSign\Kernel;

use SHSign\Exceptions\InvalidArgumentException;
use SHSign\Exceptions\InvalidResponseException;
use SHSign\Tools\DataArray;
use SHSign\Tools\DataTransform;
use SHSign\Tools\RequestTool;

class BasicSHSign {

    const VERSION = '1.4.5';

    public $config;

    // access token
    public $sign = '';

    // 当前请求方法
    private $_currentMethod = [];

    // 是否是重试
    private $_isTry = false;

    public function __construct(array $options) {
        if (empty($options['client_id'])) {
            throw new InvalidArgumentException('miss config [client_id]');
        }
        if (empty($options['client_secret'])) {
            throw new InvalidArgumentException('miss config [client_secret]');
        }
        if (empty($options['username'])) {
            throw new InvalidArgumentException('miss config [username]');
        }
        if (empty($options['password'])) {
            throw new InvalidArgumentException('miss config [password]');
        }
        if (empty($options['companyId'])) {
            throw new InvalidArgumentException('miss config [companyId]');
        }
        if (empty($options['companyCode'])) {
            throw new InvalidArgumentException('miss config [companyCode]');
        }
        if (empty($options['companyName'])) {
            throw new InvalidArgumentException('miss config [companyName]');
        }
        if (empty($options['url'])) {
            throw new InvalidArgumentException('miss config [url]');
        }
        $this->config = new DataArray($options);
    }

    /**
     * 获取灵声版本号
     * @return string
     * Author: DQ
     */
    public function getVersion() {
        return self::VERSION;
    }

    /**
     * 遇到错误是否再次尝试
     *
     * @param bool $bool
     *                  true 再次尝试
     *                  false 不会再次尝试
     *                  Author: DQ
     */
    public function tryAgain($bool = false) {
        $this->_isTry = $bool == false;
    }

    public function getUrl($uri = '') {
        return sprintf("%s%s", $this->config['url'], $uri);
    }

    /**
     * 注册请求
     *
     * @param       $method
     *                        方法
     * @param array $arguments
     *                        参数
     *
     * @throws \ErrorException
     * @throws \ListenRobot\Exceptions\InvalidResponseException
     * @throws \ListenRobot\Exceptions\LocalCacheException
     * Author: DQ
     */
    protected function registerApi($method, $arguments = []) {
        $this->_currentMethod = ['method' => $method, 'arguments' => $arguments];
    }

    /**
     * http get 简单请求
     *
     * @param $url
     *
     * @return mixed
     * @throws \BYRobot\Exceptions\InvalidResponseException
     * @throws \ErrorException
     * Author: DQ
     */
    public function httpGetJson($url) {
        try {
            $this->registerApi(__FUNCTION__, func_get_args());

            return DataTransform::json2arr(RequestTool::get($url, []));
        } catch (InvalidResponseException $e) {
            if (!$this->_isTry) {
                $this->_isTry = true;

                return call_user_func_array([
                    $this,
                    $this->_currentMethod['method']
                ], $this->_currentMethod['arguments']);
            }
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * post 请求返回json 数组
     *
     * @param $url
     * @param $data
     *
     * @return mixed
     * @throws \BYRobot\Exceptions\InvalidResponseException
     * @throws \ErrorException
     * Author: DQ
     */
    public function httpPostJson($url, $data, $headers = []) {
        try {
            $this->registerApi(__FUNCTION__, func_get_args());
            $response = RequestTool::post($url, $data, $headers);

            return DataTransform::json2arr($response);
        } catch (InvalidResponseException $e) {
            if (!$this->_isTry) {
                $this->_isTry = true;

                return call_user_func_array([
                    $this,
                    $this->_currentMethod['method']
                ], $this->_currentMethod['arguments']);
            }
            throw new InvalidResponseException($e->getMessage(), $e->getCode());
        }
    }

}