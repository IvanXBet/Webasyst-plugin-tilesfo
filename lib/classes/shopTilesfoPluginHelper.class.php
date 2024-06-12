<?php
class shopTilesfoPluginHelper
{

	static public function getOriginalPath($product_id, $image_id = null, $ext = null)
	{
		$str = str_pad($product_id, 6, '0', STR_PAD_LEFT);
        $path = wa()->getDataPath('plugins/tilesfo/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), false, 'shop', false);
		if(!$image_id) {return $path;}
		return $path.'/'.$image_id.'.'.$ext;
	}
	
	static public function getThumbsPath($product_id, $image_id = null, $size = null, $ext = null)
	{
		$str = str_pad($product_id, 6, '0', STR_PAD_LEFT);
        $path = wa()->getDataPath('plugins/tilesfo/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), true, 'shop', false);
		if(!$image_id) {return $path;}
		if(!$size || !$ext) {return $path.'/'.$image_id;}
		return $path.'/'.$image_id.'.'.$size.'.'.$ext;
	}
	
	static public function getThumbUrl($product_id, $image_id = null, $size = null, $ext = null, $absolute = false)
	{
		$str = str_pad($product_id, 6, '0', STR_PAD_LEFT);
        
		$url = wa()->getDataUrl('plugins/tilesfo/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), true, 'shop', $absolute);
        
		if(!$image_id || !$size) {return $url;}
        
		return $url.'/'.$image_id.'/'.$image_id.'.'.$size.'.'.$ext;
	}

	static public function generateThumb($src_image_path, $size, $max_size)
	{
        $image = waImage::factory($src_image_path);
        $width = $height = null;
        $size_info = self::parseSize($size);
        $type = $size_info['type'];
        $width = $size_info['width'];
        $height = $size_info['height'];
        
        switch($type)
		{
            case 'max':
                if (is_numeric($max_size) && $width > $max_size) {
                    
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'width':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'height':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'crop':
            case 'rectangle':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height, waImage::INVERSE)->crop($width, $height);
                break;
            default:
                throw new waException('Ошибка определения размеров изображения');
                break;
        }

        return $image;
	}
	
	static public function parseSize($size)
    {
        $type = 'unknown';
        $ar_size = explode('x', $size);
        $width = !empty($ar_size[0]) ? $ar_size[0] : null;
        $height = !empty($ar_size[1]) ? $ar_size[1] : null;

        if (count($ar_size) == 1)
		{
            $type = 'max';
            $height = $width;
        } 
		else 
		{
            if($width == $height)
			{
                $type = 'crop';
            }
			else 
			{
                if ($width && $height)
				{
                    $type = 'rectangle';
                }
				else
				{
                    if (is_null($width)) 
					{
                        $type = 'height';
                    }
					else
					{
						if (is_null($height))
						{
                           $type = 'width';
                        }
					}
				}
            }
        }
        return array(
            'type'   => $type,
            'width'  => $width,
            'height' => $height
        );
    }
}