<?php
class shopTilesfoPluginTileDeleteDialogAction extends waViewAction
{
    public function execute() 
    {
        $tile_id = waRequest::post('tile_id', 0, 'int');
        $set_id = waRequest::post('set_id', 0, 'int');
       
        $set_model = new shopTilesfoPluginSetModel();
		$set = $set_model->getById($set_id);

        $tile_model = new shopTilesfoPluginTileModel();
        $tile = $tile_model->getById($tile_id);

        $this->view->assign('set', $set);
        $this->view->assign('tile', $tile);
    }

}