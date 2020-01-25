<?php /** 
* Moogento
* 
* SOFTWARE LICENSE
* 
* This source file is covered by the Moogento End User License Agreement
* that is bundled with this extension in the file License.html
* It is also available online here:
* https://moogento.com/License.html
* 
* NOTICE
* 
* If you customize this file please remember that it will be overwritten
* with any future upgrade installs. 
* If you'd like to add a feature which is not in this software, get in touch
* at moogento.com for a quote.
* 
* ID          pe+sMEDTrtCzNq3pehW9DJ0lnYtgqva4i4Z=
* File        IndexController.php
* @category   Moogento
* @package    noMoreSpam
* @copyright  Copyright (c) 2017 Moogento <info@moogento.com> / All rights reserved.
* @license    https://moogento.com/License.html
*/ ?>
<?php
class Moogento_NoMoreSpam_ActiveController extends Mage_Core_Controller_Front_Action
{
    public function activeAction(){
		$this->loadLayout();
		$id = $this->getRequest()->getParam("id");
		$review  = Mage::getModel('review/review')->load($id);
		try{
			$review->setStatusId(Mage_Review_Model_Review::STATUS_APPROVED);
			Mage::getSingleton('core/session')->addSuccess('The review has been approved'); 
			$review->save();
		} catch(Exception $e){
			Mage::getSingleton('core/session')->addError('The review has NOT been approved'); 
		}
		$this->renderLayout();
	}
	
	public function deleteAction(){
		$this->loadLayout();
		$id = $this->getRequest()->getParam("id");
		$review  = Mage::getModel('review/review')->load($id);
		$review->delete();
		$this->renderLayout();
	}
}
