<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {
  public function action_get_villages() {
    if (isset($_GET['term'])) {
      $villages = Model::factory('case')->get_villages_like($_GET['term']);
    }
    else {
      $villages = Model::factory('case')->get_villages();
    }
    $villages = array_map(create_function('$x','return $x["village_name"];'), 
      $villages->as_array());
    echo json_encode($villages);
  }

  public function action_get_phcs() {
    if (isset($_GET['term'])) {
      $phcs = Model::factory('case')->get_phcs_like($_GET['term']);
    }
    else {
      $phcs = Model::factory('case')->get_phcs();
    }
    $phcs = array_map(create_function('$x', 'return $x["phc_name"];'), 
      $phcs->as_array());
    echo json_encode($phcs);
  }

  public function action_check_in() {
    if (!isset($_POST['id'])) {
      echo "Failure: Please POST the id of the appointment";
      return;
    }
    Model::factory('appointment')->check_in($_POST['id']);
    echo "success";
  }

  public function action_delete_appointment() {
    if (!isset($_POST['id'])) {
      echo "Failure: Please POST the id of the appointment to be deleted";
      return;
    }
    Model::factory('appointment')->delete_appointment($_POST['id']);
    echo "success";
  }
}
