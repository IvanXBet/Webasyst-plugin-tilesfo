<?php
class shopTilesfoPluginSetEditNameController extends waJsonController
{

	public function execute()
	{
		$set = waRequest::post('set', null);
		$tilesfo = waSystem::getInstance('shop')->getPlugin('tilesfo');
		$this->response = $tilesfo->updateSet($set);
	}
}