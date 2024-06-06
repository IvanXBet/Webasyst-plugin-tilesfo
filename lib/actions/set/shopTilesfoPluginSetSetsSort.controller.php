<?php


class shopTilesfoPluginSetSetsSortController extends waJsonController
{
	public function execute()
	{
		$sets = waRequest::post('sets', '', 'string');
		if(!mb_strlen($sets)) {$this->response = array('result' => 0, 'message' => 'Системная ошибка #NOARR'); return;}
        $sets = str_replace("sets= ", "", $sets);
        $arrSets = explode(',', $sets);
            
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->sortSets($arrSets);
	}
}