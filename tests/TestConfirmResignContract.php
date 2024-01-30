<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestConfirmResignContract extends BasicTest {

    /**
     * 续租确认并改变房源状态
     *
     *
     * Author=> DQ
     */
    public function testConfirmResignContract() {
        try {
            $mainLib     = new Main($this->_config);
            $pid         = '0285';
            $projectName = '龙南佳苑';
            $HID         = '02852019120225149_31';
            $contractNO  = '202001402800100042';
            $return = $mainLib->confirmResignContract($pid, $projectName, $HID, $contractNO);
            $this->assertNotEmpty($return, '续租确认并改变房源状态 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}