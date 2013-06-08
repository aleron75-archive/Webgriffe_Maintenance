<?php
class Webgriffe_Maintenance_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->getResponse()->setBody('Under Maintenance');
    }
}
