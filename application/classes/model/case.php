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
    return DB::select()->from('cases')->where('id', '=', $id)->limit(1)->execute();
	}

	public function add($post) {
		$id = DB::insert('cases', array_keys($post))->values($post)->execute();
		return $id;
	}

  public function get_villages() {
    return DB::query(Database::SELECT, 'SELECT DISTINCT village_name FROM cases')->execute()->as_array();
  }

  public function get_villages_like($term='') {
    $term .= '%';
    return DB::query(Database::SELECT, 'SELECT DISTINCT village_name FROM cases WHERE village_name LIKE :term')
      ->param(':term', $term)
      ->execute()->as_array();
  }

  public function get_phcs() {
    return DB::query(Database::SELECT, 'SELECT DISTINCT phc_name FROM cases')->execute()->as_array();
  }

  public function get_phcs_like($term='') {
    $term .= '%';
    return DB::query(Database::SELECT, 'SELECT DISTINCT phc_name FROM cases WHERE phc_name LIKE :term')
      ->param(':term', $term)
      ->execute()->as_array();
  }
}
