<?php

class SilkSoftware_Superman_Adminhtml_SupermanController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("superman/superman")->_addBreadcrumb(Mage::helper("adminhtml")->__("Superman  Manager"),Mage::helper("adminhtml")->__("Superman Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Superman"));
			    $this->_title($this->__("Manager Superman"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Superman"));
				$this->_title($this->__("Superman"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("superman/superman")->load($id);
				if ($model->getId()) {
					Mage::register("superman_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("superman/superman");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Superman Manager"), Mage::helper("adminhtml")->__("Superman Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Superman Description"), Mage::helper("adminhtml")->__("Superman Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("superman/adminhtml_superman_edit"))->_addLeft($this->getLayout()->createBlock("superman/adminhtml_superman_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("superman")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Superman"));
		$this->_title($this->__("Superman"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("superman/superman")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("superman_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("superman/superman");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Superman Manager"), Mage::helper("adminhtml")->__("Superman Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Superman Description"), Mage::helper("adminhtml")->__("Superman Description"));


		$this->_addContent($this->getLayout()->createBlock("superman/adminhtml_superman_edit"))->_addLeft($this->getLayout()->createBlock("superman/adminhtml_superman_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("superman/superman")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Superman was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setSupermanData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setSupermanData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("superman/superman");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('customer_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("superman/superman");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'superman.csv';
			$grid       = $this->getLayout()->createBlock('superman/adminhtml_superman_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'superman.xml';
			$grid       = $this->getLayout()->createBlock('superman/adminhtml_superman_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
