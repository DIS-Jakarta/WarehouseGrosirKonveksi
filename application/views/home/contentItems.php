	<script src="<? echo site_url();?>/resources/js/jquery.scannerdetection.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#Price').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price2').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		$('#Price3').maskMoney({prefix:'', thousands:'.', decimal:',', precision:0});
		
		
			$(document).scannerDetection({
			avgTimeByChar: 400,
			onComplete: function(barcode, qty){ $('#ItemBarcode').val(barcode) },
			onError: function(string){ }
			});

	});
	
	$('.current').html("Master Barang")
	
	function makereadonly()
	{
		$('#ItemBarcode').prop('readonly', true);
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
		echo '<button class="btn btn-add" onclick="add();makereadonly();"><i class="glyphicon glyphicon-plus"></i> Tambah barang</button>';
		
		echo '<br />
		<div id="phpGrid">
			<table class="table table-striped table-bordered" id="table" cellspacing="0" width="98%">
                  <thead>
                    <tr>
                      <th>NAMA ITEM</th>
                      <th>DESKRIPSI</th>
                      <th>KODE BARANG</th>
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
		  <input type="hidden" value="" id="barcodeinput" />
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Nama Item</label>
              <div class="col-md-9">
                <input name="ItemName" placeholder="Nama Item" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Deskripsi</label>
              <div class="col-md-9">
                <input name="description" placeholder="Last Name" class="form-control" type="text">
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">Item Barcode</label>
              <div class="col-md-9">
                <input name="ItemBarcode" placeholder="Item Barcode" class="form-control" type="text" id="ItemBarcode">
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
		  <img src="<?php echo site_url()?>/resources/images/spinner.gif" style="max-height:80px;display:none;margin-right:-20px;" id="loadinganimated" />
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