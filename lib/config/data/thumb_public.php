<?php
$file = realpath(dirname(__FILE__)."/../../../../")."/wa-apps/shop/plugins/tilesfo/lib/config/data/thumb.php";
if(file_exists($file)) {include($file);} else {header("HTTP/1.0 404 Not Found");}