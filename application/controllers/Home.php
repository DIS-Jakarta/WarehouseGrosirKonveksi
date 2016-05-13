<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 
	 
	 
	 documentation for website :
	 
	 $this->data['tablename'] -- fill with tablename to show ( ex : $this->data['tablename'] = "abc" )
	 $this->data['fields'] -- fill with fields from table to show
	 $this->data['keyfields'] -- fill with primary key from table ( for now just one primary key, multiple primary key coming soon )
	 $this->data['refftable'] -- include this with reference table when contain reference table from other table
	 $this->data['refffield'] -- include this when field from reference table contain reference table from other table
	 
	 log_message('info', ' keys ' . $keys[$column[$i]]); -- > to trace
	 
	 */
	 
	 public $data = array();
	 public $data2 = array();
	 
	 function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Europe/Lisbon');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		 $this->load->model('user','',TRUE);
		 $this->load->model('Content','',TRUE);
		
		  if($this->session->userdata('logged_in'))
	   {
	   	 $session_data = $this->session->userdata('logged_in');
		 $this->data['userid'] = $session_data['userid'];
		 $this->data['groupid'] = $session_data['groupid'];
		 $this->data['full_name'] = $session_data['full_name'];
		 $this->data['address'] = $session_data['address'];
		 $this->data['phone_number'] = $session_data['phone_number'];
		 $this->data['email_address'] = $session_data['email_address'];
		 $this->data['is_login'] = $session_data['is_login'];
		 $this->data['logged_in'] = $session_data['logged_in'];
		 
		 $this->data2['menuStok'] = $this->user->getMenu($this->data['groupid'],1);
		 $this->data2['menuUser'] = $this->user->getMenu($this->data['groupid'],2);
		 $this->data2['menuGrupmenu'] = $this->user->getMenu($this->data['groupid'],3);
		 $this->data2['menuItem'] = $this->user->getMenu($this->data['groupid'],4);
		 $this->data2['menuGrup'] = $this->user->getMenu($this->data['groupid'],5);
		 $this->data2['menuCekStok'] = $this->user->getMenu($this->data['groupid'],6);
		}
	}
	 
	public function index()
	{
		
		 if($this->session->userdata('logged_in'))
	   {
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 
	   else
	   {
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	public function Items()
	{	
	   if($this->session->userdata('logged_in'))
	   {
	   $this->data['tablename']= "reff_items";
	   $this->data['menuid'] = "4";
	   $data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
	   $query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields;
		if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 		   
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],null,null,$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentItems', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	public function User()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
	   
	   $this->data['tablename'] = "reff_users"; 
	   $this->data['menuid'] = "2";
	   $data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
	   log_message('info', ' isAdd : ' . $data3['isAdd']);
	   $query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
		   if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],null,null,$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentUser', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	// for mapping Group Menu - User
	public function GroupMenu()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
		$this->data['tablename'] = "reff_groupmenu"; 
		$this->data['menuid'] = "3";
		$data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
		$query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
		   if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],"groupid","groupid != '11111'",$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentGroupUser', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	// For Group Menus
	public function GroupMenus()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
		$this->data['tablename'] = "reff_groupid"; 
		$this->data['menuid'] = "5";
		$data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
		$query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
		   if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],"groupid","groupid != '11111'",$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentGroupMenu', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	/* public function Invoice()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
		$this->data['tablename'] = "trans_invoice"; 
		$this->data['menuid'] = "1";
		$this->data['getId'] = "invoiceId,SPKId";
		$data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
		$query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],null,null,$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentInvoice', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	} */
	
	public function Stok()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
		$this->data['tablename'] = "trans_stock"; 
		$this->data['menuid'] = "1";
		$this->data['getId'] = "Id";
		$data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
		$query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
		   if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],null,null,$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentStok', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	public function CekStok()
	{	
		 if($this->session->userdata('logged_in'))
	   { 
		$this->data['tablename'] = "reff_itemss"; 
		$this->data['menuid'] = "6";
		$this->data['getId'] = "Id";
		//$data3['isAdd'] = $this->canAdd($this->data['groupid'],$this->data['menuid']);
		$query = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->data['tablename'] . "'" ;
	   $tablestructure = $this->Content->select2($query);
	   foreach( $tablestructure as $tablestructurep )
	   {
		   $this->data['fields'] = $tablestructurep->fields; 
		   $this->data['keyfields'] = $tablestructurep->keyfields; 
		   if($tablestructurep->Condition != null)
		   $this->data['condition'] = $tablestructurep->Condition; 
	   }
		 $data3['Items'] = $this->Content->select($this->data['tablename'],null,null,$this->data['keyfields']);
		 
		$this->load->view('home/header', $this->data);
		$this->load->view('home/navigation', $this->data2);
		$this->load->view('home/contentCekStok', $data3);
		$this->load->view('home/footer');
	   } 
	   else
	   {
		echo '<script type="text/javascript">alert("Please Login to see this menu."); </script>';
		 //If no session, redirect to login page
		$this->load->view('home/header');
		$this->load->view('home/navigation');
		$this->load->view('home/content');
		$this->load->view('home/footer');
	   } 	
	}
	
	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('Home', 'refresh');
	}
	
	public function canAdd($groupid,$menuid)
	{
		$isAdds = 0;
		$querymenu = $this->Content->select2("SELECT isAdd FROM reff_groupmenu WHERE groupid = '" . $groupid . "' AND menuid = '" .$menuid . "'" );
		foreach($querymenu as $querymenup)
		{
			$isAdds = $querymenup->isAdd;
		}
		return $isAdds;
	}
	
}
