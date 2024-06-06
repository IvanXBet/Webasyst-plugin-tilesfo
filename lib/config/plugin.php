<?php
return array
	(
		'name' => 'tilesfo',
		'version' => '1.0.0',
		'vendor' => 995002,
		'description' => 'Позволяет добавлять плитки к товарам',
		'img' => 'img/tiles.svg',
		'handlers' => array
						(
							'backend_product' => 'backendProduct',
							'backend_menu' => 'backendMenu',
							'frontend_product' => 'frontendProduct',
						),
	);