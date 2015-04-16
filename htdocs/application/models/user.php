<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User extends CI_Model {
	
	private $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'users';
	}
	
	public function loginUser($user, $pass)
	{
		$user_data = false;
		// get places
		$this->db->select('id, username, password');
		$query = $this->db->get_where($this->table_name, array('username' => $user), 1);
		if ($query->num_rows() > 0)
		{
			$row_data = $query->result_array();
			$row_data = array_pop($row_data);
			if(function_exists('password_verify'))
			{
				if(password_verify ($pass, $row_data['password']))
				{
					unset($row_data['password']);
					$user_data = $row_data;
				}
			}
			else
			{
				if($row_data['password'] === crypt($pass, $row_data['password']))
				{
					unset($row_data['password']);
					$user_data = $row_data;
				}
			}
		}
		return $user_data;
	} // - - end of loginUser - - - - -
	
	public function likePlace($user_id, $place_id)
	{
		$liked = false;
		$this->db->select('id');
		$conditions = array('user_id'=>$user_id, 'place_id'=>$place_id);
		$query = $this->db->get_where('user_place_likes', $conditions, 1);
		if ($query->num_rows() > 0)
		{
			// update
			$data = array('score' => 1);
			$this->db->where($conditions);
			$liked = array();
			$liked['success'] = $this->db->update('user_place_likes', $data);
			$liked['changed'] = ($this->db->affected_rows() > 0);
			$liked['new'] = false;
		}
		else
		{
			// insert
			$data = $conditions;
			$data['score'] = 1;
			$liked = array();
			$liked['success'] = $this->db->insert('user_place_likes', $data);
			$liked['new'] = true;
		}
		return $liked;
	} // - - end of likePlace - - - - -
	
	public function dislikePlace($user_id, $place_id)
	{
		$liked = false;
		$this->db->select('id');
		$conditions = array('user_id'=>$user_id, 'place_id'=>$place_id);
		$query = $this->db->get_where('user_place_likes', $conditions, 1);
		if ($query->num_rows() > 0)
		{
			// update
			$data = array('score' => -1);
			$this->db->where($conditions);
			$disliked = array();
			$disliked['success'] = $this->db->update('user_place_likes', $data);
			$disliked['changed'] = ($this->db->affected_rows() > 0);
			$disliked['new'] = false;
		}
		else
		{
			// insert
			$data = $conditions;
			$data['score'] = -1;
			$disliked = array();
			$disliked['success'] = $this->db->insert('user_place_likes', $data);
			$disliked['new'] = true;
		}
		return $disliked;
	} // - - end of dislikePlace - - - - -
}

