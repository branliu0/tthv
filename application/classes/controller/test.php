<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_Template {

	public function action_index() {
		print_r(Model::factory('appointment')->delete_appointment(1));
	}

  public function action_test() {
    $today = new DateTime("today");
    $sevenDays = $today->add(DateInterval::createFromDateString("1 week"))->getTimestamp();
    $fourteenDays = $today->add(DateInterval::createFromDateString("1 week"))->getTimestamp();
    echo $sevenDays . "\n";
    echo strtotime("+1 week", strtotime("today")) . "\n";

    echo strtotime("yesterday");

    $appts = Model::factory('appointment')->select_by_village_name($post['village_name']);
    // $yest = new DateTime("yesterday");
    // echo $yest->getTimestamp() . "\n";
    // echo strtotime("yesterday") . "\n";
    // echo strtotime("-1 days") . "\n";

    // echo strtotime("now") . "\n";
    // echo strtotime("today") . "\n";
  }

  public function action_api() {
    $curl =<<<EOD
curl --user tthv:tthv -d 'action=addCaseWithChildrenAndAppointments&village_name=Brandon&phc_name=Brandon&mobile=1234512345&clinic_access=yes&patient_name=Brandon&children=[{"child_name": "Brandon Liu", "birth_date": "03/26/2012"}]' http://remindavax.org/api/
EOD;
    exec($curl, $output);
    echo implode("\n", $output);
  }
}
