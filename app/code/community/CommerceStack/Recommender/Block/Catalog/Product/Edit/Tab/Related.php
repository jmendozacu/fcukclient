<?php
class CommerceStack_Recommender_Block_Catalog_Product_Edit_Tab_Related extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Related
{
    public function isReadonly()
    {
        return true;
    }
    
    public function getSelectedRelatedProducts()
    {   
        Mage::registry('current_product')->getLinkInstance()->useLinkSourceCommerceStack();
        $currentProduct = Mage::registry('current_product');
        $products = array();
        
        // If this product has a parent, use that instead since we do not produce recommendations
        // for children
        $configurableProductModel = Mage::getModel('catalog/product_type_configurable');
        $parentIdArray = $configurableProductModel->getParentIdsByChild($currentProduct->getId());
        if(count($parentIdArray) > 0)
        {
            $currentProduct = Mage::getModel('catalog/product')->load($parentIdArray[0]);
        }
        
        foreach ($currentProduct->getRelatedProducts() as $product) {
            $products[$product->getId()] = array('position' => $product->getPosition());
        }
        
        return $products;
    }

    protected function _prepareCollection()
    {
        $currentProduct = $this->_getProduct();
        
        // If this product has a parent, use that instead since we do not produce recommendations
        // for children
        $configurableProductModel = Mage::getModel('catalog/product_type_configurable');
        $parentIdArray = $configurableProductModel->getParentIdsByChild($currentProduct->getId());
        if(count($parentIdArray) > 0)
        {
            $currentProduct = Mage::getModel('catalog/product')->load($parentIdArray[0]);
        }
        
        $collection = Mage::getModel('catalog/product_link')->useRelatedLinks()
            ->useLinkSourceCommerceStack()
            ->getProductCollection()
            ->setProduct($currentProduct)
            ->addAttributeToSelect('*');
            
        if ($this->isReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = array(0);
            }
            $collection->addFieldToFilter('entity_id', array('in' => $productIds));
        }

        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $col = $this->getColumn('position');
        $col->setEditable(false);
    }
}