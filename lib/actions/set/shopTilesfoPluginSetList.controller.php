<?php
class shopTilesfoPluginSetListController extends shopTilesfoPluginBackendDatatableController
{

	public function execute()
	{
		$product_id = waRequest::get('product_id', null, 'int');

		$collection = new shopTilesfoPluginSetCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$collection->setOrderBy("T.sort ASC");
		$sets = $collection->getItems('T.*');
		$items = array();
		
		foreach($sets as $set) 
		{
			$tmp = array(
				htmlspecialchars($set['name'], ENT_QUOTES),
				'<i class="icon16 edit tfo-set-edit" title="Редактировать" data-id="'.intval($set['id']).'"></i>',
				'<i class="icon16 delete tfo-set-delete" title="Удалить" data-id="'.intval($set['id']).'"></i>',
			);
			array_push($items,  $tmp);
		}
		
		$result['data'] = $items;
		$result['draw'] = $this->draw;
		$this->response = $result;
	}
}