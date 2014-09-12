<?php
class Fcuk_Pressreleases_Block_Adminhtml_Presscoverage_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("presscoverage_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("pressreleases")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("pressreleases")->__("Item Information"),
				"title" => Mage::helper("pressreleases")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("pressreleases/adminhtml_presscoverage_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
