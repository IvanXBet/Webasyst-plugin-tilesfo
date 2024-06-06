<?php
class shopTilesfoPluginSetDeleteDialogAction extends waViewAction
{
    public function execute() 
    {
        $set_id = waRequest::post('set_id', 0, 'int');
       
        $set_model = new shopTilesfoPluginSetModel();
		$set = $set_model->getById($set_id);

        $this->view->assign('set', $set);
    }

}