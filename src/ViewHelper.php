<?php
namespace Adteam\Core\Common;
/**
 * Description of ViewHelper
 *
 * @author dev
 */
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\ViewModel;

class ViewHelper {

    /**
     *
     * @var Zend\View\Helper\ViewModel
     */
    protected $ViewHelperManager;
    
    /**
     * 
     * @param ServiceManager $services
     */
    public function __construct(ServiceManager $services) {
        $this->service = $services;
        $this->ViewHelperManager = $services->get('ViewHelperManager');
    }  
	
    public function __invoke($path) {
        return $this->getUrl($path);
    }
    
    /**
     * construye url
     * 
     * @param type $path
     * @return type
     */
    public function getUrl($path='/')
    {
        $view = $this->ViewHelperManager->get(ViewModel::class)->getView();
        return $view->serverUrl().$view->basePath($path);        
    }    
}
