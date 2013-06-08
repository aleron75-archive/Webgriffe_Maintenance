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
//            The following allows you to user your own controller/action instead of CMS Page
//            $moduleName = 'wgmnt';
//            $controllerName = 'index';
//            $actionName = 'index';
            $moduleName = 'cms';
            $controllerName = 'page';
            $actionName = 'view';
            $pageId = $helper->getMaintenancePageId();
            $request
                    ->setParam('page_id', $pageId)
                    ->setModuleName($moduleName)
                    ->setControllerName($controllerName)
                    ->setActionName($actionName);
        }
        return false;
    }

}
