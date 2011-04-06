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

    // $yest = new DateTime("yesterday");
    // echo $yest->getTimestamp() . "\n";
    // echo strtotime("yesterday") . "\n";
    // echo strtotime("-1 days") . "\n";

    // echo strtotime("now") . "\n";
    // echo strtotime("today") . "\n";
  }

}
