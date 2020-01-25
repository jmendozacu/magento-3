<?php

class Sciencell_PaymentMethodFilter_Model_Observer extends Varien_Event_Observer
{
   public function __construct()
   {
 
   }
   
   public function filterPaymentMethod(Varien_Event_Observer $observer)
   {

      $method = $observer->getEvent()->getMethodInstance();
      $isLoggedIn = Mage::getSingleton("customer/session")->isLoggedIn();
      $result = $observer->getEvent()->getResult();

      if ($isLoggedIn && $method->getCode() == "purchaseorder")
      {
         $customer = Mage::getSingleton("customer/session")->getCustomer();
         $po_allowed = $customer->getData("po_allowed");
         $result->isAvailable = $po_allowed;
      }

   }
}

?>