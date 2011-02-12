<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Controller {
  public function action_index() {
    $model = Model::factory('appointment');
    $appts = $model->select_all();
    $now = time();
    for($appts as $appt) {
      sdf
    }
	}

} // End Welcome
