<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Content','',TRUE);
	}
	
	public function select()
	{
		$session_data = $this->session->userdata('logged_in');
		$data = array();
		$column = explode(',',$_POST['fields']);
		$no = $_POST['start'];
		$condition = $_POST['condition'];
		$out = $this->Content->get_datatables($_POST['tablename'],$column,$condition);
		$keyfields = explode(',',$_POST['keyfields']);
		$menu = $this->Content->select2("SELECT isView,isUpdate,isDelete FROM reff_groupmenu WHERE menuid = '" . $_POST['menuid'] . "' AND groupid = '" . $session_data['groupid'] . "'");
		
		foreach ($menu as $menup) 
		{
			$isView = $menup->isView;
			$isUpdate = $menup->isUpdate;
			$isDelete = $menup->isDelete;
		}
		//log_message('info', "update : " . $isUpdate . " delete : " . $isDelete );
		foreach ($out as $outp) 
		{
			$keyvalue = "";
			$no++;
			$row = array();
			for($i = 0;$i < count($column);$i++)
			{
				if($column[$i] == "password")
					$row[] = "********";
				else if($column[$i] == "active")
				{
					if($outp->$column[$i] == "1")
					$row[] = "ya";
					else
					$row[] = "tidak";
				}
				else if(substr($column[$i],0,2) == "is")
				{
					if($outp->$column[$i] == "1")
					$row[] = "ya";
					else
					$row[] = "tidak";
				}
				else if(strpos(strtolower($column[$i]), 'tgl') !== FALSE || strpos(strtolower($column[$i]), 'tanggal') !== FALSE)
				{
					$date = strtotime($outp->$column[$i]);
					$row[] = date('d/m/Y',$date);
				}
				else if(strpos($column[$i],"Price") !== FALSE)
				{
					$row[] = "Rp. " . number_format($outp->$column[$i],0,',','.');
				}
				else
				{
					$query = "SELECT * FROM reff_table WHERE maintable = '" . $_POST['tablename'] . "' AND refffield = '" . $column[$i] . "'";
					$countrows = $this->Content->countrows($query);
					if($countrows > 0)
					{
						$reff = $this->Content->select2($query);
						foreach($reff as $reffp)
						{
							$reff2 = $this->Content->select2($reffp->reffquery . " WHERE " . $reffp->refffield . " = '" . $outp->$column[$i] . "'");
								foreach($reff2 as $reffp2)
								{
									$row[] = $reffp2->description;
								}
						}
					}
					else
					$row[] = $outp->$column[$i];
					
					for($c = 0;$c < count($keyfields);$c++)
					{
						if($column[$i] == $keyfields[$c])
						{
							if($keyvalue == "")
							$keyvalue .= $outp->$column[$i];
							else
							$keyvalue .= "," . $outp->$column[$i];
						}
					}
				}
			}
			
			$ViewEditdelete = "";
			if($isView == "1"){
			$ViewEditdelete = '<td><a class="btn btn-sm btn-success" href="javascript:void()" onclick="view(' . "'" . $_POST['tablename'] . "'" . ',' . "'" . $_POST['keyfields'] . "'" . ',' . "'" . $keyvalue  . "'" . ');">
			<i class="glyphicon glyphicon-eye-open"></i></a>';
			}
			if($isUpdate == "1"){
			$ViewEditdelete .= '<td><a class="btn btn-sm btn-primary" href="javascript:void()" onclick="edit(' . "'" . $_POST['tablename'] . "'" . ',' . "'" . $_POST['keyfields'] . "'" . ',' . "'" . $keyvalue  . "'" . ');">
			<i class="glyphicon glyphicon-edit"></i></a>';
			}
			if($isDelete == "1"){
			$ViewEditdelete .= '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_it(' . "'" . $_POST['tablename'] . "'" . ',' . "'" . $_POST['keyfields'] . "'" . ',' . "'" . $keyvalue  . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a></td>
			';
			}
			$row[] = $ViewEditdelete;
			
			$data[] = $row;
		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Content->count_all($_POST['tablename']),
						"recordsFiltered" => $this->Content->count_filtered($_POST['tablename'],$column),
						"data" => $data,
				);
		echo json_encode($output);
	}

	public function insert()
	{
		$column = explode(",",$this->input->post('columnname'));
		$values = array();
		 for($i=0;$i < count($column);$i++)
		{	if($column[$i] == "password")
				$values[$column[$i]] = MD5($this->input->post($column[$i]));
			else if(strpos($column[$i],"Price") !== FALSE)
			{
				$values[$column[$i]] = str_replace(".","",$this->input->post($column[$i]));
			}
			else
				$values[$column[$i]] = $this->input->post($column[$i]);
		} 
		$insert = $this->Content->save($this->input->post('tablename'), $values);
		echo json_encode(array("status" => TRUE));
	}
	
	public function update()
	{
		$column = explode(",",$this->input->post('columnname'));
		$keyvalues = explode(",",$this->input->post('keyvalue'));
		$key = array();
		$keys = array();
		$queryfindkey = "SELECT * FROM reff_tablekey WHERE tablename = '" . $this->input->post('tablename') . "'";
		$findkey = $this->Content->select2($queryfindkey);
		foreach($findkey as $findkeyp)
		{
			$key = explode(",",$findkeyp->keyfields);
		}
		
		$values = array();
		 for($i=0;$i < count($column);$i++)
		{
			if($column[$i] == "password")
			{
				if(!($this->input->post($column[$i]) == null))
					$values[$column[$i]] = MD5($this->input->post($column[$i]));
			}
			else if(strpos($column[$i],"Price") !== FALSE)
			{
				$values[$column[$i]] = str_replace(".","",$this->input->post($column[$i]));
			}
			else
			{
				$values[$column[$i]] = $this->input->post($column[$i]);
				
						for($k=0;$k < count($key);$k++)
						{
							
							if($column[$i] == $key[$k])
							{								
								$keys[$column[$i]] = $keyvalues[$k];
								
								//log_message('info', ' keys ' . $keys[$column[$i]]);
							}
						}
				
			}
		} 
		$this->Content->update($this->input->post('tablename'),$keys, $values);
		echo json_encode(array("status" => TRUE));
	}
	
	public function edit()
	{
		$data = $this->Content->get_by_id($_POST['tablename'],$_POST['keyfields'],$_POST['keyvalue'],null);
		// if(array_key_exists('Price',$data))
		// {
			// $data->Price = number_format($data->Price,0,',','.');
		// }
		foreach($data as $key=>$value)
		{
		if(stristr($key,'Price')!==FALSE)
			$data->$key = number_format($data->$key,0,',','.');
		}
	
		
		//log_message('info', print_r($data,true));
		echo json_encode($data);
	}
	
	public function delete()
	{
		$this->Content->delete($_POST['tablename'],$_POST['keyfields'],$_POST['keyvalue']);
		echo json_encode(array("status" => TRUE));
	}
	
	
	public function fillddl()
	{
		$option = array();
		$query = "SELECT * FROM reff_table WHERE maintable = '" . $_POST['tablename'] . "' AND refffield = '" . $_POST['reff_column'] . "'";
		$countrows = $this->Content->countrows($query);
		if($countrows > 0)
		{
			$reff = $this->Content->select2($query);
						foreach($reff as $reffp)
						{
							$reff2 = $this->Content->select2($reffp->reffqueryedit);
							foreach($reff2 as $reffp2)
							{
								$option .= '<option value="'. $reffp2->$_POST['reff_column'] .'">'. $reffp2->description .'</option>';
							}
						}
		}
		
		$reffvalue = "";
		if(isset($_POST['reff_value']))
		{ 
		$reffvalue = $_POST['reff_value']; 
		}

		
		if(count($option) > 0)
		{
		 $response = array(
        'success' => TRUE,
        'options' => $option,
		'reffvalue' => $reffvalue
		);
		}
		else
		{
			$response = array(
        'success' => false
		);
		}
		
		
		echo json_encode($response);
	}
	
	public function selectwquery()
	{
		$response = array(
        'success' => false
		);
		
		
		$databefore = 0;
		$abs = 0;
		$Jenis = "";
		//log_message('info',print_r('id : ' . $_POST['id'],true));
		$out = $this->Content->select2("select Quantity,Jenis,ItemName from trans_stock where Id ='" . $_POST['id'] . "'");
		foreach($out as $outs)
		{
			$databefore = $outs->Quantity;
			$Jenis = $outs->Jenis;
			$ItemName = $outs->ItemName;
		}
		
		if(($Jenis == "barang keluar" && $_POST['method'] == "update") || ($Jenis == "barang masuk" && $_POST['method'] == "delete"))
		{
			$Jenis = " - ";
		}
		else if(($Jenis == "barang keluar" && $_POST['method'] == "delete") || ($Jenis == "barang masuk" && $_POST['method'] == "update"))
		{
			$Jenis = " + ";
		}
		
		//log_message('info',print_r('databefore : ' . $databefore,true));
		if($_POST['method'] == "delete")
		{
		$abs = $databefore;
		}
		else
		$abs = $_POST['Quantity'] - $databefore;
	
		$querysave = "select IFNULL(Quantity,0) " . $Jenis . " ("  . $abs . ") from reff_items where IFNULL(Quantity,0) " . $Jenis . " ("  . $abs . ") >= 0 and ItemName = '" . $ItemName . "'";
		log_message('info','$querysave : ' . $querysave);
		if($this->Content->countrows($querysave) > 0)
		{
			$querys = $this->Content->select2("select IFNULL(Quantity,0) " . $Jenis . " ("  . $abs . ") as 'quantitys' from reff_items where IFNULL(Quantity,0) " . $Jenis . " ("  . $abs . ") >= 0 and ItemName = '" . $ItemName . "'");
			foreach($querys as $queryss)
			{
				$qtys = $queryss->quantitys;
			}
			$keys["ItemName"] = $ItemName;
			$values["Quantity"] = $qtys;
			$this->Content->update("reff_items",$keys, $values);
			$response = array(
			'success' => TRUE);
		}

		// else if ($_POST['method'] == "delete")
		// {
			// $Quantity = "";
			// $Jenis = "";
			// $ItemName = "";
			// $out = $this->Content->select2("select Quantity,Jenis,ItemName from trans_stock where Id ='" . $_POST['id'] . "'");
			// foreach($out as $outs)
			// {
				// $Quantity = $outs->Quantity;
				// $Jenis = $outs->Jenis;
				// $ItemName = $outs->ItemName;
			// }
			
				// if($Jenis == "barang masuk")
				// {
					// $Jenis = " - ";
				// }
				// else
				// {
					// $Jenis = " + ";
				// }
				
				// $_POST['query'] = str_replace("{__ITEMNAME__}",$ItemName, $_POST['query']);
				// $_POST['query'] = str_replace("{__JENIS__}",$Jenis, $_POST['query']);
				// $_POST['query'] = str_replace("{__JUMLAHSTOK__}",$Quantity, $_POST['query']);
				// log_message('info', print_r($_POST['query'],true));
		// }
	
			// $countrows = $this->Content->countrows($_POST['query']);
			// if($countrows > 0)
			// {
				// $out= $this->Content->selectwquery($_POST['query']);
				// foreach($out as $outp)
				// {
					// if($outp->datas > 0)
					// {
					// $keys["ItemName"] = $_POST['keys'];
					// $values["Quantity"] = $outp->datas;
					// $this->Content->update("reff_items",$keys, $values);
					// $response = array(
					// 'success' => TRUE
					// );
					// }
					// else
				// }
			// }
		echo json_encode($response);
	}
	

	public function selectreturnvalquery()
	{
		log_message('info', print_r("query : " . $_POST["Query"],true));
		$response = array(
		'success' => FALSE);
		try
		{
			$out = $this->Content->select2($_POST["Query"]);
			$countrows = $this->Content->countrows($_POST["Query"]);
			if($countrows > 0)
			{
				foreach($out as $outs)
				{
					$response = array(
					'success' => TRUE,
					$_POST["fieldname"] => $outs->$_POST["fieldname"]
					);
				}
			}
		}
		catch (Exception $e) {
			log_message('info', print_r($e->getMessage(),true));
		}
		echo json_encode($response);
	}
	
	public function savestokbarang()
	{
		try
		{
			$dataerror = "";
			$count = 0;
			$error = FALSE;
			$databeforesave = array();
			$dbs = array();
			$data = json_decode(stripslashes($_POST['data']),true);
			log_message('info', print_r($data,true));
			$response = array(
			'success' => FALSE);
			foreach($data as $item)
			{
				if(array_key_exists($item["ItemName"],$databeforesave))
				{
					$databeforesave[$item["ItemName"]] = $databeforesave[$item["ItemName"]] + $item["Quantity"];
				}
				else
				{
					// $keys = array($item["ItemName"]);
					$databeforesave[$item["ItemName"]] = $item["Quantity"];
					// $array_to_push = array (
					// $item["ItemName"] => $item["Quantity"]
					// );
					// array_push($databeforesave,$array_to_push);
				}
					//log_message('info', "key datab : " . print_r($databeforesave,true));
				// foreach($databeforesave as $key => $databs)
				// {
					// log_message('info', print_r("key databs : " . $databs,true));
					// log_message('info', print_r("key databs : " . $key,true));
					// if(array_key_exists($key,$dbs));
					// {
						// array_push($dbs,$key,($dbs[$key] + $databs));
						//= $dbs[key($databeforesave)] + $databs;
					// }
					
					// if(!(array_key_exists($key,$dbs)))
					// {
						// array_push($dbs,$key,$databs);
					// }
				// }
				$count++;
			}
			
			log_message('info', "key datab : " . print_r($databeforesave,true));
			log_message('info', "jenis : " . $_POST['jenis']);
			
			foreach($databeforesave as $key => $value)
			{
				if($_POST['jenis'] == "barang keluar")
				{
					$querysave = "select 1 from reff_items where IFNULL(Quantity,0) - "  . $databeforesave[$key] . " >= 0 and ItemName = '" . $key . "'";
					if($this->Content->countrows($querysave) > 0)
					{
	
					}
					else
					{
						$error = TRUE;
						if($dataerror == "")
							$dataerror = $key;
						else
							$dataerror = $dataerror .", " . $key;
					}
				}
				else
				{
					$this->Content->updatewquery("Update reff_items set Quantity = IFNULL(Quantity,0) + " . $databeforesave[$key] . " where ItemName = '" . $key . "'");
					$out = $this->Content->select2("select IFNULL(max(id),0) + 1 as 'Id' from trans_stock");
					foreach($out as $outs)
					{
					$item['Id'] = $outs->Id;
					}
					$item['ItemName'] = $key;
					$item['Jenis'] = $_POST['jenis'];
					$item['Quantity'] = $databeforesave[$key];
					$item['Tgl_Barang_Masuk'] = date('Y-m-d H:i:s');
					$insert = $this->Content->save('trans_stock', $item);
				}
			}
			
			if(!($error) && $_POST['jenis'] == "barang keluar")
			{
				foreach($databeforesave as $key => $value)
				{
					$this->Content->updatewquery("Update reff_items set Quantity = Quantity - " . $databeforesave[$key] . " where ItemName = '" . $key . "'");
						$out = $this->Content->select2("select IFNULL(max(id),0) + 1 as 'Id' from trans_stock");
						foreach($out as $outs)
						{
						$item['Id'] = $outs->Id;
						}
						$item['ItemName'] = $key;
						$item['Jenis'] = $_POST['jenis'];
						$item['Quantity'] = $databeforesave[$key];
						$item['Tgl_Barang_Masuk'] = date('Y-m-d H:i:s');
						$insert = $this->Content->save('trans_stock', $item);
				}
			}
				
				$response = array(
				'success' => !($error),
				'dataerror' => $dataerror);
			// foreach($dbs as $dbsave)
			// {
				// if($_POST['jenis'] == "barang keluar")
				// {
					// $querysave = "select 1 from reff_items where IFNULL(Quantity,0) - "  . $dbsave[key($dbsave)] . " > 0 and ItemName = '" . key($dbsave) . "'";
					// if($this->Content->countrows() > 0)
					// {
						// $this->Content->updatewquery("Update reff_items set Quantity = Quantity - " . $dbsave[key($dbsave)] . " where ItemName = '" . key($dbsave) . "'");
						// $out = $this->Content->select2("select max(id) + 1 as 'Id' from trans_stock");
						// foreach($out as $outs)
						// {
						// $item['Id'] = $outs->Id;
						// }
						// $item['Tgl_Barang_Masuk'] = date('Y-m-d H:i:s');
						// $insert = $this->Content->save('trans_stock', $item);
						
					// }
					// else
					// {
						// $error = TRUE;
						// if($dataerror == "")
							// $dataerror = key($dbsave);
						// else
							// $dataerror = "," . key($dbsave);
					// }
				// }
				// else
				// {
					// $this->Content->updatewquery("Update reff_items set Quantity = Quantity + " . $dbsave[key($dbsave)] . " where ItemName = '" . key($dbsave) . "'");
					// $out = $this->Content->select2("select max(id) + 1 as 'Id' from trans_stock");
					// foreach($out as $outs)
					// {
					// $item['Id'] = $outs->Id;
					// }
					// $item['Tgl_Barang_Masuk'] = date('Y-m-d H:i:s');
					// $insert = $this->Content->save('trans_stock', $item);
				// }
			// }
			
			// $response = array(
			// 'success' => $error,
			// 'dataerror' => $dataerror);
			
			// if($_POST['jenis'] == "barang masuk")
			// {
				// $this->Content->updatewquery("Update reff_items set Quantity = Quantity + " . $item["Quantity"] .
				// " where ItemName = '" . $item["ItemName"] . "'");
			// }
			// else
			// {
				// $this->Content->updatewquery("Update reff_items set Quantity = Quantity - " . $item["Quantity"] .
				// " where ItemName = '" . $item["ItemName"] . "'");
			// }
			// $out = $this->Content->select2("select max(id) + 1 as 'Id' from trans_stock");
			// foreach($out as $outs)
			// {
			// $item['Id'] = $outs->Id;
			// }
			// $item['Tgl_Barang_Masuk'] = date('Y-m-d H:i:s');
			// $insert = $this->Content->save('trans_stock', $item);
			// $response = array(
			// 'success' => TRUE);
		}
		catch (Exception $e){
			$response = array(
			'success' => FALSE);
		}
		echo json_encode($response);
	}
	

}