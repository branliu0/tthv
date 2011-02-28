<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {
	public function action_index() {
    if (!isset($_POST['action'])) {
      echo json_encode(array("success" => false, "errors" => array("Please provide an API action")));
    }
    switch ($_POST['action']) {
    case "addPatient":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('patient_name', 'not_empty');
      $post->rule('village_name', 'not_empty');
      $post->rule('phc_name', 'not_empty');
      $post->rule('phc_name', 'regex', array('/^[a-zA-Z0-9_\- ]+$/'));
      $post->rule('mobile', 'not_empty');
      $post->rule('mobile', 'numeric');
      $post->rule('mobile', 'exact_length', array(10));

      if ($post->check()) {
        Model::factory('case')->add($post->as_array());
        echo json_encode(array("success" => true));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
    case "addAppointment":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('child_name', 'not_empty');
      $post->rule('birth_date', 'not_empty');
      $post->rule('birth_date', 'date');

      if (!isset($_POST['case_id'])) {
        echo json_encode(array("success" => false, "errors" => array("Please include the case id of the patient")));
        break;
      }
      if ($post->check()) {
        $appts->add_child($post);
        echo json_encode(array("success" => true));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
    case "getAppointments":
      if (!isset($_POST['id'])) {
        echo json_encode(array("success" => false, "errors" => array("Please include the case id of the patient")));
        break;
      }
      $appointments = Model::factory('appointment')->select_by_case_id($_POST['id']);
      echo json_encode(array("success" => true, "id" => $_POST['id'], "appointments" => $appointments));
      break;
    default:
      echo json_encode(array("success" => false, "errors" => array("Please use a valid API action")));
    }
	}

}
