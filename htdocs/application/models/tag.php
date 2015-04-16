<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag extends CI_Model {
	
	private $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'tags';
	}
	
	public function getAll()
	{
		$tags = array();
		$this->db->select('id, display_name, name');
		$this->db->order_by('display_name');
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0)
		{
			$tags = $query->result_array();
		}
		return $tags;
	} // - - end of getAll - - - - -
	
	public function findByName($name)
	{
		$tag = false;
		$this->db->select('id, display_name, name');
		$this->db->order_by('display_name');
		$query = $this->db->get_where($this->table_name, array('name' => $name), 1);
		if ($query->num_rows() > 0)
		{
			$tag = $query->result_array();
		}
		return $tag;
	} // - - end of findByName - - - - -
	
	public function save($data)
	{
		$saved = false;
		if(empty($data['id']))
		{
			// insert new record
			$inserted = $this->db->insert($this->table_name, $data);
			$saved = $this->db->insert_id();
		}
		else
		{
			// update record
			$this->db->where('id', $data['id']);
			$updated = $this->db->update($this->table_name, $data);
			$saved = !empty($updated);
		}
		return $saved;
	} // - - end of save - - - - -
}
