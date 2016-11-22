<?php
/**
 * Helper para formatear en json paginador
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @author Ing. Eduardo Ortiz
 * 
 */
namespace Adteam\Core\Common;

use Zend\Stdlib\Parameters;
use Zend\Paginator\Paginator;
use ZF\Apigility\Doctrine\Server\Paginator\Adapter\DoctrineOrmAdapter;
use Doctrine\ORM\QueryBuilder;

class JsonPaginator{
    
    /**
     *
     * @var int 
     */
    protected $page;

   
    /**
     *
     * @var Zend\Paginator\Paginator
     */
    protected $paginator;
    
    
    /**
     * See the "Sub-key: collection_query_whitelist (optional)" 
     * section of the zf-rest documentation for more information 
     * on how to use collection_query_whitelist.
     * 
     * @param Zend\Stdlib\Parameters $params
     * @return object
     */
    public function setParams(Parameters $params)
    {
        $this->params = $params->toArray();
        return $this;
    }

    /**
     * DoctrineORM Adapter
     * 
     * @param Doctrine\ORM\QueryBuilder $query
     * @param array $params
     */
    public function setAdapterPaginatorOrm(QueryBuilder $query,$params)
    {
        $this->setParams($params);
        $this->adapter = new DoctrineOrmAdapter($query);
        $this->adapter->setUseOutputWalkers(false); 
        $this->setCollection(); 
    }
    
    /**
     * Paginator Settings
     * @todo Implementa desde el config el count por paginas
     */
    public function setCollection()
    {
        $page = isset($this->params['page'])?$this->params['page']:1;
        $this->paginator = new Paginator($this->adapter);
        $this->paginator->setItemCountPerPage(25);        
        $this->paginator->setCurrentPageNumber($page); 
    }
    
    /**
     * 
     * @return Zend\Paginator\Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getPageCount()
    {
        return $this->paginator->count();
    }
    
    /**
     * Response in array
     * 
     * @return array
     */
    public function getResponse()
    {
        return [
            'data' => $this->paginator->getCurrentItems(),
            'page_count' => $this->getPageCount(),
            'page_size' => $this->paginator->getItemCountPerPage(),
            'total_items' => $this->paginator->getTotalItemCount(),
            'page' => $this->paginator->getCurrentPageNumber()
        ];        
    }
}
