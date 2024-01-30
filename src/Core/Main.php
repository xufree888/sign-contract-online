<?php

namespace SHSign\Core;

use SHSign\Kernel\BasicSHSign;
use SHSign\Tools\Cache;

class Main extends BasicSHSign {

    /**
     * access token
     * @var string
     */
    private $_token = '';

    public function setToken($token) {
        $this->_token = $token;
    }

    /**
     * 获取access token
     *
     * @return mixed
     * @throws \ErrorException
     * Author: DQ
     */
    public function getAccessToken() {
        $url  = $this->getUrl('/oauth/v2/token');
        $data = $this->config->merge(['grant_type' => 'password', 'scope' => 'read']);

        return $this->httpPostJson($url, $data);
    }

    /**
     * 获取token
     *
     * @return null
     * @throws \ErrorException
     * @throws \SHSign\Exceptions\LocalCacheException
     * Author: DQ
     */
    public function getToken() {
        $token = $this->_token;
        if (empty($token)) {
            $token = Cache::getCache('token');
            if (empty($token)) {
                $data = $this->getAccessToken();
                Cache::setCache('token', $data['access_token'], intval($data['expires_in']) - 20);
                $token = $data['access_token'];
            }
            $this->_token = $token;
        }

        return $token;
    }

    /**
     * 获取 提供申请资格证书信息至运营公司
     *
     * @param string $cardNo
     *
     * @return mixed
     * @throws \ErrorException
     * Author: DQ
     */
    public function getTransactionInfo($cardNo = '') {
        $url     = $this->getUrl('/business/commonhouse/openapi/getTransactionInfo');
        $data    = [
            'access_token' => $this->getToken(),
            'companyCode'  => $this->config['companyCode'],
            'companyName'  => $this->config['companyName'],
            'cardNo'       => $cardNo
        ];
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     * 运营公司统一从网签平台获取合同号
     *
     * @param string $pId               项目ID
     * @param string $projectName       项目名称
     * @param string $contractAttribute 合同属性：个人：01，单位：02
     *                                  Author: DQ
     */
    public function getContractSeqNo($pId = '', $projectName = '', $contractAttribute = '01') {
        $url  = $this->getUrl('/business/commonhouse/openapi/getContractSeqNo');
        $data = [
            'access_token'      => $this->getToken(),
            'companyId'         => $this->config['companyId'],
            'companyName'       => $this->config['companyName'],
            'PID'               => $pId,
            'projectName'       => $projectName,
            'contractAttribute' => $contractAttribute,
        ];

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     * 运营公司上传合同信息
     *
     * @param string $pId
     * @param string $projectName
     * @param string $HID                 房源ID
     * @param array  $contractInfo        合同信息
     * @param array  $uploadTenantBean    承租人信息
     * @param array  $uploadHouseInfoBean 房屋信息
     * @param array  $togetherList        同住人信息
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \SHSign\Exceptions\LocalCacheException
     * Author: DQ
     */
    public function uploadContract($pId = '', $projectName = '', $HID = '', $contractInfo = [], $uploadTenantBean = [], $uploadHouseInfoBean = [], $togetherList = []) {
        $url = $this->getUrl('/business/commonhouse/openapi/uploadContract');

        $emptyTenantBean  = [
            "transactionNo"    => "",
            "name"             => "",
            "sex"              => "",
            "nation"           => "",
            "birthday"         => "",
            "address"          => "",
            "cardNo"           => "",
            "age"              => "",
            "registeredRight"  => "",
            "registeredType"   => "",
            "mobile"           => "",
            "telephone"        => "",
            "email"            => "",
            "education"        => "",
            "livingCardNo"     => "",
            "livingCardTime"   => "",
            "bankAccount"      => "",
            "bank"             => "",
            "bankProxy"        => "",
            "companyName"      => "",
            "contact"          => "",
            "taxpayerAddress"  => "",
            "taxpayerMobile"   => "",
            "companyPartition" => "",
            "workPartition"    => "",
            "zip"              => "",
            "remark"           => "",
            "livingStartTime"  => "",
            "livingStatus"     => ""
        ];
        $uploadTenantBean = array_merge($emptyTenantBean, $uploadTenantBean);

        $data = [
            'companyId'           => $this->config['companyId'],
            'companyName'         => $this->config['companyName'],
            'PID'                 => $pId,
            'HID'                 => $HID,
            'projectName'         => $projectName,
            'uploadTenantBean'    => $uploadTenantBean,
            'uploadHouseInfoBean' => $uploadHouseInfoBean,
        ];
        if ($togetherList) {
            $data['togetherList'] = $togetherList;
        }
        $data    = array_merge($data, $contractInfo);
        $headers = [
            'Content-Type' => 'application/json',
            'access_token' => $this->getToken(),
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     * 退租申请
     *
     * @param string $pId           项目ID
     * @param string $projectName   项目名称
     * @param        $HID           房源ID
     * @param        $contractNO    合同编号
     *
     * @return mixed
     * @throws \ErrorException
     * Author: DQ
     */
    public function returnContract($pId = '', $projectName = '', $HID, $contractNO) {
        $url     = $this->getUrl('/business/commonhouse/openapi/returnContrart');
        $data    = [
            'access_token' => $this->getToken(),
            'companyId'    => $this->config['companyId'],
            'companyName'  => $this->config['companyName'],
            'PID'          => $pId,
            'projectName'  => $projectName,
            'HID'          => $HID,
            'contractNO'   => $contractNO
        ];
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     * 退租确认（改变合同信息和改变房源信息）
     *
     * @param string $pId                 项目ID
     * @param string $projectName         项目名称
     * @param string $HID                 房源ID
     * @param string $contractNO          合同编号
     * @param array  $returnhouseInfoBean 房源信息
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \SHSign\Exceptions\LocalCacheException
     * Author: DQ
     */
    public function confirmReturnContrart($pId = '', $projectName = '', $HID = '', $contractNO = '', $returnhouseInfoBean = []) {
        $url     = $this->getUrl('/business/commonhouse/openapi/confirmReturnContrart');
        $data    = [
            'companyId'           => $this->config['companyId'],
            'companyName'         => $this->config['companyName'],
            'PID'                 => $pId,
            'projectName'         => $projectName,
            'HID'                 => $HID,
            'contractNO'          => $contractNO,
            'returnhouseInfoBean' => $returnhouseInfoBean,
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'access_token' => $this->getToken(),
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     * 续租确认并改变房源状态
     *
     * @param string $pId         项目ID
     * @param string $projectName 项目名称
     * @param string $HID         房源ID
     * @param string $contractNO  合同编号
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \SHSign\Exceptions\LocalCacheException
     * Author: DQ
     */
    public function confirmResignContract($pId = '', $projectName = '', $HID = '', $contractNO = '') {
        $url     = $this->getUrl('/business/commonhouse/openapi/confirmResignContract');
        $data    = [
            'access_token' => $this->getToken(),
            'companyId'    => $this->config['companyId'],
            'companyName'  => $this->config['companyName'],
            'PID'          => $pId,
            'projectName'  => $projectName,
            'HID'          => $HID,
            'contractNO'   => $contractNO
        ];
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

    /**
     *
     * 单位合同的履行期间的承租人（主申请人）或同住人（配偶/子女）变更接口
     * 仅接受单位合同请求
     *
     * @param string $pId           项目ID
     * @param string $projectName   项目名称
     * @param string $HID           房源ID
     * @param string $contractNO    合同编号
     * @param array  $oldTenantList 原承租人信息列表
     * @param array  $newTenantList 新承租人信息列表
     *
     * @return mixed
     * @throws \ErrorException
     * @throws \SHSign\Exceptions\LocalCacheException
     * Author: DQ
     */
    public function changeTenant($pId = '', $projectName = '', $HID = '', $contractNO = '', $oldTenantList = [], $newTenantList = []) {
        $url = $this->getUrl('/business/commonhouse/openapi/changeTenant');
        $old = [];
        if (!isset($oldTenantList[0])) {
            $old[0] = $oldTenantList;
        } else {
            $old = $oldTenantList;
        }
        $new = [];
        if (!isset($newTenantList[0])) {
            $new[0] = $newTenantList;
        } else {
            $new = $newTenantList;
        }

        $data    = [
            'companyId'         => $this->config['companyId'],
            'companyName'       => $this->config['companyName'],
            'PID'               => $pId,
            'projectName'       => $projectName,
            'HID'               => $HID,
            'contractNO'        => $contractNO,
            'contractAttribute' => '02',
            'oldTenantList'     => $old,
            'newTenantList'     => $new
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'access_token' => $this->getToken(),
        ];

        return $this->httpPostJson($url, $data, $headers);
    }

}