<?php
Class User extends CI_Model
{
 function login($userid, $password)
 {
	
   $query = $this->db->query(
        "SELECT     userid, groupid, full_name, address, phone_number, email_address, is_login
        FROM        reff_users A
        WHERE       A.userid = '$userid'
        AND         A.password = '$password'
		AND			A.active = 1
        ");
		
  /*  $this -> db -> select('userid, password, groupid, full_name, address, phone_number, email_address, is_login, active');
   $this->db->join('comments', 'comments.id = blogs.id');
   $this -> db -> from('users');
   $this -> db -> where('username', $username);
   $this -> db -> where('active', 1);   
   $this -> db -> where('password', MD5($password));
   $this -> db -> limit(1); */
 
   
 
   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }
 
 function getMenu($groupid,$menu)
 {
   $query = $this->db->query(
		"SELECT A.menuid, A.menu_desc, A.menu_url, A.menu_image_url
		FROM reff_menu A
		LEFT JOIN reff_groupmenu B on A.menuid = B.menuid
		WHERE B.groupid = '$groupid' AND A.menuid = $menu and B.isView = 1" 
	);
	
	if($query -> num_rows() == 1)
   {
		
		foreach ($query->result() as $row){
            $results[] = array(
                'menuid' => $row->menuid,
                'menu_desc' => $row->menu_desc,
                'menu_url' => $row->menu_url,
                'menu_image_url' => $row->menu_image_url
            );
			//echo '<script type="text/javascript">alert("' . $row->menu_url . '"); </script>';
		
        }
        return $results;
   }
	
 }
 
}
?>