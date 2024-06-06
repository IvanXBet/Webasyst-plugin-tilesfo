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
			$public_path = wa()->getDataUrl('plugins/tilesfo/' . $set_id, true, 'shop', true);
			$tmp = array(
				'id' => intval($tile['id']),
				'name' => htmlspecialchars($tile['name'] . '.' . $tile['ext'], ENT_QUOTES),
				'text' => htmlspecialchars($tile['text'], ENT_QUOTES),
				'path' => $public_path . '/' . $tile['id'] . '.' . $tile['ext'],
			);
			array_push($items, $tmp);
		}
		$this->response = $items;

	}
}