<?php

class Fcuk_Pressreleases_Adminhtml_DefaultbannerController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("pressreleases/defaultbanner")->_addBreadcrumb(Mage::helper("adminhtml")->__("Defaultbanner  Manager"),Mage::helper("adminhtml")->__("Defaultbanner Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Pressreleases"));
			    $this->_title($this->__("Manager Defaultbanner"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Pressreleases"));
				$this->_title($this->__("Defaultbanner"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("pressreleases/defaultbanner")->load($id);
				if ($model->getId()) {
					Mage::register("defaultbanner_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("pressreleases/defaultbanner");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Defaultbanner Manager"), Mage::helper("adminhtml")->__("Defaultbanner Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Defaultbanner Description"), Mage::helper("adminhtml")->__("Defaultbanner Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("pressreleases/adminhtml_defaultbanner_edit"))->_addLeft($this->getLayout()->createBlock("pressreleases/adminhtml_defaultbanner_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("pressreleases")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Pressreleases"));
		$this->_title($this->__("Defaultbanner"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("pressreleases/defaultbanner")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("defaultbanner_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("pressreleases/defaultbanner");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Defaultbanner Manager"), Mage::helper("adminhtml")->__("Defaultbanner Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Defaultbanner Description"), Mage::helper("adminhtml")->__("Defaultbanner Description"));


		$this->_addContent($this->getLayout()->createBlock("pressreleases/adminhtml_defaultbanner_edit"))->_addLeft($this->getLayout()->createBlock("pressreleases/adminhtml_defaultbanner_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						
				 //save image
		try{

if((bool)$post_data['default_banner']['delete']==1) {

	        $post_data['default_banner']='';

}
else {

	unset($post_data['default_banner']);

	if (isset($_FILES)){

		if ($_FILES['default_banner']['name']) {

			if($this->getRequest()->getParam("id")){
				$model = Mage::getModel("pressreleases/defaultbanner")->load($this->getRequest()->getParam("id"));
				if($model->getData('default_banner')){
						$io = new Varien_Io_File();
						$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('default_banner'))));	
				}
			}
						$path = Mage::getBaseDir('media') . DS . 'pressreleases' . DS .'defaultbanner'.DS;
						$uploader = new Varien_File_Uploader('default_banner');
						$uploader->setAllowedExtensions(array('jpg','png','gif'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						$destFile = $path.$_FILES['default_banner']['name'];
						$filename = $uploader->getNewFileName($destFile);
						$uploader->save($path, $filename);

						$post_data['default_banner']='pressreleases/defaultbanner/'.$filename;
		}
    }
}

        } catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
        }
//save image


						$model = Mage::getModel("pressreleases/defaultbanner")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Defaultbanner was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setDefaultbannerData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setDefaultbannerData($this->getRequest()->getPost());
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
						$model = Mage::getModel("pressreleases/defaultbanner");
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
				$ids = $this->getRequest()->getPost('banner_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("pressreleases/defaultbanner");
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
			$fileName   = 'defaultbanner.csv';
			$grid       = $this->getLayout()->createBlock('pressreleases/adminhtml_defaultbanner_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'defaultbanner.xml';
			$grid       = $this->getLayout()->createBlock('pressreleases/adminhtml_defaultbanner_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}