	<script type="text/javascript">
	$(document).ready(function(){
		$('#Price').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price2').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price3').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
	});
	
	$('.current').html("Stok Barang")
	
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
	  var twoDigitMonth = month+"";if(twoDigitMonth.length==1)	twoDigitMonth="0" +twoDigitMonth;
	  var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1)	twoDigitDate="0" +twoDigitDate;
	  var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + twoDigitDate;		
	  var elem = document.getElementById("Tgl_Barang_Masuk");			
	  elem.value = currentDate;
	}
	
	function UpdateStokBarang()
    {
		var jenis = "";
		var jumlahstok = "";
		var idtransstok= "";
		var itemname = "";
			if(save_method != "delete")
			{
				itemname = $('#ItemName').val();
				jumlahstok = $('#Quantity').val();
				if($('#Jenis').val() == "barang masuk")
				{
					jenis = " + ";
				}
				else
				{
					jenis = " - ";
				}
				
				
				try
				{
					idtransstok = $('#Id').val();
				}
				catch(err){}
			}
			else
			{
				itemname = "{__ITEMNAME__}";
				jenis = "{__JENIS__}";
				jumlahstok = "{__JUMLAHSTOK__}";
				idtransstok = valuekey;
			}
		var stokbarang = "";
		$.ajax({
            url : "<?php echo site_url('Items/selectwquery')?>",
            type: "POST",
            data: { "query" : "select ( IFNULL(Quantity,0) " +
		" " + jenis + jumlahstok + ") as 'datas' " +
		"from reff_items where ItemName='" + itemname  + "'","keys" : $('#ItemName').val(), "method" : save_method, "id" : idtransstok, "Quantity" : jumlahstok}, 
            dataType: "JSON",
            success: function(data)
            {
               if(!(data.success))
				{	
					alert('Gagal update stok barang. Mohon menghubungi adminsitrator');
				}
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Gagal update stok barang. Mohon menghubungi adminsitrator');
            }
        });
	}
	
	
	function addInvoice()
	{
	  $('#Id').prop('readonly', true);
	  $('#Tgl_Barang_Masuk').prop('readonly', true);
	  $('#Jenis').prop('readonly', true);
	}
	
	function barangmasuk()
	{
		$('#tanggalbarang').html('TANGGAL BARANG MASUK');
		$('#Jenis').val("barang masuk");
		 $('#Jenis').prop('readonly', true);
	}
	
		function barangkeluar()
	{
		$('#tanggalbarang').html('TANGGAL BARANG KELUAR');
		$('#Jenis').val("barang keluar");
		 $('#Jenis').prop('readonly', true);
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
		echo '<button class="btn btn-add" onclick="add();barangmasuk();" style="float:left;margin-right:10px;margin-bottom:5px;"><i class="glyphicon glyphicon-plus"></i> Barang masuk</button>';
		echo '<button class="btn btn-add" onclick="add();barangkeluar();"><i class="glyphicon glyphicon-plus"></i> Barang keluar</button>';
		
		echo '<br />
		<div id="phpGrid">
			<table class="table table-striped table-bordered" id="table" cellspacing="0" width="98%">
                  <thead>
                    <tr>
					<th>ID</th>
                      <th>TANGGAL MASUK / KELUAR</th>
                      <th>JENIS</th>
                      <th>NAMA BARANG</th>
                      <th>QUANTITY</th>
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnclose2"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Item Form</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="keyvalue"/>
		  <input type="hidden" value="" name="tablename"/>
		  <input type="hidden" value="" name="columnname"/>
          <div class="form-body">
            <div class="form-group">
              <!--<label class="control-label col-md-3">ID STOCK BARANG</label>-->
              <div class="col-md-9">
                <input name="Id" placeholder="" class="form-control" type="hidden" id="Id" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3" id="tanggalbarang" >TANGGAL BARANG MASUK</label>
              <div class="col-md-9">
                <input name="Tgl_Barang_Masuk" placeholder="" class="form-control" type="datetime" id="Tgl_Barang_Masuk" readonly>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">JENIS</label>
              <div class="col-md-9">
                <input name="Jenis" placeholder="" class="form-control" type="text" id="Jenis" value="abc" readonly>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">NAMA BARANG</label>
              <div class="col-md-9">
                <select name="ItemName" id="ItemName" class="form-control"></select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">QUANTITY</label>
              <div class="col-md-9">
                <input name="Quantity" placeholder="Quantity" class="form-control" type="text" id="Quantity">
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<button type="button" class="btn btn-danger" id="btnclose" style="display: none;" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  <!-- End Bootstrap modal -->
		
		<div class="spacer"></div>
	</section>