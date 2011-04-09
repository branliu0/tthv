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

  private static $firstnames = array(
    "Rahul", "Vinay", "Sudi", "Adithya", "Adithi", "Vyshaali", "Kavya",
    "Bhavna", "Anu", "Shrini", "Karthika", "Karthik", "Raj",
    "Vyas", "Narayan", "Mihir"
  );
  private static $lastnames = array(
    "Basu", "Mavram", "Bhat", "Balasubramanian", "Subramanian", "Chandrasekhara",
    "Jagadeesan", "Shankar", "Challa", "Prasad", "Gangapukar", "Pai", "Viswanathan",
    "Deo"
  );
  private static $villagenames = array(
    "Gadag", "Dharwar", "Belgaum", "Bellary", "PN Halli", "Kammasandra", "BR Hills", 
    "T Narasipura", "Davangere", "Bijapura", "Sugganahalli", "Thithimathi", 
    "VK Salgar", "Yemalur", "Shreemangala"
  );
  private static $phc = "Karnataka";
  private static $treatments = array(
    "HIV/AIDS", "Hepatitis", "Polio", "Injury", "Malaria", "Rabies",
    "Fever", "Common Cold"
  );

  
  private function random_mobile() {
    $mobile = "";
    for ($i = 0; $i < 10; $i++) {
      $mobile .= (string)rand(0,9);
    }
    return $mobile;
  }

  // Location in range [14-16],[74-76]
  private function random_location() {
    return (string)(2*lcg_value() + 14) . "," . (string)(2*lcg_value() + 74);
  }

  public function action_insert_data() {
    return;
    DB::query(Database::DELETE, 'DELETE FROM cases WHERE 1=1')->execute();
    DB::query(Database::DELETE, 'DELETE FROM appointments WHERE 1=1')->execute();
    $caseModel = Model::factory('case');
    $apptModel = Model::factory('appointment');
    foreach (self::$firstnames as $first) {
      foreach (self::$lastnames as $last) {
        $case = array();
        $case['patient_name'] = $first . " " . $last;
        $case['village_name'] = self::$villagenames[array_rand(self::$villagenames)];
        $case['phc_name'] = self::$phc;
        $case['mobile'] = $this->random_mobile();
        $case['clinic_access'] = "yes";
        $case['location'] = $this->random_location();

        list($case_id, $num_rows) = $caseModel->add_case($case);

        $child['child_name'] = self::$firstnames[array_rand(self::$firstnames)] . " " . self::$lastnames[array_rand(self::$lastnames)];
        $child['birth_date'] = strftime("%m-%d-%Y", strtotime("+" . strval(rand(1,365)) . " days", strtotime("today")));
        $child['case_id'] = $case_id;

        $apptModel->add_child($child);

        for ($i = 0; $i < 4; $i++) {
          $appt['child_name'] = $case['patient_name'];
          $appt['date'] = strtotime("-" . rand(1,365) . " days", strtotime("today"));
          $appt['treatment'] = self::$treatments[array_rand(self::$treatments)];
          $appt['case_id'] = $case_id;
          $appt['checked_in'] = (rand(1,2) == 1) ? $appt['date'] : 0;
          $appt['message'] = $apptModel->generateMessage($appt['child_name'], $appt['treatment'], $appt['date']);
          $apptModel->add_appointment($appt);
        }
      }
    }
  }
}
