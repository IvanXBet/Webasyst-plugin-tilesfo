<?php 
    class shopTilesfoPluginSetModel extends waModel
    {
        protected $table = "shop_tilesfo_set";

        public function getAllSorted($fetch_by = 'id') 
        {
            return $this->query("SELECT * FROM " .$this->table. " ORDER BY sort ASC")->fetchAll($fetch_by);
        }
        public function getMaxSort($product_id) 
        {
            $data = $this->query("SELECT  MAX(sort) AS mx FROM ".$this->table." WHERE product_id = ".$product_id)->fetchAll();
           
            if(!count($data)) {return 0;}
            return $data[0]["mx"];
        }

        public function add($data)
        {
            if(array_key_exists("id", $data)) {unset($data['id']);}
            $data['sort'] = $this->getMaxSort($data['product_id']) + 1;
            return $this->insert($data);
        }

        public function remove($id)
        {
            return $this -> deleteById($id);
        }

        public function sortSets($arrSest)
        {

            if(!count($arrSest)) {return;}
            $sort = 1;
            foreach($arrSest as $key => $id) {
                $this->updateById($id, array('sort' => $sort));
                $sort++;
            }
            return array(
                'data' => $arrSest,
                'mas' => 'Готово',
            );
        }
    }