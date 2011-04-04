<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_Template {

	public function action_index() {
		print_r(Model::factory('appointment')->delete_appointment(1));
	}

  public function action_test() {
    echo "HELLO WORLD!";
  }

} // End Welcome
