<?php
class shopTilesfoPluginTileUploadController extends waJsonController
{
	public function execute()
	{
		$files = waRequest::file('files');
		$product_id = waRequest::post("product_id", null, 'int');
		$set_id = waRequest::post("set_id", null, 'int');
		

		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->uploadTileFromPost($files, $set_id);
		
	}
}


