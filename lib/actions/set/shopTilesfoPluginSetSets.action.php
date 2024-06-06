<?php
class shopTilesfoPluginSetSetsAction extends waViewAction
{

	public function execute()
	{
		$product_id = waRequest::post('product_id', '', 'string');
		$this->view->assign('product_id', $product_id);
	}
}