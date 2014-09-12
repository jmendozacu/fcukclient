<?php

class MagicToolbox_MagicZoom_MagicZoom_CacheController extends Mage_Adminhtml_Controller_Action {

    public function cleanAction() {
        try {
            $cachePath = Mage::getBaseDir('media').DS.'magictoolbox'.DS.'magiczoom'.DS.'cache';
            if(file_exists($cachePath)) {
                $this->cleanUpDir($cachePath);
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__('Magic Zoom images cache has been cleaned.')
            );
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addException(
                $e,
                Mage::helper('adminhtml')->__('An error occurred while clearing the Magic Zoom images cache.')
            );
        }
        $this->_redirect('*/cache/index');
    }

    protected function cleanUpDir($path, $remove = false) {
        if($dir = @opendir($path)) {
            while(($file = readdir($dir))!==false) {
                if($file == '.' || $file == '..') {
                    continue;
                }
                if(is_dir($path.'/'.$file)) {
                    $this->cleanUpDir($path.'/'.$file, true);
                } else {
                    unlink($path.'/'.$file);
                }
            }
            closedir($dir);
            if($remove) rmdir($path);
        }
    }

}
