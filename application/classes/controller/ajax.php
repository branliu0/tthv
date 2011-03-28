<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {
  public function action_get_villages() {
    if (isset($_GET['term'])) {
      $villages = Model::factory('case')->get_villages_like($_GET['term']);
    }
    else {
      $villages = Model::factory('case')->get_villages();
    }
    $array = array();
    foreach($villages as $village) {
      $array[] = $village["village_name"];
    }
    echo json_encode($array);
  }

  public function action_get_phcs() {
    if (isset($_GET['term'])) {
      $phcs = Model::factory('case')->get_phcs_like($_GET['term']);
    }
    else {
      $phcs = Model::factory('case')->get_phcs();
    }
    $array = array();
    foreach($phcs as $phc) {
      $array[] = $phc["phc_name"];
    }
    echo json_encode($array);
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
