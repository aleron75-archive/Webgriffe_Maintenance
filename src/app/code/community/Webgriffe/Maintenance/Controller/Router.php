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
        } elseif ($helper->isBasicAuthActive()) {
            if (!isset($_SERVER['PHP_AUTH_USER'])) {
                header('WWW-Authenticate: Basic realm="My Realm"');
                header('HTTP/1.0 401 Unauthorized');
                echo '<response><error>Uh-oh, you need to enter the username and password.</error></response>';
                exit;
            } else {
                if (! $this->_isBasicAuthCorrect()) {
                    header('HTTP/1.0 401 Unauthorized');
                    echo '<response><error>Wrong username or password</error></response>';
                    exit;
                }
            }
        }

        return false;
    }

    protected function _isBasicAuthCorrect()
    {
        $helper = Mage::helper('wgmnt');

        if ($_SERVER['PHP_AUTH_USER'] != $helper->getBasicAuthUsername()) {
            return false;
        }

        if ($helper->getBasicAuthPassword() && $_SERVER['PHP_AUTH_PW'] != $helper->getBasicAuthPassword()) {
            return false;
        }

        return true;
    }
}
