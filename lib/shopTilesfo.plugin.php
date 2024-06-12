<?php

class shopTilesfoPlugin  extends shopPlugin

{
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Хуки
	/////////////////////////////////////////////////////////////////////////////////////
	

	public function backendProduct($data)
	{
		$view = wa()->getView();

		$plugin = waRequest::get('plugin', '', 'string');
		$module = waRequest::get('module', '', 'string');
		$action = waRequest::get('action', '', 'string');
		
		$tilesfo_core_li_class = 'no-tab';
		if($plugin == 'tilesfo' && $module == 'backend' && $action == 'control') {$tilesfo_core_li_class = 'selected';}
		$view->assign('tilesfo_core_li_class', $tilesfo_core_li_class);

		$view->assign('id_poduct', $data['id']);
		return array('edit_section_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/tilesfo/templates/BackendMenuEditSectionLi.html'));
	}

	public function backendMenu()
	{
		$view = wa()->getView();
		return array(
			'aux_li' => $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/tilesfo/templates/BackendMenuAuxLi.html'),
		);
	}

	static public function frontendProductTiles($product_id, $width, $height) 
	{
		$setsCollection = new shopTilesfoPluginSetCollection();
		$setsCollection->addWhere("T.product_id = ".$product_id);
		$setsCollection->setOrderBy("T.sort ASC");
		$sets = $setsCollection->getItems('T.id, name');

		$items = array();

		foreach ($sets as $set_id => $set) {
			$tilesCollection = new shopTilesfoPluginTileCollection();
			$tilesCollection->addWhere("T.set_id = ".$set_id);
			$tilesCollection->setOrderBy("T.sort ASC");
			$tiles = $tilesCollection->getItems('T.id, set_id, name, ext, text, sort');
			$public_path = wa()->getDataUrl('plugins/tilesfo/' . $set_id, true, 'shop', true);
			foreach ($tiles as &$tile) {
				$thumb_url = shopTilesfoPluginHelper::getThumbUrl($tile['set_id'], $tile['id'], $width.'x'.$height, $tile['ext']);
				$tile['path'] = $thumb_url;
			}
			$set['tiles'] = $tiles;
			$items[$set_id] = $set;
		}
		
		$view = wa()->getView();
		$view->assign('tfo_sets', $items);
		return $view->fetch(wa()->getAppPath(null, 'shop').'/plugins/tilesfo/templates/FrontendTilesBlock.html');
	}
	
	/////////////////////////////////////////////////////////////////////////////////////
	// Работа со списками
	/////////////////////////////////////////////////////////////////////////////////////


	public function addSet($data) 
	{
		$set_model = new shopTilesfoPluginSetModel();

		if (!isset($data['product_id']) || empty($data['product_id'])) {
			return array('result' => 0, 'message' => 'Продукт не найден');
		}

		$name = trim(ifempty($data['name'], ''));
		if (mb_strlen($name) == 0) {
			return array('result' => 0, 'message' => 'Укажите имя');
		}
		$data['name'] = $set_model->escape($name);
		$data['product_id'] = $set_model->escape($data['product_id']);

		try {
			$id = $set_model->add($data);
			return array('result' => 1, 'message' => 'Готово', 'set' => $set_model->getById($id));
		} catch (Exception $e) {
			return array('result' => 0, 'message' => 'Ошибка при сохранении сета: ' . $e->getMessage());
		}
	}

	public function updateSet($data) 
	{
		$id = ifempty($data['id'], 0);
		if(array_key_exists('id', $data)) {unset($data['id']);}
		if(array_key_exists('sort', $data)) {unset($data['sort']);}
		
		$set_model = new shopTilesfoPluginSetModel();
		$ex = $set_model->getById($id);
		if(!$ex) {return array('result' => 0, 'message' => 'Список не найден');}
		$set_model->updateById($id, $data);
		return array('result' => 1, 'message' => 'Готово', 'set' => $set_model->getById($id));
	}

	public function removeSet($data) 
	{
		$set_model = new shopTilesfoPluginSetModel();
		$id = ifempty($data['id'], 0);
		$id = $set_model->escape($id);
		if(!is_numeric($id))
		{
			return array('result' => 0, 'message' => 'Системная ошибка #IDNONUM');
		}
		$set_model->remove($id);
		$path_public = wa()->getDataPath("plugins/tilesfo/$id/", true);
		$path_protected = wa()->getDataPath("plugins/tilesfo/$id/", false);
		waFiles::delete($path_public);
		waFiles::delete($path_protected);

		return array('result' => 1,'message' => 'Успешное удаление', 'id'=>$id);
	}

	public function sortSets($sets) 
	{
		if(!count($sets)) {return array('result' => 0, 'message' => 'Не заданн список для сротировки');}
		$set_model = new shopTilesfoPluginSetModel();
		return $set_model->sortSets($sets);
	}

	/////////////////////////////////////////////////////////////////////////////////////
	// Работа с плитками
	/////////////////////////////////////////////////////////////////////////////////////

	public function uploadTileFromPost($files, $set_id = '')
	{
		
		$tile_model = new shopTilesfoPluginTileModel();
		$result = array();

		$tmp_path = wa()->getDataPath('plugins/tilesfo/tmp/', true, 'shop');
		

		$valid_extensions = ['jpg', 'jpeg', 'png'];

		if(!is_numeric($set_id)) 
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка записи файла. Не известный набор');
			return $result;
		}
		
		foreach($files as $file) 
		{
			if(!$file->uploaded()) {$result[] = array('result' => 0, 'message' => 'Не удалось загрузить файл. Проверьте лимиты на размер файла (MAX_UPLOAD_FILESIZE)'); continue;}
			try 
			{
				$uuid = $tile_model->getUUID();
				try
				{
					$file->copyTo($tmp_path.$uuid.'.'.$file->extension);
					$hash = md5(file_get_contents($tmp_path.$uuid.'.'.$file->extension));
					waFiles::delete($tmp_path.$uuid.'.'.$file->extension);

					if($tile_model->getByField(array('hash' => $hash, 'set_id' => $set_id))) 
					{
						$file = $tile_model->getByField(array('hash' => $hash, 'set_id' => $set_id));

						$result[] = array('result' => 0, 'message' => 'Плитка с такой фотографией уже есть', 'file' => $file); 
						continue;
					}
					
					$data = array(
						'set_id' => $tile_model->escape($set_id),
						'name' => pathinfo(basename($file->name), PATHINFO_FILENAME),
						'size' => $file->size,
						'original_filename' => basename($file->name),
						'ext' => $file->extension,
						'hash' => $hash,
						'sort' => $tile_model->getMaxSort($set_id)+1,
					);
					if (!in_array($file->extension, $valid_extensions)) {
						$result[] = array('result' => 0, 'massage' => 'Расширение файла не подходит для сертификата');
						continue;
					}
					

					$id = $tile_model->insert($data);
					$thumb_path =  shopTilesfoPluginHelper::getOriginalPath($set_id);
					waFiles::create($thumb_path);
					$file->moveTo($thumb_path, $id.'.'.$file->extension);
					
					$size = '0x400'; 
					$max_size = '2000';
					$thumb_url = shopTilesfoPluginHelper::getThumbUrl($set_id, $id, $size, $file->extension);

					$result[] = array(
						'result' => 1,
						'message' => 'Файл загружен',
						'file' => $tile_model->getById($id),
						'thumb_url' => $thumb_url,
						'thumb_path' => $thumb_path,
					);
				}
				catch (Exception $e)
				{
					$result[] = array('result' => 0, 'message' => $e->getMessage());
				}
			}
			catch (Exception $e)
			{
				$result[] = array('result' => 0, 'message' => $e->getMessage());
			}
		}
		return $result;
	}

	public function removeTile($set_id, $tile_id) 
	{
		$tile_model = new shopTilesfoPluginTileModel();
		
		$path = wa()->getDataPath('plugins/tilesfo/'.$set_id, true, 'shop');
		$tile_id = $tile_model->escape($tile_id);

		if(is_numeric($tile_id)) 
		{
			if($tile_model->getById($tile_id)) 
			{
				$tile = $tile_model->getById($tile_id);
				$tile_model->deleteById($tile_id);

				$path = shopTilesfoPluginHelper::getOriginalPath($tile['set_id'], $tile['id'], $tile['ext']);
				$thumb_path =  shopTilesfoPluginHelper::getThumbsPath($tile['set_id'], $tile['id']);
				waFiles::delete($thumb_path);
				waFiles::delete($path);
				$result[] = array('result' => 1, 'message' => 'Плитка удалена');
			}
			else
			{
				$result[] = array('result' => 0, 'message' => 'Такой плитки не существует');
			} 
			
		}
		else
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка удаления');
		}
		
		return $result;
	}

	public function saveText($tile_id, $text) 
	{
		$tile_model = new shopTilesfoPluginTileModel();
		
		$tile_id = $tile_model->escape($tile_id);
		$text = $tile_model->escape($text);

		if(is_numeric($tile_id) && $tile_id != '0') 
		{
			if($tile_model->getById($tile_id))
			{
				$res = $tile_model->updateById($tile_id, array('text' => $text));
				$result[] = array('result' => 1, 'message' => $text);
			}
			else
			{
				$result[] = array('result' => 0, 'message' => 'Такой плитки не существует');
			} 
			
		}
		else
		{
			$result[] = array('result' => 0, 'message' => 'Ошибка сохранения');
		}
		
		return $result;

	}
	public function sortTiles($arrTiles, $set_id)
	{
		if(!count($arrTiles)) {return array('result' => 0, 'message' => 'Не заданн список для сротировки');}
		$tile_model = new shopTilesfoPluginTileModel();
		return $tile_model->sortTiles($arrTiles);
	}
	
}
