<?php
class shopTilesfoPluginSetCreateConfirmController extends waJsonController
{
	public function execute()
	{
		$set = waRequest::post("set", null);
		if(!is_array($set)) {$this->response = array("result"=> 0,"message"=> "Системная ошибка"); return;}
		
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->addSet($set);
		
	}
}


