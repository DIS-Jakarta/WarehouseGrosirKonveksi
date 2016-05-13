<?php
Class Content extends CI_Model
{

	function select($tablename,$column, $where, $orderby)
	{
	$querySql = "";
	
	if(is_null($column))
	{
		$querySql =  $querySql . "SELECT * FROM " .$tablename . " ";
	}
	else
	{
		$querySql =  $querySql . "SELECT " . $column . " FROM " .$tablename . " ";
	}
	
	if(!(is_null($where)))
	{
		$querySql =  $querySql . "WHERE " . $where . " ";
	}
	
	if(!(is_null($orderby)))
	{
		$querySql =  $querySql . "ORDER BY " . $orderby;
	}
	
    $query = $this->db->query($querySql);
		
	 return $query->result();
	}
 
	public function select2($query)
	{
		$query = $this->db->query($query);
		
		if($query -> num_rows() > 0)
		{
		 return $query->result();
		}
		else
		{
		 return false;
		}
	}
	private function _get_datatables_query($tablename,$column)
	{
		
		$this->db->from($tablename);
		$i = 0;
	
		foreach ($column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	public function save($tablename,$data)
	{
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}

	public function update($tablename,$where, $data)
	{
		//log_message('info', $tablename . " " . $where . " " . $data);
		$this->db->update($tablename, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete($tablename,$key,$keyvalues)
	{
		$keys = explode(",",$key);
		$keyvaluess = explode(",",$keyvalues);
		
		for($i=0;$i < count($keys);$i++){
		$this->db->where($keys[$i], $keyvaluess[$i]);
		}
		$this->db->delete($tablename);
	}
	
	function get_datatables($tablename,$column,$condition)
	{
		$this->_get_datatables_query($tablename,$column);
		if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		if($condition != "")
			$this->db->where($condition);
		$query = $this->db->get();
		return $query->result();
	}
 
	public function count_all($tablename)
	{
		$this->db->from($tablename);
		return $this->db->count_all_results();
	}
 
	function count_filtered($tablename,$column)
	{
		$this->_get_datatables_query($tablename,$column);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function get_by_id($tablename,$key,$keyvalues,$column)
	{
		if(!(is_null($column)))
		{
			$this->db->select($column);
			
		}
		$this->db->from($tablename);
		$keys = explode(",",$key);
		$keyvaluess = explode(",",$keyvalues);
		
		for($i=0;$i < count($keys);$i++){
		$this->db->where($keys[$i], $keyvaluess[$i]);
		}
		$query = $this->db->get();

		return $query->row();
	}
	
	public function countrows($query)
	{
		$query = $this->db->query($query);
		
		return $query->num_rows();
	}
	
	public function selectwquery($query)
	{
		$query = $this->db->query($query);
		
		return $query->result();
	}
 
 /* function insert($tablename,$column,$values)
 {
	$querySql = "";
	$querySql = $querySql . "INSERT INTO " . $tablename . " ";
	if(!(is_null($column)))
	{
		$querySql = $querySql . "(" . $column . ") ";
	}
	
	$querySql = $querySql . "VALUES (" . $values . ") ";
	//echo '<script type="text/javascript">alert("' . $querySql . '"); </script>';
    if($this->db->query($querySql))
    {
	 return true;
    }
    else
    {
	 return false;
    }
 }
 
 function update($tablename,$update,$where)
 {
	$querySql = "";
	$querySql += "UPDATE " .$tablename . " ";

	if(!($update = NULL))
	{
		$querySql += "SET " . $update . "";
	}	
	
	if(!($where = NULL))
	{
		$querySql += "WHERE " + $where;
	}
		
    if($this->db->query($querySql))
    {
	 return true;
    }
    else
    {
	 return false;
    }
 }
 
  function delete($tablename,$where)
 {
	$querySql = "";
	$querySql += "DELETE FROM " .$tablename . " ";

	if(!($where = NULL))
	{
		$querySql += "WHERE " + $where;
	}
		
    if($this->db->query($querySql))
    {
	 return true;
    }
    else
    {
	 return false;
    }
 } */
}
?>