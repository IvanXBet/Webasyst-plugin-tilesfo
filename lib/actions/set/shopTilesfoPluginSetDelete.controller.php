<?php
class shopTilesfoPluginSetDeleteController extends waJsonController
{

	public function execute()
	{
		$set = waRequest::post('set', null);
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->removeSet($set);
	}
}