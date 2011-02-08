<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Appointment extends Controller_Template {
	public function action_delete($case_id, $id) {
		Model::factory('appointment')->delete_appointment($id);
		$this->request->redirect("case/view/{$case_id}");
	}
}
