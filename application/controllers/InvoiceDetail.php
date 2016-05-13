<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InvoiceDetail extends CI_Controller {
	
		function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Content','',TRUE);
	}
	
	public function selectDetail()
	{
		$where = "invoiceId = '" . $_POST['keyvalue'] . "'";
		$data = array();
		$column = explode(',',$_POST['fields']);
		$no = $_POST['start'];
		$keyfields = explode(',',$_POST['keyfields']);
		$out = $this->Content->select($_POST['tablename'],$_POST['fields'],$where,null);
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
			
			if($_POST['save_method'] == "add" || $_POST['save_method'] == "edit")
			{
			$row[] = '&nbsp;&nbsp;<td><a class="btn btn-sm btn-primary" href="javascript:void()" onclick="editDetail(' . "'" . $_POST['tablename'] . "'" . ',' . "'" . $_POST['keyfields'] . "'" . ',' . "'" . $keyvalue  . "'" . ');">
			<i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;<a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_itDetail(' . "'" . $_POST['tablename'] . "'" . ',' . "'" . $_POST['keyfields'] . "'" . ',' . "'" . $keyvalue  . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a></td>';
			}
			else
			{
				$row[] = ' ';
			}
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
	
	public function insertDetail()
	{
		$column = explode(",","bagian_kendaraan,jenis_jasa,Price,Price2,Price3");
		$values = array();
		
		$values['invoiceId'] = $this->input->post('keyvalue2');
		$values['detailId'] = 1;
		$query = "select detailId FROM trans_invoice_detail where invoiceId ='" . $values['invoiceId'] . "'";
		$reff = $this->Content->select2($query);
		$countrows = $this->Content->countrows($query);
		if($countrows > 0)
		{
			foreach($reff as $reffp)
			{
				if(!(is_null($reffp->detailId)))
				$values['detailId'] = $reffp->detailId + 1;
			}
		}
		
		 for($i=0;$i < count($column);$i++)
		{	
			if(strpos($column[$i],"Price") !== FALSE)
			{
				$values[$column[$i]] = str_replace(".","",$this->input->post($column[$i]));
			}
			else
				$values[$column[$i]] = $this->input->post($column[$i]);
		} 
		
		$insert = $this->Content->save("trans_invoice_detail", $values);
		echo json_encode(array("status" => TRUE));
	}
	
	public function editDetail()
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
	
	public function UpdateDetail()
	{
		$column = explode(",","invoiceId,detailId,bagian_kendaraan,jenis_jasa,Price,Price2,Price3");
		//$keyvalues = explode(",",$this->input->post('keyvalue'));
		$key = array();
		$keys = array();
		$findkey = $this->Content->select2("SHOW KEYS FROM trans_invoice_detail WHERE Key_name = 'PRIMARY'");
		foreach($findkey as $findkeyp)
		{
			$key[] = $findkeyp->Column_name;
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
								$keys[$column[$i]] = $this->input->post($column[$i]);
								
								//log_message('info', ' keys ' . $keys[$column[$i]]);
							}
						}
				
			}
		} 
		$this->Content->update("trans_invoice_detail",$keys, $values);
		echo json_encode(array("status" => TRUE));
	}
	
	public function deleteDetail()
	{
		$this->Content->delete($_POST['tablename'],$_POST['keyfields'],$_POST['keyvalue']);
		echo json_encode(array("status" => TRUE));
	}
	
	public function fillddldetail()
	{
		$option = array();
		$query = "select * FROM reff_items";
		$reff = $this->Content->select2($query);
		
		foreach($reff as $reffp)
		{
				$option .= '<option value="'. $reffp->ItemName .'">'. $reffp->Description .'</option>';
		}
		
		$query = "select Price FROM reff_items LIMIT 0 , 1";
		$reff = $this->Content->select2($query);
		$reffvalue = "";
		foreach($reff as $reffp)
		{
				$reffvalue = number_format($reffp->Price,0,',','.');
		}
		
		$query = "select Price2 FROM reff_items LIMIT 0 , 1";
		$reff = $this->Content->select2($query);
		$reffvalue2 = "";
		foreach($reff as $reffp)
		{
				$reffvalue2 = number_format($reffp->Price2,0,',','.');
		}
		
		$query = "select Price3 FROM reff_items LIMIT 0 , 1";
		$reff = $this->Content->select2($query);
		$reffvalue3 = "";
		foreach($reff as $reffp)
		{
				$reffvalue3 = number_format($reffp->Price3,0,',','.');
		}
		
		if(isset($_POST['reff_value']))
		{ 
		$reffvalue = $_POST['reff_value']; 
		}
		
		if(isset($_POST['reff_value2']))
		{ 
		$reffvalue2 = $_POST['reff_value2']; 
		}
		
		if(isset($_POST['reff_value3']))
		{ 
		$reffvalue3 = $_POST['reff_value3']; 
		}
		
		if(count($option) > 0)
		{
		 $response = array(
        'success' => TRUE,
        'options' => $option,
		'reffvalue' => $reffvalue,
		'reffvalue2' => $reffvalue2,
		'reffvalue3' => $reffvalue3
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
	
	public function fillPrice()
	{
		$price;
		$price2;
		$price3;
		$query = "select Price,Price2,Price3 FROM reff_items where ItemName ='" . $_POST['bagian_kendaraan'] . "'";
		$reff = $this->Content->select2($query);
		foreach($reff as $reffp)
		{
				$price = number_format($reffp->Price,0,',','.');
				$price2 = number_format($reffp->Price2,0,',','.');
				$price3 = number_format($reffp->Price3,0,',','.');
		}
		
		if(count($price) > 0)
		{
		$response = array(
        'success' => TRUE,
        'price' => $price,
        'price2' => $price2,
        'price3' => $price3
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
	
	public function PrintInvoice()
	{
		$date = new DateTime($_POST['tanggal_invoice']);
		$tabledetail = "";
		$query="select * from trans_invoice_detail where invoiceId = '" . $_POST['invoiceId'] . "'";
		$reff = $this->Content->select2($query);
		foreach($reff as $reffp)
		{
			$tabledetail .= "<tr>
			<td>" . $reffp->detailId . "</td>
			<td>" . $reffp->bagian_kendaraan . "</td>
			<td>" . $reffp->jenis_jasa . "</td>
			<td>" . number_format($reffp->Price,0,',','.') . "</td>
			</tr>";
		}
		$table = "
		<table style=width:100%;>
		<tr>
		<td width=33%>
		<img src=" . base_url() . "resources/images/AFOBodyRepairLogo.png>
		</td>
		<td width=33% style=position:relative;text-align:center;>
		<div style=position:absolute;bottom:0;left:30%;><b>". $_POST['invoiceId'] ."</b></div>
		</td>
		<td width=34%>
		Kepada : <br />
		No. Polis : <br />
		Jenis / No. Pol : " . $_POST['jenis_kendaraan'] . "  /  " . $_POST['no_polisi_kendaraan'] . "<br />
		<b>Kategori</b> : <br />
		Pemilik : " . $_POST['Pemilik'] . "<br />
		No. Hp : " . $_POST['no_hp'] . "<br />
		Up : <br />
		</td>
		</tr>
		</table>
		<br />
		<div style=width:100%;margin:0 auto;>
        <b>ESTIMASI BIAYA</b>
		</div>
		<br />
		<div style=width:100%;margin:0 auto;>
        <b>JASA / SPAREPARTS</b>
		</div>
		<table border=1 style=width:100%; >
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Bagian Kendaraan</th>
                      <th>Jenis Jasa</th>
                      <th>Harga</th>
                    </tr>
                  </thead>
				  <tbody>" . $tabledetail . "
				  </tbody>
				  </table>
				  <br />
				  <br />
				  <div style=width:100%;text-align:left; >
				  <table>
				  <tr>
				  <td>Total Estimasi Biaya</td>
				  <td>:</td>
				  <td>.........................</td>
				  </tr>
				  <tr>
				  <td>DP</td>
				  <td>:</td>
				  <td>.........................</td>
				  </tr>
				  <tr>
				  <td>Sisa Tagihan</td>
				  <td>:</td>
				  <td>.........................</td>
				  </tr>
				  </table></div>
				  <br />
				  <div style=width:100%;text-align:right; >
				  <div style=padding-right:100px> 
				  Surabaya, " . $date->format("j F Y") . "
				  </div>
				  </div>
				  <div style=width:100%;text-align:left; >
				  <table width=100%>
				  <tr width=100%>
				  <td width=48% style=padding-left:50px;>
				  <table>
				  <tr><td>Customer</tr></td>
				  <tr><td><br /><br /><br />(...................)</tr></td>
				  <tr><td>Nama & Jabatan</tr></td>
				  </table>
				  </td>
				  <td width=48% align=right style=padding-right:100px;>
				  <table>
				  <tr><td align=right>Sales & Marketing</tr></td>
				  <tr><td align=right><br /><br /><br />(...................)</tr></td>
				  <tr><td align=right>Nama & Jabatan</tr></td>
				  </table>
				  </td>
				  </tr>
				  </table></div>";
				  
		$response = array(
        'success' => TRUE,
        'table' => $table,
		);
		echo json_encode($response);
	}



	public function PrintSPK()
		{
			$price;
			$date = new DateTime($_POST['tanggal_invoice']);
			$tabledetail = "";
			$query="select * from trans_invoice_detail where invoiceId = '" . $_POST['invoiceId'] . "'";
			$reff = $this->Content->select2($query);
			foreach($reff as $reffp)
			{
				if($_POST['jenisSPK'] == "1")
				{
					$price = number_format($reffp->Price2,0,',','.');
				}
				else
				{
					$price = number_format($reffp->Price3,0,',','.');
				}
				$tabledetail .= "<tr>
				<td>" . $reffp->detailId . "</td>
				<td>" . $reffp->bagian_kendaraan . "</td>
				<td>" . $reffp->jenis_jasa . "</td>
				<td>" . $price . "</td>
				<td>&nbsp;</td>
				</tr>";
			}
			$table = "
			<div style=width:100%;margin:0 auto;>
			<b>SURAT PERINTAH KERJA</b>
			</div>
			<table style=width:100%;>
			<tr>
			<td width=33%><table>
			<tr><td>Tanggal</td><td>:</td><td>" . $date->format("j F Y") . "</td></tr>
			<tr><td>Nama Sub</td><td>:</td><td>&nbsp;</td></tr>
			</table>
			</td>
			
			<td width=33% style=position:relative;text-align:center;><div style=position:absolute;bottom:0;left:30%;><b>". $_POST['SPKId'] ."</b></div></td>
			
			<td width=33%><table>
			<tr><td>Jenis Kendaraan No. Pol</td><td>:</td><td>" . $_POST['jenis_kendaraan'] . " / " . $_POST['no_polisi_kendaraan'] . "</td></tr>
			<tr><td>Pemilik</td><td>:</td><td>" . $_POST['Pemilik'] . "</td></tr>
			</table>
			</td>
			
			</tr>
			</table>
			<br />
			<table border=1 style=width:100%; >
					  <thead>
						<tr>
						  <th>No.</th>
						  <th>Item /Bagian Kendaraan</th>
						  <th>Jenis Jasa</th>
						  <th>Harga</th>
						  <th>Keterangan</th>
						</tr>
					  </thead>
					  <tbody>" . $tabledetail . "
					  <tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>Jumlah</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					  </tbody>
					  </table>
					  ";
					  
			$response = array(
			'success' => TRUE,
			'table' => $table,
			);
			echo json_encode($response);
		}
	}