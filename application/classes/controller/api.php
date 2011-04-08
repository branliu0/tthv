<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api extends Controller {
  public function action_index() {
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    if (!isset($_POST['action'])) {
      echo json_encode(array("success" => false, "errors" => array("Please provide an API action")));
      return;
    }
    switch ($_POST['action']) {
      // Gets all the patients in a village
      // Required fields:
      // Description | field_name | required_attributes
      // Village Name | village_name | not_empty
    case "getCasesByVillageName":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('village_name', 'not_empty');

      if ($post->check()) {
        $cases = Model::factory('case')->select_by_village_name($post['village_name']);
        echo json_encode(array("success" => true, "cases" => $cases->as_array()));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
      // Adds a new patient
      // Required fields:
      // Description | field_name | required_attributes
      // Patient Name | patient_name | not_empty
      // Village Name | village_name | not_empty
      // Primary Health Center Name | phc_name | not_empty, alphanum
      // Mobile number | mobile | not_empty, 10 digits
      // Clinic Access | clinic_access | not_empty,yes|no
    case "addCase":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('patient_name', 'not_empty');
      $post->rule('village_name', 'not_empty');
      $post->rule('phc_name', 'not_empty');
      $post->rule('phc_name', 'alpha_numeric');
      $post->rule('mobile', 'not_empty');
      $post->rule('mobile', 'digit');
      $post->rule('mobile', 'exact_length', array(10));
      $post->rule('clinic_access', 'not_empty');
      $post->rule('clinic_access', 'regex', array('/yes|no/'));
      $post->rule('location', 'max_length', array(255));

      if ($post->check()) {
        list($case_id, $num_rows) = Model::factory('case')->add_case($post->as_array());
        echo json_encode(array("success" => true, "case_id" => $case_id));
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
      $post->rule('case_id', 'not_empty');
      $post->rule('birth_date', 'date');

      if ($post->check()) {
        Model::factory('appointment')->add_child($post->as_array());
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
      $post->rule('case_id', 'not_empty');
      $post->rule('date', 'date');
      $post->rule('treatment', 'not_empty');
      $post->rule('treatment', 'alpha_dash');
      $post->rule('treatment', 'max_length', array(150));

      if ($post->check()) {
        $appts = Model::factory('appointment');
        $post['date'] = strtotime($post['date']);
        if (!isset($post['message'])) {
          $post['message'] = $appts->generateMessage($post['child_name'], $post['treatment'], $post['date']);
        }
        list($id, $num_rows) = $appts->add_appointment($post->as_array());
        echo json_encode(array("success" => true, "id" => $id));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
    case "addCaseWithChildrenAndAppointments":
      // Check case data
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('patient_name', 'not_empty');
      $post->rule('village_name', 'not_empty');
      $post->rule('phc_name', 'not_empty');
      $post->rule('phc_name', 'alpha_numeric');
      $post->rule('mobile', 'not_empty');
      $post->rule('mobile', 'digit');
      $post->rule('mobile', 'exact_length', array(10));
      $post->rule('clinic_access', 'not_empty');
      $post->rule('clinic_access', 'regex', array('/yes|no/'));
      $post->rule('location', 'max_length', array(255));

      if (!$post->check()) {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
        return;
      }

      // Check children data
      if (isset($_POST['children'])) {
        // echo $_POST['children'];
        // print_r(json_decode($_POST['children'], true));
        // break;
        foreach(json_decode($_POST['children'], true) as $child) {
          $childPost = Validate::factory($child);
          $childPost->filter(TRUE, 'trim');
          $childPost->rule('child_name', 'not_empty');
          $childPost->rule('birth_date', 'not_empty');
          $childPost->rule('birth_date', 'date');

          if (!$childPost->check()) {
            $errors = $childPost->errors('validate');
            echo json_encode(array("success" => false, "errors" => $errors));
            return;
          }
        }
      }
      
      // Check appointment data
      if (isset($_POST['appointments'])) {
        foreach(json_decode($_POST['appointments'], true) as $appt) {
          $apptPost = Validate::factory($appt);
          $apptPost->filter(TRUE, 'trim');
          $apptPost->rule('child_name', 'not_empty');
          $apptPost->rule('date', 'not_empty');
          $apptPost->rule('date', 'date');
          $apptPost->rule('message', 'not_empty');
          $apptPost->rule('message', 'max_length', array(150));

          if (!$apptPost->check()) {
            $errors = $apptPost->errors('validate');
            echo json_encode(array("success" => false, "errors" => $errors));
            return;
          }
        }
      }

      // All validation has passed. Let's insert the data!
      $caseModel = Model::factory('case');
      $apptModel = Model::factory('appointment');
      list($case_id, $num_rows) = $caseModel->add_case($post->as_array());

      if (isset($_POST['children'])) {
        foreach(json_decode($_POST['children'], true) as $child) {
          $child['case_id'] = $case_id;
          $apptModel->add_child($child);
        }
      }
      if (isset($_POST['appointments'])) {
        foreach(json_decode($_POST['appointments'], true) as $appt) {
          $appt['case_id'] = $case_id;
          $appt['date'] = strtotime($appt['date']);
          if (!isset($post['message'])) {
            $appt['message'] = $apptModel->generateMessage($appt['child_name'], $appt['treatment'], $appt['date']);
        }
          $apptModel->add_appointment($appt);
        }
      }
      echo json_encode(array("success" => true, "case_id" => $case_id));
      break;
      // Gets all the appointments for a patient
      // Required fields:
      // Description | field_name | required_attributes
      // Patient Case Id | case_id | not_empty, digit
    case "getAppointmentsByCaseId":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('case_id', 'not_empty');
      $post->rule('case_id', 'digit');

      if ($post->check()) {
        $appointments = Model::factory('appointment')->select_by_case_id($_POST['case_id']);
        echo json_encode(array("success" => true, "appointments" => $appointments->as_array()));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
      // Checks in for an appointment
      // Required fields:
      // Description | field_name | required_attributes
      // Appointment Id | id | not_empty, digit
    case "getAppointmentsByVillageName":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('village_name', 'not_empty');

      if ($post->check()) {
        $appts = Model::factory('appointment')->select_by_village_name($post['village_name']);
        echo json_encode(array("success" => true, "appointments" => $appts->as_array()));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
    case "checkInAppointment":
      $post = Validate::factory($_POST);
      $post->filter(TRUE, 'trim');
      $post->rule('id', 'not_empty');
      $post->rule('id', 'digit');

      if ($post->check()) {
        Model::factory('appointment')->check_in($_POST['id']);
        echo json_encode(array("success" => true));
      }
      else {
        $errors = $post->errors('validate');
        echo json_encode(array("success" => false, "errors" => $errors));
      }
      break;
    case "getCasesToday":
      $cases = Model::factory('case')->select_with_appts_today();
      echo json_encode(array("success" => true, "cases" => $cases->as_array()));
      break;
    case "getCasesOverdue":
      $cases = Model::factory('case')->select_overdue();
      echo json_encode(array("success" => true, "cases" => $cases->as_array()));
    case "getCasesOverdueByVillage":
      $villages = Model::factory('case')->select_overdue_by_village();
      echo json_encode(array("success" => true, "villages" => $villages->as_array()));
      break;
    case "getCasesThisWeek":
      $cases = Model::factory('case')->select_with_appts_this_week();
      echo json_encode(array("success" => true, "cases" => $cases->as_array()));
      break;
    case "getCasesThisWeekByVillage":
      $villages = Model::factory('case')->select_with_appts_this_week_by_village();
      echo json_encode(array("success" => true, "villages" => $villages->as_array()));
      break;
    case "getCasesNextWeek":
      $cases = Model::factory('case')->select_with_appts_next_week();
      echo json_encode(array("success" => true, "cases" => $cases->as_array()));
      break;
    case "getCasesNextWeekByVillage":
      $villages = Model::factory('case')->select_with_appts_next_week_by_village();
      echo json_encode(array("success" => true, "villages" => $villages->as_array()));
      break;
    case "getCasesOverdueLastWeek":
      $cases = Model::factory('case')->select_overdue_last_week();
      echo json_encode(array("success" => true, "cases" => $cases->as_array()));
      break;
    case "getCasesOverdueLastWeekByVillage":
      $villages = Model::factory('case')->select_overdue_last_week_by_village();
      echo json_encode(array("success" => true, "villages" => $villages->as_array()));
      break;
    default:
      echo json_encode(array("success" => false, "errors" => array("Please use a valid API action")));
    }
  }
}
