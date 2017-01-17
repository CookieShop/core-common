<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Adteam\Core\Common\Import\Csv;

/**
 * Description of ImportFactory
 *
 * @author dev
 */
use Adteam\Core\Common\Import\Csv\Import;

class ImportFactory 
{
    /**
     *
     * @var type 
     */
    protected $config;
    
    /**
     * 
     * @return type
     */
    function getConfig() {
        return $this->config['adteam-core-common'];
    }

    /**
     * 
     * @param type $config
     */
    function setConfig($config) {
        $this->config = $config;
    }

    /**
     * 
     * @param type $services
     * @return \Application\Import\ImportFactory
     */
    public function __invoke($services) {
        $config = $services->get('config');
        $this->setConfig($config);
        return $this;
    }
   
    /**
     * Imports CSV file and returns its content
     * 
     * @param  array $data
     * @return string
     * @throws \InvalidArgumentException
     */
    public function importCsv($data)
    {
        $config = $this->getConfig();
        $excepcion =  'Max of rows'.$config['maxfieldcsv'];        
        $import = new Import();
        $filename = $data['tmp_name'];
        $csv = $import->import(
                $filename,
                $config['useFirstRecordAsHeader'],
                $config['delimiter']
                );

        $rowsCount = $import->count();
        
        if ($rowsCount <= $config['maxfieldcsv'] and $rowsCount > 0) {
            return $csv;   
        }
        
        $excepcion = (0 === $rowsCount)
            ? 'Invalid_file_format_empty' 
            : 'Invalid_file_format_maxExceeded';
        
        throw new \InvalidArgumentException($excepcion);
    }
}
