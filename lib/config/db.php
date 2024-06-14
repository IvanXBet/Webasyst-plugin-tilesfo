<?php
return array(
    'shop_tilesfo_set' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'product_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'shop_tilesfo_tile' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'set_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'size' => array('int', 11, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 32, 'null' => 0),
        'hash' => array('varchar', 255, 'null' => 0),
        'text' => array('varchar', 255),
        'sort' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
