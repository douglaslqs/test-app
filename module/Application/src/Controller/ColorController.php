<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Application\Model\ColorTable;
use Application\Service\ResponseService;
use Application\Service\LoggerService as Logger;

class ColorController extends AbstractRestfulController
{
    private $colorTable;
    private $form;
    private $responseService;
    private $filterService;
    private $logger;

    public function __construct(ResponseService $responseService, ColorTable $colorTable)
    {
        $this->responseService = $responseService;
        $this->colorTable = $colorTable;
    }

    public function getAction()
    {
        $request = $this->getRequest();
        if($request->isGet()){
            $arrParams = $request->getQuery()->toArray();
            try {
                if(!empty($arrParams)){
                    $arrParams = array_change_key_case($arrParams, CASE_LOWER);
                }
                $color = $this->colorTable->fetch($arrParams);
                if(!empty($color)){
                    $this->responseService->setData($color);
                    $this->responseService->setCode(ResponseService::CODE_SUCCESS);
                } else {
                    $this->responseService->setCode(ResponseService::CODE_QUERY_EMPTY);
                }
            } catch (Exception $e) {
                $this->responseService->setCode(ResponseService::CODE_ERROR);
                $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                $this->logger->save(Logger::LOG_APPLICATION, Logger::CRITICAL ,$e->getMessage());
            }
        } else {
            $this->responseService->setCode(ResponseService::CODE_METHOD_INCORRECT);
        }
        return new JsonModel($this->responseService->getArrayCopy());
    }

    public function addAction()
    {
    	$request = $this->getRequest();
        if ($request->isPost()) {
            $arrParams = $request->getPost()->toArray();
            $arrParams = array_change_key_case($arrParams, CASE_LOWER);
            try {
                $boolUpdate = false;
                $this->form->addInputFilter($boolUpdate);
                $this->form->setData($arrParams);
                if ($this->form->isValid()) {
                    $arrParams = $this->filterService->setData($arrParams)->getData();
                    $color = $this->colorTable->fetchRow($arrParams);
                    if (empty($color)) {
                        $returnInsert = $this->colorTable->insert($arrParams);
                        if ($returnInsert !== 1) {
                            $this->responseService->setCode(ResponseService::CODE_ERROR);
                            $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                            $this->logger->save(Logger::LOG_APPLICATION,Logger::ALERT,$returnInsert);
                        } else {
                            $this->responseService->setCode(ResponseService::CODE_SUCCESS);
                        }
                    } else {
                        if (is_array($color)) {
                            $this->responseService->setCode(ResponseService::CODE_ALREADY_EXISTS);
                        } else {
                            $this->responseService->setCode(ResponseService::CODE_ERROR);
                            $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                            $this->logger->save(Logger::LOG_APPLICATION,Logger::WARNING,$color);
                        }
                    }
                } else {
                    $this->responseService->setData($this->form->getInputFilter()->getMessages());
                    $this->responseService->setCode(ResponseService::CODE_NOT_PARAMS_VALIDATED);
                }
            } catch (Exception $e) {
                $this->responseService->setCode(ResponseService::CODE_ERROR);
                $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                $this->logger->save(Logger::LOG_APPLICATION, Logger::CRITICAL ,$e->getMessage());
            }
        } else {
            $this->responseService->setCode(ResponseService::CODE_METHOD_INCORRECT);
        }
        return new JsonModel($this->responseService->getArrayCopy());
    }

    public function updateAction()
    {
    	$request = $this->getRequest();
        if ($request->isPost()) {
            $arrParams = $request->getPost()->toArray();
            $arrParams = array_change_key_case($arrParams, CASE_LOWER);
            try {
                $boolUpdate = true;
                $this->form->addInputFilter($boolUpdate);
                $this->form->setData($arrParams);
                if ($this->form->isValid()) {
                    $arrParams = $this->filterService->setData($arrParams)->getData();
                    $color = $this->colorTable->fetchRow($arrParams);
                    if (is_array($color) && !empty($color)) {
                        $arrSet = $this->filterService->getArraySet();
                        $arrWhere = $this->filterService->getArrayWhere();
                        $returnUpdate = $this->colorTable->update($arrSet,$arrWhere);
                        if (is_numeric($returnUpdate)) {
                            $this->responseService->setCode(ResponseService::CODE_SUCCESS);
                        } else {
                            $this->responseService->setCode(ResponseService::CODE_ERROR);
                            $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                            $this->logger->save(Logger::LOG_APPLICATION,Logger::ALERT,$returnUpdate);
                        }
                    } else {
                        if (is_array($color)) {
                            $this->responseService->setCode(ResponseService::CODE_ALREADY_EXISTS);
                        } else {
                            $this->responseService->setCode(ResponseService::CODE_ERROR);
                            $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                            $this->logger->save(Logger::LOG_APPLICATION,Logger::WARNING,$color);
                        }
                    }
                } else {
                    $this->responseService->setData($this->form->getInputFilter()->getMessages());
                    $this->responseService->setCode(ResponseService::CODE_NOT_PARAMS_VALIDATED);
                }
            } catch (Exception $e) {
                $this->responseService->setCode(ResponseService::CODE_ERROR);
                $this->logger->setMethodAndLine(__METHOD__, __LINE__);
                $this->logger->save(Logger::LOG_APPLICATION, Logger::CRITICAL ,$e->getMessage());
            }
        }
        return new JsonModel($this->responseService->getArrayCopy());
    }

    public function setForm($form)
    {
        $this->form = $form;
    }

    public function setFilterService($filterService)
    {
        $this->filterService = $filterService;
    }

    public function setLogger($objLogger)
    {
        $this->logger = $objLogger;
    }
}
