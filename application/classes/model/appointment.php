<?php defined('SYSPATH') or die('No direct script access.');

class Model_Appointment extends Model {

	const MESSAGE = "%child_name% has an appointment for %treatment% on %date%";
	private static $child_appts = array(
		array(
			"treatment" => "Child Birth",
			"interval" => "0 weeks",
			"after" => true
		),
		array(
			"treatment" => "Oral Polio, DPT, Hepatitis B",
			"interval" => "6 weeks",
			"after" => true
    ),
    array(
      "treatment" => "Oral Polio, DPT, Hepatitis B",
      "interval" => "10 weeks",
      "after" => true
    ),
    array(
      "treatment" => "Oral Polio, DPT, Hepatitis B",
      "interval" => "14 weeks",
      "after" => true
    ),
    array(
      "treatment" => "Measles",
      "interval" => "10 months",
      "after" => true
    )
	);

  public function generateMessage($childName, $treatment, $timestamp) {
    $message = preg_replace("/%treatment%/", $treatment, self::MESSAGE);
    $message = preg_replace("/%child_name%/", $childName, $message);
    $message = preg_replace("/%date%/", strftime("%b %e, %Y", $timestamp), $message);
    return $message;
  }

	public function add_child($post) {
		$data['child_name'] = $post['child_name'];
		$data['case_id'] = $post['case_id'];

		$now = new DateTime("now");
		foreach(self::$child_appts as $appt) {
			$birth_date = new DateTime($post['birth_date']);

      // Calculate the date of the appointment
			if($appt['after']) {
				$data['date'] = $birth_date->add(DateInterval::createFromDateString($appt['interval']));
			}
			else {
				$data['date'] = $birth_date->sub(DateInterval::createFromDateString($appt['interval']));
			}

			// Don't add appointments that are for the past.
			if($data['date'] < $now) {
				continue;
			}

      $data['treatment'] = $appt['treatment'];
			$data['date'] = $data['date']->getTimestamp();
			$data['message'] = $this->generateMessage($data['child_name'], $data['treatment'], $data['date']);
			$this->add_appointment($data);
		}
	}

	public function add_appointment($post) {
		return DB::insert('appointments', array_keys($post))->values($post)->execute();
  }

  public function select_by_id($id) {
    return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE id=:id LIMIT 1')
      ->param(':id', $id)
      ->execute();
  }

	public function select_by_case_id($id) {
		return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE case_id=:case_id AND date >= :today ORDER BY date ASC')
			->param(':case_id', $id)
      ->param(':today', strtotime("today"))
			->execute();
	}

  public function select_by_village_name($village) {
    return DB::query(Database::SELECT, 'SELECT * FROM appointments a INNER JOIN cases c
      ON a.case_id=c.id WHERE c.village_name = :village ORDER BY a.date ASC')
      ->param(':village', $village)
      ->execute();
  }

  public function select_all() {
    return DB::query(Database::SELECT, 'SELECT * FROM appointments 
      WHERE date >= :today ORDER BY date ASC')
      ->param(':today', strtotime("today"))
      ->execute();
  }

	public function delete_appointment($id) {
    return DB::query(Database::DELETE, 'DELETE FROM appointments WHERE id=:id')
			->param(':id', $id)
			->execute();
	}

  public function check_in($id) {
    return DB::query(Database::UPDATE, 'UPDATE appointments SET checked_in=:now WHERE id=:id')
      ->param(':id', $id)
      ->param(':now', date())
      ->execute();
  }
}
