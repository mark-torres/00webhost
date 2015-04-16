<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo extends CI_Model {
	
	private $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'photos';
	}
	
	public function getPlacePhotos($place_id)
	{
		$photos = array();
		$this->db->select('id, filename, description');
		$query = $this->db->get_where($this->table_name, array('place_id'=>$place_id));
		if ($query->num_rows() > 0)
		{
			$photos = $query->result_array();
		}
		return $photos;
	} // - - end of getPlacePhotos - - - - -
	
	public function addPlacePhoto($data)
	{
		$saved = false;
		if($this->db->insert($this->table_name, $data))
		{
			$saved = $this->db->insert_id();
		}
		return !empty($saved);
	} // - - end of addPlacePhoto - - - - -
	
	public function delete($photo_id)
	{
		$deleted = false;
		$query = $this->db->get_where($this->table_name, array('id' => $photo_id), 1);
		if ($query->num_rows() > 0)
		{
			$photo = $query->result_array();
			$photo = array_pop($photo);
			$photo_dir = BASEPATH."../img/photos";
			// get thumbnail name
			list($n, $e) = explode(".", $photo['filename']);
			$thumb_file = "{$n}_thumb.{$e}";
			// check if place directory is writeable (to delete the file)
			$place_dir = "$photo_dir/{$photo['place_id']}";
			if(is_writeable($place_dir))
			{
				unlink("$place_dir/$thumb_file");
				unlink("$place_dir/{$photo['filename']}");
				$deleted = $this->db->delete($this->table_name, array('id' => $photo_id)); 
			}
		}
		return $deleted;
	} // - - end of delete - - - - -
}
