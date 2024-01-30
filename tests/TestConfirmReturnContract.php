<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestConfirmReturnContract extends BasicTest {

    /**
     * 退租确认
     *
     *
     * Author=> DQ
     */
    public function testConfirmReturnContrart() {
        try {
            $mainLib             = new Main($this->_config);
            $pid                 = '0285';
            $projectName         = '龙南佳苑';
            $HID                 = '02852019120225149_31';
            $contractNO          = '202001402800100042';
            $returnhouseInfoBean = [
                "area"          => "61.71",
                "buildingNo"    => "4",
                "buildingFloor" => "1",
                "roomNo"        => "702",
                "roomType"      => "1",
                "monthlyRent"   => "1",
                "actualRent"    => "1",
                "category"      => "公租房",
                "openingDate"   => "702"
            ];
            $return              = $mainLib->confirmReturnContrart($pid, $projectName, $HID, $contractNO, $returnhouseInfoBean);
            $this->assertNotEmpty($return, '退租确认 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}