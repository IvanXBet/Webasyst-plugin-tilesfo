<?php
class shopTilesfoPluginSetEditAction extends waViewAction
{

	public function execute()
	{
		$set_id = waRequest::get('id', 0, 'int');
		$product_id = waRequest::post('product_id', 0, 'int');
		
		$set_model = new shopTilesfoPluginSetModel();
		$set = $set_model->getById($set_id);

		$this->view->assign('product_id', $product_id);
		$this->view->assign('set', $set);
	}
}