<?php
class shopTilesfoPluginTileDeleteController extends waJsonController
{
	public function execute()
	{
		$set = waRequest::post('set', null);

		if (!$set['id']) {
            $this->response = array('result' => 0, 'message' => 'ID файла не указан');
            return;
        }
		
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
        $this->response = $tilesfo->removeTile($set['id'], $set['tile_id']);
	}
}