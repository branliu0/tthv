<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Case extends Controller_Template {

	public function action_index() {
		$cases = Model::factory('case');
		$phcs = $cases->select_phcs();
		$this->template->content = View::factory('case/view_phcs')
			->bind('phcs', $phcs);
	}

	public function action_phc($phc_name) {
		$model = Model::factory('case');
		$cases = $model->select_by_phc_name($phc_name);
		$this->template->content = View::factory('case/phc')
			->bind('cases', $cases)
			->bind('phc_name', $phc_name);
	}

	public function action_view($id) {
		$cases = Model::factory('case');
		$case = $cases->select_by_id($id);
		$this->template->content = View::factory('case/view')
			->bind('case', $case);
	}

	public function action_add() {

		if( ! isset($_POST)) {
			$this->template->content = View::factor('case/add');
			return;
		}

		$post = Validate::factory($_POST);
		$post->filter(TRUE, 'trim');
		$post->rule('patient_name', 'not_empty');
		$post->rule('village_name', 'not_empty');
		$post->rule('phc_name', 'not_empty');
		$post->rule('phc_name', 'regex', array('/[a-zA-Z0-9_\- ]+/'));
		$post->rule('mobile', 'not_empty');
		$post->rule('mobile', 'numeric');
		$post->rule('mobile', 'exact_length', array(10));

		if ($post->check()) {
			$case = Model::factory('case');
			list($case_id, $affected_rows) = $case->add($post->as_array());
			$this->request->redirect("case/view/{$case_id}");
		}

		$errors = $post->errors('validate');

		$this->template->content = View::factory('case/add')
			->bind('post', $post)
			->bind('errors', $errors);
	}

}
