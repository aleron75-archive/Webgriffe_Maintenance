<?php
class Webgriffe_Maintenance_Helper_Data extends Mage_Core_Helper_Data {

    protected function _log($msg) {
        Mage::log($msg, null, 'Webgriffe_Maintenance.log', Mage::getStoreConfig('system/wg_maintenance/debug'));
    }

    protected function _getCurrentIp() {
        return Mage::app()->getRequest()->getClientIp();
    }

    public function isActive() {
        return Mage::getStoreConfig('system/wg_maintenance/active');
    }

    public function bypassIp() {
        $bypassedIps = explode(',', Mage::getStoreConfig('system/wg_maintenance/bypassed_ips'));

        if ($bypassedIps && !empty($bypassedIps)) {
            for ($i = 0, $count = count($bypassedIps); $i < $count; $i ++) {
                $bypassedIps[$i] = trim($bypassedIps[$i]);
            }
        }

        return ($this->_check($this->_getCurrentIp(), $bypassedIps));
    }

    protected function _check($ip, $arr_ip) {
        $this->_log(sprintf("Check incoming IP %s against following IPs: %s", $ip, implode(' - ', $arr_ip)));
        foreach ($arr_ip as $test_ip) {
            $test_ip = str_replace('\*','\d+',preg_quote($test_ip));
            if (preg_match("/^".$test_ip."$/", $ip)) {
                $this->_log(sprintf('Matched "%s" against "%s"', $ip, $test_ip));
                return true;
            }
        }
        return false;
    }

    public function getMaintenancePageId() {
        return Mage::getStoreConfig('system/wg_maintenance/cms_page');
    }

    public function getCustomMessage() {
        return Mage::getStoreConfig('system/wg_maintenance/msg');
    }

    public function getShowMode() {
        return Mage::getStoreConfig('system/wg_maintenance/show');
    }

    public function isBasicAuthActive()
    {
        return Mage::getStoreConfig('system/wg_basicauth/active');
    }

    public function getBasicAuthUsername()
    {
        return Mage::getStoreConfig('system/wg_basicauth/username');
    }

    public function getBasicAuthPassword()
    {
        return Mage::getStoreConfig('system/wg_basicauth/password');
    }
}
