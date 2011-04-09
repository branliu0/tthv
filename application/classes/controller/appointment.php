<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Appointment extends Controller_Template {
	public function action_delete($case_id, $id) {
		Model::factory('appointment')->delete_appointment($id);
		$this->request->redirect("case/view/{$case_id}");
	}

  public function action_add($case_id) {
    $post = Validate::factory($_POST);
    $post->filter(TRUE, 'trim');
    $post->rule('child_name', 'not_empty');
    $post->rule('date', 'not_empty');
    $post->rule('date', 'date');
    $post->rule('treatment', 'not_empty');
    $post->rule('treatment', 'alpha_dash');
    $post->rule('treatment', 'max_length', array(150));

    if ($post->check()) {
      $appts = Model::factory('appointment');
      $post['case_id'] = $case_id;
      $post['date'] = strtotime($post['date']);
      $post['message'] = $appts->generateMessage($post['child_name'], $post['treatment'], $post['date']);
      print_r($post->as_array());
      $appts->add_appointment($post->as_array());

      $this->request->redirect("case/view/{$case_id}");
      return;
    }

    $errors = $post->errors('validate');

    $case = Model::factory('case')->select_by_id($case_id);

    $this->template->content = View::factory('appointment/add')
      ->bind('post', $post)
      ->bind('errors', $errors)
      ->bind('case', $case);
    
  }

  public function action_all($case_id) {
    $case = Model::factory('case')->select_by_id($case_id);
    $appts = Model::factory('appointment')->select_by_case_id($case_id);
    $apptArr = $appts->as_array();
    $now = time();
    foreach ($apptArr as &$appt) {
      if ($appt['date'] > $now) {
        $appt['status'] = "Upcoming";
      }
      else {
        if ($appt['checked_in'] == 0) {
          $appt['status'] = "Overdue";
        }
        else {
          $appt['status'] = "Treated on " . strftime("%Y-%m-%d %H:%M:%S", $appt['date']);
        }
      }
    }
    $this->template->content = View::factory('appointment/all')
      ->bind('case', $case)
      ->bind('appts', $apptArr);
  }
}
