<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {
	public function action_index() {
		$data['foo'] = 'bar';
		echo json_encode($data);
	}

} // End Welcome