<?php 
    class shopTilesfoPluginTileModel extends waModel
    {
        protected $table = "shop_tilesfo_tile";

        public function getUUID()
		{
			$data = $this->query('SELECT UUID() AS uuid')->fetchAll();
			return $data[0]['uuid'];
		}

        public function getMaxSort($set_id) 
        {
            $data = $this->query("SELECT  MAX(sort) AS mx FROM ".$this->table." WHERE set_id = ".$set_id)->fetchAll();
           
            if(!count($data)) {return 0;}
            return $data[0]["mx"];
        }

        public function sortTiles($arrTiles)
        {
            if(!count($arrTiles)) {return;}
            $sort = 1;

            // Отладка: печать входного массива
            echo "Input array:\n";
            print_r($arrTiles);

            foreach($arrTiles as $key => $id) {
                echo "ID: $id, Sort: $sort\n";
                $this->updateById($id, array('sort' => $sort));
                $sort++;
            }
            return array(
                'data' => $arrTiles,
                'mas' => 'Готово',
            );
        }
    }