<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestTransactionInfo extends BasicTest {

    /**
     * @todo 获取资格
     *
     * @throws \BYRobot\Exceptions\InvalidResponseException
     * @throws \ErrorExcep
     *
     */
    public function testGetTransactionInfo() {
        try {
            $mainLib = new Main($this->_config);
            $return  = $mainLib->getTransactionInfo($this->_data['card']);
            $this->assertNotEmpty($return, '提供申请资格证书信息至运营公司');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}