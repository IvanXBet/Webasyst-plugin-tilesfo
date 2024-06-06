<?php


class shopTilesfoPluginTileTileSortController extends waJsonController
{
	public function execute()
	{
		$tiles = waRequest::post('tiles', '', 'string');
		$set_id = waRequest::post('set_id', null);
		if(!mb_strlen($tiles)) {$this->response = array('result' => 0, 'message' => 'Системная ошибка #NOARR'); return;}
        $tiles = str_replace("tiles= ", "", $tiles);
        $arrTiles = explode(',', $tiles);
            
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->sortTiles($arrTiles, $set_id);
	}
}