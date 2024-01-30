<?php

namespace SHSign\tests;

use SHSign\Core\Main;

class TestUploadContract extends BasicTest {

    /**
     * 上传合同
     *
     *
     * Author=> DQ
     */
    public function testUploadContract() {
        try {
            $mainLib     = new Main($this->_config);
            $pid         = $this->_data['PID'];
            $projectName = $this->_data['projectName'];
            $HID         = $this->_data['HID'];

            $contractInfo = $this->_data['contractInfo'];

            $uploadTenantBean    = $this->_data['uploadTenantBean'];
            $uploadHouseInfoBean = $this->_data['uploadHouseInfoBean'];;
            $togetherList = null;

            $return = $mainLib->uploadContract($pid, $projectName, $HID, $contractInfo, $uploadTenantBean, $uploadHouseInfoBean, $togetherList);
            $this->assertNotEmpty($return, '运营公司上传合同信息 错误');
        } catch (\Exception $e) {
            $this->assertEmpty($e->getMessage(), $e->getMessage());
        }
    }

}