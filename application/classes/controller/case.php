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
		$appts = Model::factory('appointment');
		$cases = Model::factory('case');

		$post = Validate::factory($_POST);
		$post->filter(TRUE, 'trim');
		$post->rule('child_name', 'not_empty');
		$post->rule('birth_date', 'not_empty');
		$post->rule('birth_date', 'date');

		if ($post->check()) {
			$post['case_id'] = $id;
			$appts->add_child($post);
		}

		$errors = $post->errors('validate');

		$case = $cases->select_by_id($id);
		$appointments = $appts->select_by_case_id($id);

    $checkedIn = -1;
    if ($appointments->count() != 0) {
      $nextAppt = $appointments->current();
      $nextApptTime = new DateTime("@" . $nextAppt['date']);
      $now = new DateTime("now");
      if ($nextApptTime->format("m d Y") == $now->format("m d Y")) {
        $checkedIn = $nextAppt['checked_in'];
      }
    }

		$this->template->content = View::factory('case/view')
			->set('case', $case->current())
      ->bind('checkedIn', $checkedIn)
			->bind('appts', $appointments)
			->bind('post', $post)
			->bind('errors', $errors);
    if ($checkedIn != -1) {
      $this->template->content->set('nextAppt', $appointments->current());
    }
	}

	public function action_add() {
		$post = Validate::factory($_POST);
		$post->filter(TRUE, 'trim');
		$post->rule('patient_name', 'not_empty');
		$post->rule('village_name', 'not_empty');
		$post->rule('phc_name', 'not_empty');
		$post->rule('phc_name', 'alpha_numeric');
		$post->rule('mobile', 'not_empty');
		$post->rule('mobile', 'numeric');
		$post->rule('mobile', 'exact_length', array(10));
    $post->rule('location', 'max_length', array(255));

    if (!isset($post['clinic_access'])) {
      $post['clinic_access'] = "no";
    }

		if ($post->check()) {
			$case = Model::factory('case');
			list($case_id, $affected_rows) = $case->add_case($post->as_array());
			$this->request->redirect("case/view/{$case_id}");
		}

		$errors = $post->errors('validate');

		$this->template->content = View::factory('case/add')
			->bind('post', $post)
			->bind('errors', $errors);
	}

  public function action_today() {
    $cases = Model::factory('case')->select_with_appts_today();
    $caseModel = Model::factory('case');
    $cases = $caseModel->select_with_appts_today();
    $overdue = $caseModel->select_overdue_last_week();
    $thisWeek = $caseModel->select_with_appts_this_week();
    $this->template->content = View::factory('case/today')
      ->bind('cases', $cases)
      ->bind('overdue', $overdue)
      ->bind('thisWeek', $thisWeek);
  }

}
