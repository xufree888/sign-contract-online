<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestChangeTenant extends BasicTest {

    /**
     * 单位合同的履行期间的承租人变更接口
     *
     *
     * Author=> DQ
     */
    public function testUploadContract() {
        try {
            $mainLib       = new Main($this->_config);
            $pid           = '0285';
            $projectName   = '龙南佳苑';
            $HID           = '02852019120225149_31';
            $contractNO   = '201901300020100010';
            $oldTenantList = [
                "cardno"        => "310012199011212521",
                "livingEndTime" => "2019-12-12"
            ];
            $newTenantList = [
                "tenantType"          => "1",
                "cardno"              => "310012199011212521",
                "name"                => "张三",
                "sex"                 => "男",
                "nation"              => "汉",
                "birthDay"            => "2019-08-28",
                "livingadreess"       => "上海",
                "age"                 => "30",
                "nativeAddress"       => "山西",
                "nativeKind"          => "本市",
                "telphone"            => "65235236",
                "email"               => "1@1.com",
                "educational"         => "本科",
                "livingCard"          => "上海",
                "livingCardValidTime" => "2029-08-28",
                "workPartition"       => "工人",
                "mobile"              => "13917331290",
                "ration"              => "夫妻",
                "livingStartTime"     => "2019-12-12",
                "livingStatus"        => "在租"
            ];
            $return = $mainLib->changeTenant($pid, $projectName, $HID, $contractNO, $oldTenantList, $newTenantList);
            $this->assertNotEmpty($return, '单位合同的履行期间的承租人变更接口 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}