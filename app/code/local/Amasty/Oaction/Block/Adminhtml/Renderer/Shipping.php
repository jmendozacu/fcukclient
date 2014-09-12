<?php

/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2011 Amasty (http://www.amasty.com)
* @package Amasty_Oaction
*/
class Amasty_Oaction_Block_Adminhtml_Renderer_Shipping extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
     public function render(Varien_Object $row)
    {
		
        $html = '';
        
	
        $order = Mage::getModel('sales/order')->load($row->getId());
	    if ($order->canShip()) {
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$query = 'SELECT * FROM ' 
			. Mage::getSingleton('core/resource')->getTableName('order_ship_track')
			. ' where order_id = '.$row->getId();
			$results = $read->fetchAll($query);
			if(!empty($results)){
				$html =  $this->__('Carrier:');
                $html .= '<br /><strong>' .$results[0]['carrier'] . '</strong><br />';
                $html .= $this->__('Tracking Number:');
                $html .= '<br /><strong>' .$results[0]['track_no']. '</strong>';
				
				
				
			}else{
			$default = Mage::getStoreConfig('amoaction/ship/carrier');
			$payment =$order->getPayment();
			//echo '<pre>';
			 $shippingId = $order->getShippingAddress()->getId();
    // Get shipping address data using the id
    $address = Mage::getModel('sales/order_address')->load($shippingId);
			//print_r($address->getData());
			//exit; 
            $html = $this->__('Carrier:') . '<br />';
            $html .= '<select name="trackingcarrier" onchange="getTrack(this.value,'.$row->getId().' , \''.$payment->getData('method').'\','.$address->getData('postcode').')" class="amasty-carrier" rel="'.$row->getId().'" style="width:90%">';
            foreach ($this->getCarriers($row->getIncrementId()) as $k => $v){
                $selected = '';
                if ($default == $k){
                    $selected = 'selected="selected"';
                }
                $html .= sprintf('<option value="%s" %s>%s</option>', $k, $selected, $v);
            }
            $html .= '</select><br />';
			$html .= $this->__('Tracking Number:') . '<br />';
            $html .= '<input rel="'.$row->getId().'" id="trackno_'.$row->getId().'" class="input-text amasty-tracking" name="trackingnumber" value="" />';
			
				
				
			}
			
			
            
           
			
			
			
			
			
        }    
        else {
			
            
            $field = 'track_number';
            if (version_compare(Mage::getVersion(), '1.5.1.0') <= 0){
                $field = 'number';
            }            
            
            $collection = Mage::getModel('sales/order_shipment_track')
                ->getCollection()
                ->addAttributeToSelect($field)
                ->addAttributeToSelect('title')
                ->setOrderFilter($row->getId());
                
            $numbers = array();
            $carriers = array();
            foreach ($collection as $track) {
                $numbers[]  = $track->getData($field);
                $carriers[] = $track->getTitle();
            }

            if($carriers){
                $html =  $this->__('Carrier:');
                $html .= '<br /><strong>' . implode(', ', $carriers) . '</strong><br />';
                $html .= $this->__('Tracking Number:');
                $html .= '<br /><strong>' .implode(', ', $numbers). '</strong>';
            }
        }

        return $html;
    }
    
    public function getFilter()
    {
        return false;
    }
    
    private function getCarriers($code)
    {
        $hash = array();
        
        //convert array to hash
        $vals = Mage::getModel('sales/order_shipment_api_v2')->getCarriers($code);
        foreach ($vals as $v){
            $hash[$v['key']] = $v['value'];
        }
        
        // add custom carrier as dropdown option
        $title = Mage::getStoreConfig('amoaction/ship/title');
        if ($title && isset($hash['custom'])){
            $hash['custom'] = $title;
        } 
        
        return $hash;
    }
}