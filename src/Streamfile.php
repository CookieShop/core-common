<?php
/**
 * Helper para hacer stream
 * 
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @author Ing. Eduardo Ortiz
 * 
 */
namespace Adteam\Core\Common;

use Zend\Http\Response\Stream;
use Zend\Http\Headers;

class Streamfile
{
    /**
     * 
     * @return Zend\Http\Response\Stream
     */
    public function getStreamForceDownloadFromFile($filePath)
    {
        $response = new Stream();
        $headers = new Headers();
        $finfo = new \finfo(FILEINFO_MIME);
        $response->setStream(fopen($filePath, 'r'));
        $response->setStatusCode(200);
        $response->setStreamName(basename($filePath));
        $headers->addHeaders(array(
            'Content-Type'        => $finfo->file($filePath),
            'Content-Disposition' => 'attachment; filename="'
            .basename($filePath).'"',
            'Content-Length'      => filesize($filePath)
        ));
        $response->setHeaders($headers);
        return $response;        
    }
           
}
