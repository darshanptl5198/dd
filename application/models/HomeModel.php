<?php

use PHPUnit\Framework\MockObject\UnknownClassException;

defined('BASEPATH') or exit('No direct script access allowed');

class HomeModel extends CI_Model
{


    public function insertdata($data)
    {

        $query = $this->db->insert('user', $data);
        if ($query) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    public function insertimage($data)
    {
        // Insert image data
        return $this->db->insert('images', $data);
    }

    public function updatemodel($data, $id)
    {
        $this->db->where('id', $id);
        return  $this->db->update('user', $data);
    }

    public function updateimage($id, $data)
    {
        $this->db->where('user_id', $id);
        return $this->db->update('images', $data);
    }
    
  
    public function get_image_array($id)
    {
    
        $this->db->from('images');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
    
        $imageArray = [];
        foreach ($query->result() as $row) {
            $imageArray[] = $row->image; 
        }
        return $imageArray;
    }
    

    public function getdata($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('user');
        return $query->row();
    }
  
    
    
    public function delete_image_record($fileName)
    {
        $this->db->where('image', $fileName);
        return $this->db->delete('images');
    }
    
    public function deleteImage($userId)
    {
        $this->db->where('user_id', $userId);
        $query = $this->db->delete('images');

        return $query;
    }
    public function deletedata($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->delete('user');

        return $query;
    }


    public function gettotalrow($key)
    {
        if ($key) {
            $this->db->like('name', $key); // Adjust the column name if needed
            $this->db->or_like('email', $key); // Adjust the column name if needed
        }
        
        return $this->db->count_all('user');
    }
    public function fetch_data($limit, $offset, $key = null)
    {
        if ($key) {
            $this->db->like('user.name', $key); 
            $this->db->or_like('user.email', $key); 
        }
    
        $this->db->select('user.id, user.name, user.email');
        $this->db->from('user');
        $this->db->limit($limit, $offset);
    
        $this->db->order_by('name', 'ASC');
      
        $query = $this->db->get();
        $users = $query->result();
    
        foreach ($users as $user) {
            $user->images = $this->get_image_array($user->id); // Fetch images for each user
        }
    
        return $users;
    }
    
}
