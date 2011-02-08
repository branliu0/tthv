<?php defined('SYSPATH') or die('No direct script access.');

class Model_Appointment extends Model {

	const MESSAGE = "You have an appointment for %appt_name% in one week";
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
		)
	);

	public function add_child($post) {
		$data['child_name'] = $post['child_name'];
		$data['case_id'] = $post['case_id'];

		$now = new DateTime("now");
		foreach(self::$child_appts as $appt) {
			$birth_date = new DateTime($post['birth_date']);
			$data['message'] = preg_replace("/%appt_name%/", $appt['name'], self::MESSAGE);
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
			$data['date'] = $data['date']->getTimestamp();
			$this->add_appointment($data);
		}
	}

	public function add_appointment($data) {
		return DB::insert('appointments', array_keys($data))->values($data)->execute();
	}

	public function select_by_case_id($id) {
		return DB::query(Database::SELECT, 'SELECT * FROM appointments WHERE case_id=:case_id')
			->param(':case_id', $id)
			->execute();
	}

	public function delete_appointment($id) {
		return DB::query(Database::DELETE, 'DELETE FROM appointments WHERE id=:id LIMIT 1')
			->param(':id', $id)
			->execute();
	}
}
