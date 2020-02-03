<?php
/**
 * @link      http://github.com/zendframework/Administrator for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administrator\Service\ApiRequestService;

class UserController extends AbstractActionController
{
    private $logger;
    private $objApiRequest;

    public function __construct(ApiRequestService $objApiRequest)
    {
    	$this->objApiRequest = $objApiRequest;
    }

   	public function indexAction()
   	{
        try {
            $this->objApiRequest->setUri(ApiRequestService::URI_API.'users');
            $arrData['data'] = $this->objApiRequest->request();
        } catch (Exception $e) {
            $arrData['error'] = $e->getMessage();
        }
        $view = new ViewModel($arrData);
        return $view;
   	}

    public function postsAction()
    {
        $id = $this->params()->fromQuery('id');
        $id = !empty($id) ? $id : 0;
        $this->objApiRequest->setUri(ApiRequestService::URI_API.'users/'.$id.'/posts');
        $this->objApiRequest->setMethod(ApiRequestService::METHOD_GET);
        $this->objApiRequest->setParameters(array('userId'=> $id));
        try {
            $arrData['data'] = $this->objApiRequest->request();
        } catch (Exception $e) {
            $arrData['error'] = $e->getMessage();
        }
        $view = new ViewModel($arrData);
        return $view;
    }

    public function getUserAction()
    {
        $id = $this->params()->fromPost('id');
        $this->objApiRequest->setUri(ApiRequestService::URI_API.'users/'.$id);
        $this->objApiRequest->setMethod(ApiRequestService::METHOD_GET);
        $this->objApiRequest->setParameters(array('id'=> $id));
        $arrResponse = array();
        try {
            $arrResponse['data'] = $this->objApiRequest->request();
        } catch (Exception $e) {
            $arrResponse['error'] = $e->getMessage();
        }
        echo json_encode($arrResponse);exit;
    }

    public function addAction()
    {
        $arrParams = $this->getArrayParams();
        unset($arrParams['id']);
        $this->objApiRequest->setUri(ApiRequestService::URI_API.'users');
        $this->objApiRequest->setParameters($arrParams);
        $this->objApiRequest->setMethod(ApiRequestService::METHOD_POST);
        $arrResponse = array();
        try {
            $arrResponse = $this->objApiRequest->requestFromCurl();
        } catch (Exception $e) {
            $arrResponse['message'] = $e->getMessage();
        }
        echo json_encode($arrResponse);exit;
    }

    public function deleteAction()
    {
        $this->objApiRequest->setUri(ApiRequestService::URI_API.'users/'.$this->params()->fromPost('id'));
        $this->objApiRequest->setMethod(ApiRequestService::METHOD_DELETE);
        $arrResponse = array();
        try {
            $arrResponse = $this->objApiRequest->requestFromCurl();
        } catch (Exception $e) {
            $arrResponse['message'] = $e->getMessage();
        }
        echo json_encode($arrResponse);exit;
    }

    public function updateAction()
    {
        $arrParams = $this->getArrayParams();
        $this->objApiRequest->setUri(ApiRequestService::URI_API.'users/'.$arrParams['id']);
        $this->objApiRequest->setParameters($arrParams);
        $this->objApiRequest->setMethod(ApiRequestService::METHOD_PUT);
        $arrResponse = array();
        try {
            $arrResponse = $this->objApiRequest->request();
        } catch (Exception $e) {
            $arrResponse['message'] = $e->getMessage();
        }
        echo json_encode($arrResponse);exit;
    }

    private function getArrayParams()
    {
        $arrParams['id'] = $this->params()->fromPost('id');
        $arrParams['name'] = $this->params()->fromPost('name');
        $arrParams['username'] = $this->params()->fromPost('username');
        $arrParams['email'] = $this->params()->fromPost('email');
        $arrParams['phone'] = $this->params()->fromPost('phone');
        $arrParams['website'] = $this->params()->fromPost('website');
        return $arrParams;
    }
}
