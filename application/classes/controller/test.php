<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_Template {

	public function action_index() {
		print_r(Model::factory('appointment')->delete_appointment(1));
	}

  public function action_test() {
    $today = new DateTime("today");
    $sevenDays = $today->add(DateInterval::createFromDateString("1 week"))->getTimestamp();
    $fourteenDays = $today->add(DateInterval::createFromDateString("1 week"))->getTimestamp();
    echo date("m-d-Y", $sevenDays) . "\n";
    echo date("m-d-Y", $fourteenDays) . "\n";
  }

} // End Welcome
