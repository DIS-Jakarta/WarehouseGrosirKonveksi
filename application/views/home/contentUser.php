<script type="text/javascript">
	$('.current').html("User")
	
	</script>
	<section id="main" class="column">
		<?php if(!(isset($Items))){
		echo '<h4 class="alert_info">Welcome to the AFO Body Repair System.</h4>';
		}?>
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
		//log_message('info', ' isAdd ' . $isAdd);
		if($isAdd == "1")
		{
		echo '<button class="btn btn-add" onclick="add()"><i class="glyphicon glyphicon-plus"></i> Add User</button>';
		}
		echo '
		<br />
		<div id="phpGrid">
			<table class="table table-striped table-bordered" id="table" cellspacing="0" width="98%">
                  <thead>
                    <tr>
                      <th>USERID</th>
                      <th>PASSWORD</th>
                      <th>GROUP USER</th>
                      <th>NAMA LENGKAP</th>
                      <th>ALAMAT</th>
                      <th>NO. TELEPON</th>
                      <th>ALAMAT EMAIL</th>
					  <th>ACTIVE</th>
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
		  <input type="hidden" value="reff_users" name="tablename"/>
		  <input type="hidden" value="" name="columnname"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">USERID</label>
              <div class="col-md-9">
                <input name="userid" placeholder="Userid" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">PASSWORD</label>
              <div class="col-md-9">
                <input name="password" placeholder="Password" class="form-control" type="password">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">GROUP USER</label>
              <div class="col-md-9" >
				<select name="groupid" id="groupid" class="form-control">
				</select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">NAMA LENGKAP</label>
              <div class="col-md-9">
                <input name="full_name" placeholder="Nama Lengkap" class="form-control" type="text">
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">ALAMAT</label>
              <div class="col-md-9">
                <input name="address" placeholder="Alamat" class="form-control" type="text">
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">NO. TELEPON</label>
              <div class="col-md-9">
                <input name="phone_number" placeholder="NO. TELEPON" class="form-control" type="text">
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">ALAMAT EMAIL</label>
              <div class="col-md-9">
                <input name="email_address" placeholder="ALAMAT EMAIL" class="form-control" type="text">
                </select>
              </div>
            </div>
			<div class="form-group">
              <label class="control-label col-md-3">ACTIVE</label>
              <div class="col-md-9">
                <input type="checkbox" name="active" id="active">
                </select>
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