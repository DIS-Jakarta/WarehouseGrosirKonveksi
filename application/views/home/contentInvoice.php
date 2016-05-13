	<script type="text/javascript">
	$(document).ready(function(){
		$('#Price').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price2').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price3').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
	});
	
	var save_method2;
	var table2;
	var loadInvoiceId = true;
	function addInvoice()
	{
	  $('#invoiceId').prop('readonly', true);
	  $('#SPKId').prop('readonly', true);
	  $('#tanggal_invoice').prop('readonly', true);
	  $('#btndetail').prop('disabled', true);
	  $('#btnPrint').prop('disabled', true);
	  $('#btnPrint2').prop('disabled', true);
	  $('#btnPrint3').prop('disabled', true);
	}
	
	function fillInvoiceData()
	{
		 <?php 
	  if(isset($getId))
	  {
		  $ides = explode(",",$getId);
		  for($i=0;$i < count($ides);$i++)
		  {
		  echo '
						$.ajax({
						url : "' . site_url('id/getId') . '",
						type: "POST",
						"data": {
						"tablename" : "' . $tablename . '",
						"Id" : "' . $ides[$i] . '",
						},
						dataType: "JSON",
						success: function(data)
						{
							if(data.success)
							{				
							 $(\'[name="' . $ides[$i] . '"]\').val(data.id);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							alert("Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator");
						}
					});';
		  }
	  }
	  ?>
	  var fullDate = new Date();
	  var month = parseInt(fullDate.getMonth()) + 1;
	  var twoDigitMonth = month+"";if(month.length==1)	month="0" +month;
	  var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1)	twoDigitDate="0" +twoDigitDate;
	  var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + twoDigitDate;		
	  var elem = document.getElementById("tanggal_invoice");			
	  elem.value = currentDate;
	}
	
	function showdetail()
	{
			$('#modal_form').modal('hide');
			$('#modal_form2').modal('show');
			
			$(document).ready(function(){
			table2 = $('#table2').DataTable({ 
			
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"language": {
				"zeroRecords": "no data found"
			},
			"bInfo": false,
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url('InvoiceDetail/selectDetail')?>",
				"type": "POST",
				<?php 
				if(isset($tablename)){
				echo '
				"data": {
				"tablename" : "trans_invoice_detail",
				"fields" : "invoiceId,detailId,bagian_kendaraan,jenis_jasa,Price,Price2,Price3",
				"generateid" : "detailId",
				"keyfields" : "invoiceId,detailId",
				"keyvalue" : $(\'#invoiceId\').val(),
				"save_method" : save_method
				},';
				}
				?>			 
				},
				
				'iDisplayLength': 5,
				'bLengthChange': false,
				'bFilter': false,
				"columnDefs": [
				{
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
				},
				{
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
				},
				{
				  "targets": [ -1 ], //last column
				  "orderable": false, //set not orderable
				}
				]
			});
			});
		
	}
	
	function btnclosemodal2()
	{
		table2.destroy();
		$('#modal_form2').modal('hide');
		$('#modal_form').modal('show');	
		$('#keyvalue').val($('#invoiceId').val() + ',' + $('#SPKId').val());
		$('#tablename').val('trans_invoice');
		$('#columnname').val('invoiceId,SPKId,tanggal_invoice,jenis_kendaraan,no_polisi_kendaraan,Pemilik,no_hp');
	}
	
	function addDetail()
	{
		$('#Price').prop('readonly', true);
		$('#Price2').prop('readonly', true);
		$('#Price3').prop('readonly', true);
		save_method2 = 'add';
		$('#keyvalue2').val($('#invoiceId').val());
		$('#formDetail').show("blind");
	  <?php
			  echo '
						$.ajax({
						url : "' . site_url('InvoiceDetail/fillddldetail') . '",
						type: "POST",
						"data": {

						},
						dataType: "JSON",
						success: function(data)
						{
							if(data.success)
							{				
							$("#bagian_kendaraan").html(data.options);
							$("#Price").val(data.reffvalue);
							$("#Price2").val(data.reffvalue2);
							$("#Price3").val(data.reffvalue3);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});';
	  ?>
	}
	
	function editDetail(tablename,keyfields,keyvalue)
	{
		save_method2 = 'edit';
		$('#formDetail').show("blind");
		$('#formDetail')[0].reset(); // reset form on modals
		
		 //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('InvoiceDetail/editDetail')?>",
        type: "POST",
        dataType: "JSON",
		"data": {
			"tablename" : tablename,
			"keyfields" : keyfields,
			"keyvalue" : keyvalue
			},
			
        success: function(data)
        {
           <?php 
			
			$column = explode(",","invoiceId,detailId,bagian_kendaraan,jenis_jasa,Price,Price2,Price3");
			for($i=0;$i < count($column);$i++)
			{
				// tambahkan blank password
				if(!($column[$i] == "password"))
				{	
					/* if(isset($refftable))
					{
						if(!($column[$i] == $refffield))
						{
							echo " $('[name=" . '"' . $column[$i] . '"' . "]').val(data." . $column[$i] . ");";
						}
					}
					else
					*/	

						echo " $('[name=" . '"' . $column[$i] . '"' . "]').val(data." . $column[$i] . ");"; 
					
					echo '
						$.ajax({
						url : "' . site_url('Items/fillddl') . '",
						type: "POST",
						"data": {
						"tablename" : tablename,
						"reff_column" : "' . $column[$i] . '",
						"reff_value" : data.' . $column[$i] . '
						},
						dataType: "JSON",
						success: function(data)
						{
							if(data.success)
							{				
							$("' . '#' . $column[$i] . '").html(data.options);
							$("' . '#' . $column[$i] . '").val(data.reffvalue);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});';
				}
				if($column[$i] == "active")
				{
					
					echo 'if(data.active == "1"){
						$("#active").prop("checked",true);
					}
					else{
						$("#active").prop("checked",false);
					}';
				}
				if(substr($column[$i],0,2) == "is")
				{
					
					echo 'if(data.' . $column[$i] . ' == "1"){
						$("#' . $column[$i] . '").prop("checked",true);
					}
					else{
						$("#' . $column[$i] . '").prop("checked",false);
					}';
				}
				
				
				if($column[$i] == "password")
					echo "$('[name=" . '"' . $column[$i] . '"' . "]').attr(\"placeholder\", \"Leave blank if don't change password\");";
				
			}
			?>
            $('[name="keyvalue"]').val(keyvalue);
			$('[name="tablename"]').val(tablename);
			$('[name="columnname"]').val('<?php if(isset($fields)){ echo $fields; } ?>');
		}
	});
	}
	  
	
	function delete_itDetail(tablename,keyfields,keyvalue)
	{
		if(confirm('Are you sure delete this data?'))
      {
		  // ajax delete data to database
          $.ajax({
            url : "<?php echo site_url('InvoiceDetail/deleteDetail')?>",
            type: "POST",
            dataType: "JSON",
			"data": {
			"tablename" : tablename,
			"keyfields" : keyfields,
			"keyvalue" : keyvalue
			},
            success: function(data)
            {
               //if success reload ajax table
			   //alert('Success delete data');
               //$('#modal_form').modal('hide');
               reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
	  }
	}
	
	function removeDetail()
	{
		$('#formDetail').hide();
		$('#formDetail')[0].reset();
	}
	
	function reload_table2()
    {
      table2.ajax.reload(null,false); //reload datatable ajax 
    }
	
	function saveDetail()
	{
		var url;
      if(save_method2 == 'add') 
      {
          url = "<?php echo site_url('InvoiceDetail/insertDetail')?>";
      }
      else
      {
        url = "<?php echo site_url('InvoiceDetail/UpdateDetail')?>";
      }

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#formDetail').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
			   $('#formDetail')[0].reset();
			   $('#formDetail').hide();
               reload_table2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
	}
	
	function fillPrice()
	{
		var bagian_kendaraan = $("#bagian_kendaraan").find(":selected").val();
		<?php
			  echo '
						
						$(\'#formDetail button\').prop(\'disabled\', true);;
						$.ajax({
						url : "' . site_url('InvoiceDetail/fillPrice') . '",
						type: "POST",
						"data": {
						"bagian_kendaraan": bagian_kendaraan
						},
						dataType: "JSON",
						success: function(data)
						{
							if(data.success)
							{				
							$("#Price").val(data.price);
							$("#Price2").val(data.price2);
							$("#Price3").val(data.price3);
							$(\'#formDetail button\').prop(\'disabled\', false);;
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							$(\'#formDetail button\').prop(\'disabled\', false);;
						}
					});';
	  ?>
	}
	
	function printInvoice()
	{
		var table="";
		$.ajax({
            url : "<?php echo site_url('InvoiceDetail/PrintInvoice')?>",
            type: "POST",
            dataType: "JSON",
			"data": {
			"invoiceId" : $('#invoiceId').val(),
			"tanggal_invoice" : $('#tanggal_invoice').val(),
			"jenis_kendaraan" : $('#jenis_kendaraan').val(),
			"no_polisi_kendaraan" : $('#no_polisi_kendaraan').val(),
			"Pemilik" : $('#Pemilik').val(),
			"no_hp" : $('#no_hp').val(),
			},
            success: function(data)
            {
				var left = (screen.width/2)-(800/2);
				var top = 30;
				var docprint=window.open("about:blank", "_blank", 'height=600,width=800,top='+top+', left='+left); 
				docprint.document.open(); 
				docprint.document.write('<html><head><title>AFO BODY REPAIR INVOICE</title>'); 
				docprint.document.write('</head><body><center>');
				docprint.document.write(data.table);
				docprint.document.write('</center></body></html>'); 
				docprint.document.close(); 
				docprint.print();
				docprint.close();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
		
	}
	
	function printSPK(jenisSPK)
	{
		var table="";
		$.ajax({
            url : "<?php echo site_url('InvoiceDetail/PrintSPK')?>",
            type: "POST",
            dataType: "JSON",
			"data": {
			"invoiceId" : $('#invoiceId').val(),
			"SPKId" : $('#SPKId').val(),
			"tanggal_invoice" : $('#tanggal_invoice').val(),
			"jenis_kendaraan" : $('#jenis_kendaraan').val(),
			"no_polisi_kendaraan" : $('#no_polisi_kendaraan').val(),
			"Pemilik" : $('#Pemilik').val(),
			"jenisSPK" : jenisSPK,
			},
            success: function(data)
            {
				var left = (screen.width/2)-(800/2);
				var top = 30;
				var docprint=window.open("about:blank", "_blank", 'height=600,width=800,top='+top+', left='+left); 
				docprint.document.open(); 
				docprint.document.write('<html><head><title>AFO BODY REPAIR SPK</title>'); 
				docprint.document.write('</head><body><center>');
				docprint.document.write(data.table);
				docprint.document.write('</center></body></html>'); 
				docprint.document.close(); 
				docprint.print();
				docprint.close();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
	}
	
	
	</script>
	<section id="main" class="column">
		<!--<article class="module width_full">
			<header><h3>Stats</h3></header>
			<div class="module_content">
				<article class="stats_graph">
					<p><h2>Welcome to AFO Body Repair System.</h2></p>
					<br /><br /><br /><p>Regards,</p><p>Digital Information Solution™</p>
				</article>
				
				<article class="stats_overview">
				<h3 style="margin-left:10px;">Info</h3>
				<br /><br /><br /><br /><br /><br /><br />
				 	<div class="overview_today">
						<p class="overview_day">Today</p>
						<p class="overview_count">1,876</p>
						<p class="overview_type">Hits</p>
						<p class="overview_count">2,103</p>
						<p class="overview_type">Views</p>
					</div>
					<div class="overview_previous">
						<p class="overview_day">Yesterday</p>
						<p class="overview_count">1,646</p>
						<p class="overview_type">Hits</p>
						<p class="overview_count">2,054</p>
						<p class="overview_type">Views</p>
					</div>
				</article>
				<div class="clear"></div>
			</div>
		</article> --><!-- end of stats article -->
		<?php 
		if(isset($Items))
		{ 
		if($isAdd == 1)
		echo '<button class="btn btn-add" onclick="add()"><i class="glyphicon glyphicon-plus"></i> Add Invoice</button>';
		
		echo '<br />
		<div id="phpGrid">
			<table class="table table-striped table-bordered" id="table" cellspacing="0" width="98%">
                  <thead>
                    <tr>
                      <th>NO. INVOICE</th>
                      <th>NO. SPK</th>
                      <th>TANGGAL INVOICE</th>
                      <th>JENIS KENDARAAN</th>
                      <th>NO. POLISI</th>
                      <th>NAMA PEMILIK</th>
                      <th>NO. HANDPHONE</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
                  <tbody>';
                  
                 /*   foreach ($Items as $row) {
                            echo '<tr>';
                            echo '<td>'. $row->ItemName . '</td>';
                            echo '<td>'. $row->Description . '</td>';
                            echo '<td>'. $row->Price . '</td>';
							echo '<td><a href="javascript:void()" onclick="edit(' . "'" . $row->ItemName  . "'" . ')">edit</a></td>';
                            echo '</tr>';
                   } */
				   /* echo '<tr>';
                   echo '<td>ItemName</td>';
                   echo '<td>Description</td>';
                   echo '<td>Price</td>';
				   echo '<td>Action</td>';
                   echo '</tr>'; */
                   
                  
            echo    '</tbody>
            </table>
		</div>';
		} ?>
		
		
		
		<!-- Bootstrap modal -->
  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnclose2" onclick="closeDetail()"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Invoice Form</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="keyvalue" id="keyvalue" />
		  <input type="hidden" value="" name="tablename" id="tablename" />
		  <input type="hidden" value="" name="columnname" id="columnname" />
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">NO. INVOICE</label>
              <div class="col-md-9">
                <input name="invoiceId" class="form-control" type="text" id="invoiceId" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">No. SPK</label>
              <div class="col-md-9">
                <input name="SPKId" class="form-control" type="text" id="SPKId" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">TANGGAL INVOICE</label>
              <div class="col-md-9">
                <input name="tanggal_invoice" class="form-control" type="text" id="tanggal_invoice" readonly>
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">JENIS KENDARAAN</label>
              <div class="col-md-9">
                <input name="jenis_kendaraan" class="form-control" type="text" id ="jenis_kendaraan">
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">NO. POLISI</label>
              <div class="col-md-9">
                <input name="no_polisi_kendaraan" class="form-control" type="text" id="no_polisi_kendaraan">
                </select>
              </div>
			 </div>
			  <div class="form-group">
              <label class="control-label col-md-3">NAMA PEMILIK</label>
              <div class="col-md-9">
                <input name="Pemilik" class="form-control" type="text" id="Pemilik">
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">NO. HANDPHONE</label>
              <div class="col-md-9">
                <input name="no_hp" class="form-control" type="text" id="no_hp">
                </select>
              </div>
            </div>
          </div>
		  <div class="form-group">
			<button type="button" id="btndetail" class="btn btn-primary" style="float:right;margin-right:15px;style" onclick="showdetail()" disabled>detail</button>
		  </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnPrint" onclick="printInvoice()" class="btn btn-primary" disabled>Print Invoice</button>
            <button type="button" id="btnPrint2" onclick="printSPK('1')" class="btn btn-primary" disabled>Print SPK</button>
            <button type="button" id="btnPrint3" onclick="printSPK('2')" class="btn btn-primary" disabled>Print SPK 2</button>
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-danger" id="btnclose" style="display: none;" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
		
		
		
		  <div class="modal fade" id="modal_form2">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" aria-label="Close" onclick="btnclosemodal2()" id="btncloseDetail2" ><span aria-hidden="true">&times;</span></button>
        <h3 class="modal2-title">Detail</h3>
      </div>
      <div class="modal-body form">
	  
	 <table class="table table-striped table-bordered" id="table2" cellspacing="0" width="98%">
                  <thead>
                    <tr>
                      <th>InvoiceId</th>
                      <th>DetailId</th>
                      <th>BAGIAN KENDARAAN</th>
                      <th>JENIS JASA</th>
                      <th>HARGA</th>
                      <th>HARGA SPK</th>
                      <th>HARGA SPK 2</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
				  <tbody>
				  </tbody>
	</table>
		 <div class="form-group">
			<button type="button" id="btnAdddetail" class="btn btn-primary" style="float:right;margin-right:15px;style" onclick="addDetail()">add detail</button>
		  </div>
		  <form action="#" id="formDetail" class="form-horizontal" style="display:none;">
		  <input type="hidden" value="" name="keyvalue2" id="keyvalue2" />
		  <input type="hidden" value="" name="invoiceId" id="invoiceId" />
		  <input type="hidden" value="" name="detailId" id="detailId" />
		  <br /><br /><br />
			<div class="form-group">
              <label class="control-label col-md-3">BAGIAN KENDARAAN</label>
              <div class="col-md-9">
			  <select name="bagian_kendaraan" id="bagian_kendaraan" class="form-control" onchange="fillPrice()">
			  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">JENIS JASA</label>
              <div class="col-md-9">
                <input name="jenis_jasa" class="form-control" type="text" id="jenis_jasa">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">HARGA</label>
              <div class="col-md-9">
                <input name="Price" class="form-control" type="text" id="Price">
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">HARGA SPK</label>
              <div class="col-md-9">
                <input name="Price2" class="form-control" type="text" id="Price2">
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">HARGA SPK2</label>
              <div class="col-md-9">
                <input name="Price3" class="form-control" type="text" id="Price3">
              </div>
            </div>
			<div style="float:right;">
			<button type="button" class="btn btn-primary" id="btncloseDetail" onclick="saveDetail()" >Simpan</button>
			<button type="button" class="btn btn-danger" id="btncloseDetail" onclick="removeDetail()">Cancel</button>
			</div>
		  </form>
		  <br /><br />
	  </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-danger" id="btncloseDetail" data-dismiss="modal" onclick="btnclosemodal2()">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
		
		
		<div class="spacer"></div>
	</section>