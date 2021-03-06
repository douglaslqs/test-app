<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\AbstractTable;
use Application\Service\ResponseService;

class ImageProductTable extends AbstractTable
{
	public function __construct(TableGateway $tableGateway, ResponseService $responseService)
	{
		parent::__construct($tableGateway, $responseService);
	}

	public function filterArrayWhere($arrParams = array())
	{
		return array(
                'name' => isset($arrParams['name']) ? $arrParams['name'] : null,
                'product' => isset($arrParams['product']) ? $arrParams['product'] : null,
                'mark' => isset($arrParams['mark']) ? $arrParams['mark'] : null,
            );
	}
}