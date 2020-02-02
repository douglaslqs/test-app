<?php
namespace Administrator\Service;

use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;

class ApiRequestService
{
    private $objRequest;
    private $arrParameters;
    private $arrUrl;
    private $strMethod = 'GET';
    private $strUri;
    private $objClient;

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const URI_API = 'http://test-api.local/';
    const URI_API_SOURCE = 'http://test-api-source.local/';


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->objRequest = new Request();
        $this->objClient = new Client();
    }

    /**
     * Efetue request to API
     * @return array data returned api
     */
    public function request()
    {
        $this->objRequest->setUri($this->getUri());
        $this->objRequest->setMethod($this->getMethod());
        $arrHeader = array();
        if ($this->getMethod() === self::METHOD_POST) {
            $arrHeader['Content-Type'] = 'application/json';
            $this->objRequest->setPost(new Parameters($this->getParameters()));
            $this->objRequest->setPost(new Parameters($this->getParameters()));
        } else if ($this->getMethod() === self::METHOD_GET) {
            $arrHeader['Accept'] = '*/*';
            $this->objRequest->setQuery(new Parameters($this->getParameters()));
        } else {
            $arrHeader['Accept'] = '*/*';
            $arrHeader['Content-Type'] = 'application/json';
            $this->objRequest->setContent(json_encode($this->getParameters()));
        }
        $this->objRequest->getHeaders()->addHeaders($arrHeader);
        try {
            $response = $this->objClient->dispatch($this->objRequest);
        } catch (Exception $e) {
            $response = $e->getMessage();
        }
        //return json_decode(var_export($response));
        return json_decode($response->getBody(), true);
    }

    public function requestFromCurl()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->getUri(),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $this->getMethod(),
          CURLOPT_POSTFIELDS => $this->getParameters()
        ));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        if($response === false)
        {
            $response = 'Curl error: ' . curl_error($curl);
        } else {
            $response = true;
        }
        curl_close($curl);
        echo json_encode($response);exit;
        return $response;
    }

    public function setUri($strUri)
    {
        $this->strUri = $strUri;
    }

    public function getUri()
    {
        return $this->strUri;
    }

    public function setMethod($strMethod)
    {
        $this->strMethod = $strMethod;
    }

    public function getMethod()
    {
        return $this->strMethod;
    }

    public function setParameters(array $arrParameters)
    {
        $this->arrParameters = $arrParameters;
    }

    public function getParameters()
    {
        return $this->arrParameters;
    }
}