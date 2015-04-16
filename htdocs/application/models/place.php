<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place extends CI_Model {
	
	private $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'places';
	}
	
	public function getPopular($limit = 12)
	{
		$places = array();
		$this->db->select('p.id, p.name, p.info, (likes.total/(likes.total+dislikes.total)*100) AS popularity');
		$this->db->where('likes.total > dislikes.total');
		$this->db->from('places p');
		$this->db->join('v_place_popularity likes', 'p.id = likes.place_id AND likes.score_type = 1', 'left');
		$this->db->join('v_place_popularity dislikes', 'p.id = dislikes.place_id AND dislikes.score_type = -1', 'left');
		$this->db->order_by('popularity', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$places = $query->result_array();
		}
		return $places;
	} // - - end of getAll - - - - -
	
	public function findAll()
	{
		$places = array();
		$this->db->order_by('name');
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0)
		{
			$places = $query->result_array();
		}
		return $places;
	} // - - end of findAll - - - - -
	
	public function findByName($name, $offset = 0, $limit = 30)
	{
		$places = array();
		$this->db->order_by('name');
		$this->db->like('name', $name);
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() > 0)
		{
			$places = $query->result_array();
		}
		return $places;
	} // - - end of findByName - - - - -
	
	public function findByTag($tag_name, $offset = 0, $limit = 30)
	{
		$places = array();
		// get tags
		$this->db->select('id');
		$query = $this->db->get_where('tags', array('name' => $tag_name), 1);
		if ($query->num_rows() > 0)
		{
			$tag = $query->result_array();
			$tag = array_pop($tag);
			// get places
			$this->db->order_by('name');
			$this->db->like('tags', '{'.$tag['id'].'}');
			$query = $this->db->get($this->table_name);
			if ($query->num_rows() > 0)
			{
				$places = $query->result_array();
			}
		}
		return $places;
	} // - - end of findByTag - - - - -
	
	public function getPlaceInfo($place_id)
	{
		$place = array();
		// get place tag IDs
		$query = $this->db->get_where($this->table_name, array('id' => $place_id), 1);
		if ($query->num_rows() > 0)
		{
			$place = $query->result_array();
			$place = $place[0];
			// get tag names
			$place['tags'] = preg_replace("/[\{\}\s]+/","",$place['tags']);
			if(!empty($place['tags']))
			{
				$tags = array();
				$this->db->select('id, name, display_name');
				$this->db->where_in("id", explode(",", $place['tags']));
				$this->db->order_by('name');
				$query = $this->db->get('tags');
				if ($query->num_rows() > 0)
				{
					$place_tags = $query->result_array();
					foreach($place_tags as $tag)
					{
						$tags[$tag['id']] = array(
							'name' => $tag['name'],
							'display_name' => $tag['display_name'],
						);
					}
				}
				$place['tags'] = $tags;
			}
			else
			{
				$place['tags'] = array();
			}
			// get likes/dislikes
			$likes = 0;
			$dislikes = 0;
			$query = $this->db->get_where('v_place_popularity', array('place_id'=>$place_id));
			if ($query->num_rows() > 0)
			{
				$rows = $query->result_array();
				foreach($rows as $row)
				{
					if((int)$row['score_type'] == 1)
					{
						$likes = $row['total'];
					}
					if((int)$row['score_type'] == -1)
					{
						$dislikes = $row['total'];
					}
				}
			}
			$place['likes'] = $likes;
			$place['dislikes'] = $dislikes;
			// get photos
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
			$place['photos'] = $photos;
		}
		return $place;
	} // - - end of getPlaceTags - - - - -
	
	public function save($data, $user)
	{
		$saved = false;
		if(empty($data['id']))
		{
			// insert new record
			$data['creator_id'] = $user['id'];
			$inserted = $this->db->insert($this->table_name, $data);
			$saved = $this->db->insert_id();
		}
		else
		{
			// update record
			$data['last_editor_id'] = $user['id'];
			$this->db->where('id', $data['id']);
			$updated = $this->db->update($this->table_name, $data);
			$saved = !empty($updated);
		}
		return $saved;
	} // - - end of save - - - - -
}
