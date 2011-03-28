<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_Template {

	public function action_index() {
		print_r(Model::factory('case')->get_villages());
		
	}

} // End Welcome
