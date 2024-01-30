<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestReturnContract extends BasicTest {

    /**
     * 退租申请
     *
     *
     * Author=> DQ
     */
    public function testUploadContract() {
        try {
            $mainLib     = new Main($this->_config);
            $pid         = '0285';
            $projectName = '龙南佳苑';
            $HID         = '02852019120225149_31';
            $contractNO  = '202001402800100042';
            $return = $mainLib->returnContract($pid, $projectName, $HID, $contractNO);
            $this->assertNotEmpty($return, '退租申请 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}