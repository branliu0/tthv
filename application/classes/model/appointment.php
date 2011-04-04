<?php defined('SYSPATH') or die('No direct script access.');

class Model_Appointment extends Model {

	const MESSAGE = "%child_name% has an appointment for %appt_name% on %date%";
	private static $child_appts = array(
		array(
			"name" => "Child Birth",
			"interval" => "0 weeks",
			"after" => true
		),
		array(
			"name" => "Oral Polio, DPT, Hepatitis B",
			"interval" => "6 weeks",
			"after" => true
    ),
    array(
      "name" => "Oral Polio, DPT, Hepatitis B",
      "interval" => "10 weeks",
      "after" => true
    ),
    array(
      "name" => "Oral Polio, DPT, Hepatitis B",
      "interval" => "14 weeks",
      "after" => true
    ),
    array(
      "name" => "Measles",
      "interval" => "10 months",
      "after" => true
    )
	);

	public function add_child($post) {
		$data['child_name'] = $post['child_name'];
		$data['case_id'] = $post['case_id'];

		$now = new DateTime("now");
		foreach(self::$child_appts as $appt) {
			$birth_date = new DateTime($post['birth_date']);

      // Calculate the date of the appointment
			if($appt['after']) {
				$data['date'] = $birth_date->add(DateInterval::createFromDateSTring($appt['interval']));
			}
			else {
				$data['date'] = $birth_date->sub(DateInterval::createFromDateString($appt['interval']));
			}

			// Don't add appointments that are for the past.
			if($data['date'] < $now) {
				continue;
			}
      // String format the message
			$data['message'] = preg_replace("/%appt_name%/", $appt['name'], self::MESSAGE);
			$data['message'] = preg_replace("/%child_name%/", $data['child_name'], $data['message']);
			$data['message'] = preg_replace("/%date%/", $data['date']->format("M j, Y"), $data['message']);

			$data['date'] = $data['date']->getTimestamp();
			$this->add_appointment($data);
		}
	}

	public function add_appointment($post) {
		return DB::insert('appointments', array_keys($post))->values($post)->execute();
    /*
    $data['child_name'] = $post['child_name'];
    $data['date'] = $post['date'];
    $data['message'] = $post['message'];
    $data['case_id'] = $post['case_id'];
		return DB::insert('appointments', array_keys($data))->values($data)->execute();
     */
	}

  public function select_by_id($id) {
    return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE id=:id LIMIT 1')
      ->param(':id', $id)
      ->execute();
  }

	public function select_by_case_id($id) {
    $yesterday = new DateTime("yesterday");
		return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE case_id=:case_id AND date > :yesterday ORDER BY date ASC')
			->param(':case_id', $id)
      ->param(':yesterday', $yesterday->getTimestamp())
			->execute();
	}

  public function select_all() {
    return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE date > :now ORDER BY date ASC')
      ->param(':now', time())
      ->execute();
  }

	public function delete_appointment($id) {
		// return DB::query(Database::DELETE, 'DELETE FROM appointments WHERE id=:id LIMIT 1')
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
