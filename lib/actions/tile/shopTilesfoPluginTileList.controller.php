<?php
class shopTilesfoPluginTileListController extends waJsonController
{

	public function execute()
	{
		$set_id = waRequest::get('id', '');

		$collection = new shopTilesfoPluginTileCollection();
		$collection->addWhere('T.set_id = '.$set_id);
		$collection->setOrderBy("T.sort ASC");
		$tiles = $collection->getItems('T.*');
		
		
		
		$items = array();
		foreach ($tiles as $tile) {
			$thumb_url = shopTilesfoPluginHelper::getThumbUrl($set_id, $tile['id'], '0x200', $tile['ext']);
			$tmp = array(
				'id' => intval($tile['id']),
				'name' => htmlspecialchars($tile['name'] . '.' . $tile['ext'], ENT_QUOTES),
				'text' => htmlspecialchars($tile['text'], ENT_QUOTES),
				'path' => $thumb_url,
			);
			array_push($items, $tmp);
		}
		$this->response = $items;

	}
}