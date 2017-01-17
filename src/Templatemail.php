<?php

/**
 *   $render = new RenderTemplate();
 *   $render->setServiceManager($this->sm);
 *   $render->getContent(['template'=>'hola.phtml','hola'=>'hola Mundo'], 'hola.phtml');
 */

namespace Adteam\Core\Common;

use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of Templatemail
 *
 * @author dev
 */
class Templatemail {
    /**
     * @var ServiceManager
     */
    protected $serviceManager; 
    
    /**
     * Obtiene Service Manager
     * 
     * @return type
     */
    public function getServiceManager(){
        return $this->serviceManager;
    }

    /**
     * Inyecta Service Manager
     * 
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @return \Cscore\Service\Cmf
     */
    public function setServiceManager(ServiceManager $serviceManager){
        $this->serviceManager = $serviceManager;
        return $this;
    }
    
    /**
     * 
     * @param type $_vars
     */
    public function getContent($_vars,$template){
        $config = $this->getServiceManager()->get('config');
        $basePath = $config['path'];
        $renderer = new PhpRenderer();
        $renderer->resolver()->addPath($basePath.'/data/template');
        $renderer->setHelperPluginManager($this->getServiceManager()
                ->get('ViewHelperManager'));
        $model = new ViewModel();
        $model->setVariable('items' , $_vars);
        $model->setTemplate($template); 
        $textContent = $renderer->render($model);
        $filename = $basePath.'/data/email/'.$_vars['render'];
        if(file_exists($filename)){
            file_put_contents($filename, $textContent);
        }        
        return $this->getParams($_vars['from'], $_vars['subject']);
    }
    
    /**
     * 
     * @return type
     */
    public function getParams($from,$subject)
    {
        return  [
            'template'=>  $this->getNameFilehash(),
            'from'=>$from,
            'cc'=>'',
            'subject'=>$subject,
            
        ];
    }
    
    /**
     * 
     * @return type
     */
    private function getNameFilehash(){
        return hash('sha1', uniqid(time(),true)).'.phtml';
    }    
}
