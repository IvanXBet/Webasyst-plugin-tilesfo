<?php


class shopTilesfoPluginProductControltilesAction extends waViewAction
{

	public function execute()
	{
		$product_id = waRequest::get('id', 0, 'int');

		$collection = new shopTilesfoPluginSetCollection();
		$collection->addWhere('T.product_id = '.$product_id);
		$collection->setOrderBy("T.sort ASC");
		$sets = $collection->getItems('T.*');
		
		$this->view->assign('product_id', $product_id); 
		$this->view->assign('sets', $sets);
		
	}
}
