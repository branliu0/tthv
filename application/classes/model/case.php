<?php defined('SYSPATH') or die('No direct script access.');

class Model_Case extends Model {
	public function select_phcs() {
		return DB::query(Database::SELECT, 'SELECT COUNT(*) as total, phc_name FROM cases GROUP BY phc_name')->execute();
	}

	public function select_by_phc_name($phc_name) {
		return DB::query(Database::SELECT, 'SELECT * FROM cases WHERE phc_name=:phc_name')
			->param(':phc_name', $phc_name)
			->execute();
	}

	public function select_by_id($id) {
    // return DB::query(Database::SELECT, 'SELECT TOP(1) * FROM cases WHERE id=:id')
    return DB::query(Database::SELECT, 'SELECT * FROM cases WHERE id=:id LIMIT 1')
      ->param(':id', $id)
      ->execute();
	}

  public function select_with_appts_today() {
    $today = new DateTime("today");
    return DB::query(Database::SELECT, 'SELECT c.id, c.patient_name, c.village_name, 
      c.phc_name FROM cases c INNER JOIN appointments a
      ON c.id = a.case_id WHERE a.date = :today')
      ->param(':today', $today->getTimestamp())
      ->execute();
  }

	public function add($post) {
    $id = DB::insert('cases', array_keys($post))
      ->values($post)
      ->execute();
		return $id;
	}

  public function get_villages() {
    return DB::query(Database::SELECT, 'SELECT DISTINCT village_name FROM cases')
      ->execute();
  }

  public function get_villages_like($term='') {
    $term .= '%';
    return DB::query(Database::SELECT, 'SELECT DISTINCT village_name FROM cases WHERE village_name LIKE :term')
      ->param(':term', $term)
      ->execute();
  }

  public function get_phcs() {
    return DB::query(Database::SELECT, 'SELECT DISTINCT phc_name FROM cases')
      ->execute();
  }

  public function get_phcs_like($term='') {
    $term .= '%';
    return DB::query(Database::SELECT, 'SELECT DISTINCT phc_name FROM cases WHERE phc_name LIKE :term')
      ->param(':term', $term)
      ->execute();
  }
}
