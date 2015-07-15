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
	
	public function getByPlace($place_id) {
		$photos = array();
		$query = $this->db->get_where('photos', array('place_id'=>$place_id));
		if ($query->num_rows() > 0)
		{
			$rows = $query->result_array();
			foreach($rows as $row)
			{
				$pw = PHOTO_WIDTH;
				$ph = PHOTO_HEIGHT;
				$tw = PHOTO_THUMB_WIDTH;
				$th = PHOTO_THUMB_HEIGHT;
				list($name, $ext) = explode(".", $row['filename']);
				$thumb_name = "{$name}_thumb.{$ext}";
				$photo_dir = BASEPATH."../img/photos";
				// photo
				$photo_file = "$photo_dir/$place_id/{$row['filename']}";
				if(is_readable($photo_file))
				{
					$photo_src = site_url("/img/photos/$place_id/{$row['filename']}");
				}
				else
				{
					$photo_src = "http://placehold.it/{$pw}x{$ph}/4D99E0/ffffff.png&text={$pw}x{$ph}";
				}
				// thumbnail
				$photo_thumb = "$photo_dir/$place_id/$thumb_name";
				if(is_readable($photo_thumb))
				{
					$thumb_src = site_url("/img/photos/$place_id/$thumb_name");
				}
				else
				{
					$thumb_src = "http://placehold.it/{$pw}x{$ph}/4D99E0/ffffff.png&text={$pw}x{$ph}";
				}
				//
				$photos[$row['id']] = array(
					'thumb'  => $thumb_src,
					'src'    => $photo_src,
					'width'  => $pw,
					'height' => $ph,
					'descr'  => $row['description'],
				);
			}
		}
		return $photos;
	}
	
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
