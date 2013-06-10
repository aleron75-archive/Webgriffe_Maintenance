<?php

class Webgriffe_Maintenance_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

    /**
     * Initialize Controller Router
     *
     * @param Varien_Event_Observer $observer
     */
    public function initControllerRouters($observer) {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();
        $front->addRouter('maintenance', $this);
    }

    public function match(Zend_Controller_Request_Http $request) {
        $helper = Mage::helper('wgmnt');
        if ($helper->isActive() && !$helper->bypassIp()) {
            $adminFrontName = (string) Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
            $str_pos = strpos($request->getPathInfo(), $adminFrontName);
            if (Mage::app()->getStore()->isAdmin() || $str_pos === 1) {
                return false;
            }

            $requestParams = array();

            $show = Mage::helper('wgmnt')->getShowMode();

            switch ($show) {

                case Webgriffe_Maintenance_Model_System_Config_Source_Show::MODE_CMS:
                    $moduleName = 'cms';
                    $controllerName = 'page';
                    $actionName = 'view';
                    $pageId = $helper->getMaintenancePageId();
                    $requestParams['page_id'] = $pageId;
                    break;

                case Webgriffe_Maintenance_Model_System_Config_Source_Show::MODE_MSG:
                    $moduleName = 'wgmnt';
                    $controllerName = 'index';
                    $actionName = 'index';
                    break;

            }

            foreach ($requestParams as $paramKey => $paramVal) {
                $request->setParam($paramKey, $paramVal);
            }

            $request
                    ->setModuleName($moduleName)
                    ->setControllerName($controllerName)
                    ->setActionName($actionName);
        }
        return false;
    }

}
