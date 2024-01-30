<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestContractSeqNo extends BasicTest {

    /**
     * 获取合同编号
     *
     * Author: DQ
     */
    public function testGetContractSeqNo() {
        try {
            $mainLib = new Main($this->_config);
            $return  = $mainLib->getContractSeqNo($this->_data['PID'], $this->_data['projectName']);
            $this->assertNotEmpty($return['contractNo'], '运营公司统一从网签平台获取合同号');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}