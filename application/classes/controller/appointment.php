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
    $post->rule('message', 'not_empty');
    $post->rule('message', 'max_length', array(150));

    if ($post->check()) {
      $appts = Model::factory('appointment');
      $data['child_name'] = $post['child_name'];
      $data['message'] = $post['message'];
      $data['date'] = new DateTime($post['date']);
      $data['date'] = $data['date']->getTimestamp();
      $data['case_id'] = $case_id;
      $appts->add_appointment($data);

      $this->request->redirect("case/view/{$case_id}");
      return;
    }

    $errors = $post->errors('validate');

    $case = Model::factory('case')->select_by_id($case_id)->current();

    $this->template->content = View::factory('appointment/add')
      ->bind('post', $post)
      ->bind('errors', $errors)
      ->bind('case', $case);
    
  }
}
