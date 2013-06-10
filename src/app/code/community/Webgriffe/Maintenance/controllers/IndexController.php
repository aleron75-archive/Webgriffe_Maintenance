<?php
class Webgriffe_Maintenance_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $body = Mage::helper('wgmnt')->getCustomMessage();
        $this->getResponse()->setBody($body);
    }
}
