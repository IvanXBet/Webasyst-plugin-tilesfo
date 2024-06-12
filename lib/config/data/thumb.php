<?php
	$path = realpath(dirname(__FILE__)."/../../../../../../../");
	$config_path = $path."/wa-config/SystemConfig.class.php";
	if (!file_exists($config_path))
	{
		header("Location: ../../../../../../../../../../wa-apps/shop/plugins/tilesfo/img/image-not-found.png");
		exit;
	}
	
	require_once($config_path);
	$config = new SystemConfig();
	waSystem::getInstance(null, $config);
	$app_config = wa('shop')->getConfig();
	$request_file = $app_config->getRequestUrl(true, true);
	$request_file = preg_replace("@^thumb.php(plugins/tilesfo/)?/?@", '', $request_file);
	$protected_path = wa()->getDataPath('plugins/tilesfo/', false, 'shop');
	$public_path = wa()->getDataPath('plugins/tilesfo/', true, 'shop');

	$file = false;
	$size = false;
	$enable_2x = false;
	if(preg_match('#([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)/([a-zA-Z0-9_\.-]+)\.(\d+(?:x\d+)?)(@2x)?\.([a-z]{3,4})#i', $request_file, $matches))
	{
		$file = $matches[1].'/'.$matches[2].'/'.$matches[3].'/'.$matches[5].'.'.$matches[8];
		$size = $matches[6];
	}
	wa()->getStorage()->close();
	
	$original_path = $protected_path.$file;
	$thumb_path = $public_path.$request_file;
	if($file && file_exists($original_path) && !file_exists($thumb_path))
	{
		$thumbs_dir = dirname($thumb_path);
		if(!file_exists($thumbs_dir))
		{
			waFiles::create($thumbs_dir);
		}
		
		$max_size = '2000';
		//ToDo
		$image = shopTilesfoPluginHelper::generateThumb($original_path, $size, $max_size);
		
		if ($image)
		{
			$image->save($thumb_path, 100);
			clearstatcache();
		}
	}
	
	if($file && file_exists($thumb_path))
	{
		waFiles::readFile($thumb_path);
	}
	else
	{
		header("HTTP/1.0 404 Not Found");
		exit;
	}
