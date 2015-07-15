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
				$this->load->model( 'Tag', 'tags_db' );
				$tag_ids = explode(",", $place['tags']);
				$place['tags'] = $this->tags_db->getTagsById($tag_ids);
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
			$this->load->model( 'Photo', 'photos_db' );
			$place['photos'] = $this->photos_db->getByPlace($place_id);
			// get products
			$this->load->model( 'Product', 'products_db' );
			$place['products'] = $this->products_db->getByPlace($place_id);
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
