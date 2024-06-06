<?php
class shopTilesfoPluginTileSaveTextController extends waJsonController
{

	public function execute()
	{
		$tile_id = waRequest::post('id', 0, 'int');
		$text = waRequest::post('text', '', 'string');
		
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->saveText($tile_id, $text);
	}
}