<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {
  public function action_index() {
    if (!isset($_POST['action'])) {
      echo json_encode(array("success" => false, "errors" => array("Please provide an API action")));
    }
    switch ($_POST['action']) {
      // Adds a new patient
      // Required fields:
      // Description | field_name | required_attributes
      // Patient Name | patient_name | not_empty
      // Village Name | village_name | not_empty
      // Primary Health Center Name | phc_name | not_empty, only alphanum, _-
      // Mobile number | mobile | 10 digits
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
      // Adds a child for a case
      // Required fields:
      // Description | field_name | required_attributes
      // Child Name | child_name | not_empty
      // Birth Date | birth_date | date (can be accepted by date())
      // Patient Case Id | case_id | not_empty
    case "addChild":
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
      // Adds one appointment for a patient
      // Required fields:
      // Description | field_name | required_attributes
      // Child Name | child_name | not_empty
      // Appointment Date | date | date (must be accepted by date())
      // Reminder Message | message | not_empty, less than 150 chars
      // Patient Case Id | case_id | not_empty
    case "addAppointment":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('child_name', 'not_empty');
      $post->rule('date', 'not_empty');
      $post->rule('date', 'date');
      $post->rule('message', 'not_empty');
      $post->rule('message', 'max_length', array(150));

      if (!isset($_POST['case_id'])) {
        echo json_encode(array("success" => false, "errors" => array("Please include the case id of the patient")));
        break;
      }

      if ($post->check()) {
        $appts = Model::factory('appointment');
        $data['child_name'] = $post['child_name'];
        $data['message'] = $post['message'];
        $data['date'] = new DateTime($post['date']);
        $data['date'] = $data['date']->getTimestamp();
        $data['case_id'] = $post['case_id'];
        $appts->add_appointment($data);
        echo json_encode(array("success" => true));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
      // Gets all the appointments for a patient
      // Required fields:
      // Description | field_name | required_attributes
      // Patient Case Id | case_id | not_empty
    case "getAppointments":
      if (!isset($_POST['case_id'])) {
        echo json_encode(array("success" => false, "errors" => array("Please include the case id of the patient")));
        break;
      }
      $appointments = Model::factory('appointment')->select_by_case_id($_POST['case_id']);
      echo json_encode(array("success" => true, "case_id" => $_POST['case_id'], "appointments" => $appointments));
      break;
    default:
      echo json_encode(array("success" => false, "errors" => array("Please use a valid API action")));
    }
  }

}
