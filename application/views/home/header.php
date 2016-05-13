<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Warehouse Retail System - Dashboard</title>
	
	
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/table.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/loginpop.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/dataTables.bootstrap.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<? echo site_url();?>/resources/css/dataTables.fixedColumns.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="<? echo site_url();?>/resources/js/jquery-2.1.4.min.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	var $j = jQuery.noConflict(true);
	</script>
	<script src="<? echo site_url();?>/resources/js/hideshow.js" type="text/javascript"></script>
	<!--<script src="<? echo site_url();?>/resources/js/jquery.tablesorter.min.js" type="text/javascript"></script>-->
	<script type="text/javascript" src="<? echo site_url();?>/resources/js/jquery.equalHeight.js"></script>
	<script src="<? echo site_url();?>/resources/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/dataTables.bootstrap.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/jquery.maskMoney.min.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/dataTables.fixedColumns.min.js" type="text/javascript"></script>
	<script src="<? echo site_url();?>/resources/js/jquery.tabletojson.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	/* $j(document).ready(function() 
    	{ 
      	  $j(".tablesorter").tablesorter(); 
   	 } 
	); */
	$j(document).ready(function() {

	//When page loads...
	$j(".tab_content").hide(); //Hide all content
	$j("ul.tabs li:first").addClass("active").show(); //Activate first tab
	//$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$j("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

<script type="text/javascript">
 var save_method;
 var table;
 var valuekey;
$(document).ready(function(){
	
   $("#show_login").click(function(){
    showpopup();
   });
   
   $("#show_login2").click(function(){
    showpopup();
   });
   
   $("#close_login").click(function(){
    hidepopup();
   });
	 
	$('body').click(function (e) {
        if ($(e.target).is('#overlay') ) {
            hidepopup();
        }
    })
	
	$j('.toggleLink2').toggle(
	function() {
		//$('#sidebar').animate();
		$('#sidebar').animate({width: 35});
		$('#sidebar2').animate({width: 35});
		$('#sidebar3').animate({width: 35});
		$('#sidebar li a').addClass("nonetext");
		$('#sidebar2_title').html("");
		$('#sidebar3').addClass("nonetext");
		$('#sidebar p').addClass("nonetext");
		
		$('#sidebar h3').css({ 'font-size' : '1px', 'color' : '#fff' });
	},
	function() {
		percent = 22;
		add_width = ((percent/100)*$('body').parent().width());
		$('#sidebar').animate({width : add_width});
		$('#sidebar2').animate({width : add_width});
		$('#sidebar3').animate({width : add_width});
		$('#sidebar li a').removeClass("nonetext");
		$('#sidebar2_title').html("");
		$('#sidebar3').removeClass("nonetext");
		$('#sidebar p').removeClass("nonetext");
		$('#sidebar h3').removeClass("nonetext");
		$('#sidebar h3').css({ 'font-size' : '13px', 'color' : '#1F1F20' });
	}
	)
	
	$( window ).resize(function() {
		percent = 23;
		add_width = ((percent/100)*$('body').parent().width());
		$('#sidebar').animate({width : add_width});
		$('#sidebar2').animate({width : add_width});
		$('#sidebar3').animate({width : add_width});
		$('#sidebar li a').removeClass("nonetext");
		$('#sidebar2').removeClass("nonetext");
		$('#sidebar3').removeClass("nonetext");
		$('#sidebar p').removeClass("nonetext");
		$('#sidebar h3').removeClass("nonetext");
		$('#sidebar h3').css({ 'font-size' : '13px', 'color' : '#1F1F20' });
	});
	
	
		table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('Items/select')?>",
            "type": "POST",
			<?php 
			$cond = "";
			if(isset($condition))
			{
				$cond = $condition;
			}
			
			if(isset($tablename)){
			echo '
			"data": {
			"tablename" : "' . $tablename . '",
			"fields" : "' . $fields . '",
			"keyfields" : "' . $keyfields . '",
			"menuid" : "' . $menuid . '",
			"condition" : "' . $cond . '"
			},';
			}
			?>			
			
            
        },

        //Set column definition initialisation properties.

		fixedHeader: true,
		scrollY:        340,
		scrollCollapse: true,
		fixedColumns: true,
		scrollX:        true,
		"columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
		  <?php 
		  if(isset($tablename))
		  {
			  if($tablename == "reff_itemss"){
				echo '"visible" : false,';
			  }
		  }?>
        }
		
        ]
		
      });
	
	});
	
	function displayOverlay() {
		$("<div id='overlay'></div>").css({
			"position": "fixed",
			"top": 0,
			"left": 0,
			"width": "100%",
			"height": "100%",
			"background-color": "rgba(0,0,0,.5)",
			"z-index": 10000,
			"vertical-align": "middle",
			"text-align": "center",
			"color": "#fff",
			"font-size": "30px",
			"font-weight": "bold"
		}).appendTo("body");
	}

	function removeOverlay() {
		$("#overlay").remove();
	}

	function showpopup()
	{
		displayOverlay();
	   $("#loginform").fadeIn();
	   $("#loginform").css({"visibility":"visible","display":"block"});
	}

	function hidepopup()
	{
		removeOverlay();
	   $("#loginform").fadeOut();
	   $("#loginform").css({"visibility":"hidden","display":"none"});
	}
	
	function reload_table()
    {
      table.ajax.reload(null,false); //reload datatable ajax 
    }
	

	function add()
    {
      save_method = 'add';
      $('#form')[0].reset(); // reset form on modals
	  $('[name="tablename"]').val("<?php if(isset($tablename)){echo $tablename;} ?>");
	  $('[name="columnname"]').val("<?php  if(isset($fields)){echo $fields;} ?>");
      $('#modal_form').modal('show'); // show bootstrap modal
	  // fill data for special case invoice
	  try{
	  fillInvoiceData();
	  }
	  catch (err){}
	  // end of fill data invoice
	  <?php
	  if(isset($fields))
	  {
		  $col = explode(",",$fields);
		  for($i=0;$i < count($col);$i++)
		  {
			  echo '
						$.ajax({
						url : "' . site_url('Items/fillddl') . '",
						type: "POST",
						"data": {
						"tablename" : "' . $tablename . '",
						"reff_column" : "' . $col[$i] . '"
						},
						dataType: "JSON",
						success: function(data)
						{
							if(data.success)
							{				
							$("' . '#' . $col[$i] . '").html(data.options);
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});';
		  }
	  }
	  ?>
	  
	  $('#modal_form input').prop('readonly', false);
	  
	  // special case for Invoice
	  try{
	  addInvoice();
	  }
	  catch (err){}
	  // end of special case for Invoice
	  
      $('#modal_form select').prop('disabled', false);
      $('#modal_form :checkbox').prop('disabled', false);
	  $('#modal_form button').show();
      $('#btnclose').hide();
      $('#btnclose2').show();
	  $('#btnPrint').show();
	  $('#btnPrint2').show();
	  $('#btnPrint3').show();
	  $('#btndetail').show();
	  $('#btnAdddetail').show();
      $('.modal-title').text('Add <?php if(isset($title)) { echo $title; } ?>'); // Set Title to Bootstrap modal title
    }
	
	function save()
    {
	// special case for Invoice
	  try
	  {
	  UpdateStokBarang();
	  }
	  catch(err){}

	  // end of special case for Invoice
      var url;
      if(save_method == 'add') 
      {
          url = "<?php echo site_url('Items/insert')?>";
      }
      else
      {
        url = "<?php echo site_url('Items/update')?>";
      }

       // ajax adding data to database
          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               //if success close modal and reload ajax table
			    alert('Data berhasil disimpan');
			   <?php 
			   if(isset($tablename))
			   {
				   if(($tablename) == "trans_invoice")
				   {
					   echo "$('#btndetail').prop(\"disabled\", false);";
					   echo "$('#btnPrint').prop(\"disabled\", false);";
					   echo "$('#btnPrint2').prop(\"disabled\", false);";
					   echo "$('#btnPrint3').prop(\"disabled\", false);";
					   echo "save_method = 'edit';$('#keyvalue').val($('#invoiceId').val() + ',' + $('#SPKId').val() );";
				   }
				   else 
				   {
					   echo "$('#modal_form').modal('hide');";
				   }
			   }
			   ?>
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
	}
	
	
	function view(tablename,keyfields,keyvalue)
    {
      save_method = 'view';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('Items/edit')?>",
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
			if(isset($fields)){
			$column = explode(",",$fields);
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
					if(strpos($column[$i],"price") == true)
							echo " $('[name=" . '"' . $column[$i] . '"' . "]').val(data." . $column[$i] . ");"; 
					else
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
			}?>
            $('[name="keyvalue"]').val(keyvalue);
			$('[name="tablename"]').val(tablename);
			$('[name="columnname"]').val('<?php if(isset($fields)){ echo $fields; } ?>');
            
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('#modal_form input').prop('readonly', true);
            $('#modal_form select').prop('disabled', true);
            $('#modal_form :checkbox').prop('disabled', true);
            $('#modal_form button').hide();
            $('#btnAdddetail').hide();
            $('#btnclose').show();
            $('#btnclose2').show();
			$('#btnPrint').show();
			$('#btnPrint2').show();
			$('#btnPrint3').show();
			$('#btndetail').show();
            $('.modal-title').text('Edit <?php if(isset($title)){ echo $title; } ?>'); // Set title to Bootstrap modal title
            <?php 
			if(isset($tablename))
			{
				if(($tablename) == "trans_invoice")
			   {
				   echo "$('#btndetail').prop(\"disabled\", false);";
				   echo "$('#btnPrint').prop(\"disabled\", false);";
				   echo "$('#btnPrint2').prop(\"disabled\", false);";
				   echo "$('#btnPrint3').prop(\"disabled\", false);";
			   }
			}
			?>
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
        }
    });
    }
	
	
	function edit(tablename,keyfields,keyvalue)
    {
      save_method = 'update';
      $('#form')[0].reset(); // reset form on modals

      //Ajax Load data from ajax
      $.ajax({
        url : "<?php echo site_url('Items/edit')?>",
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
			if(isset($fields)){
			$column = explode(",",$fields);
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
					if(strpos($column[$i],"price") == true)
							echo " $('[name=" . '"' . $column[$i] . '"' . "]').val(data." . $column[$i] . ");"; 
					else
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
			}?>
            $('[name="keyvalue"]').val(keyvalue);
			$('[name="tablename"]').val(tablename);
			$('[name="columnname"]').val('<?php if(isset($fields)){ echo $fields; } ?>');
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
			$('#modal_form input').prop('readonly', false);
            $('#modal_form select').prop('disabled', false);
            $('#modal_form :checkbox').prop('disabled', false);
			$('#modal_form button').show();
			$('#btnclose').hide();
			$('#btnAdddetail').show();
			$('#btnclose2').show();
			$('#btnPrint').show();
			$('#btnPrint2').show();
			$('#btnPrint3').show();
			$('#btndetail').show();
            $('.modal-title').text('Edit <?php if(isset($title)){ echo $title; } ?>'); // Set title to Bootstrap modal title
            				   // special case for Invoice
			  try{
			  addInvoice();
			  }
			  catch (err){}
			  // end of special case for Invoice

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
        }
    });
    }
	
	function delete_it(tablename,keyfields,keyvalue)
    {
      if(confirm('Are you sure delete this data?'))
      {
		  save_method = 'delete';
		  try
		  {
			valuekey = keyvalue;
		  UpdateStokBarang();
		  }
		  catch(err){}
        // ajax delete data to database
          $.ajax({
            url : "<?php echo site_url('Items/delete')?>",
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
			   alert('Data berhasil dihapus');
               $('#modal_form').modal('hide');
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Terjadi kesalahan pada sistem. Mohon menghubungi adminsitrator');
            }
        });
         
      }
    }
	
</script>


	
</script>


</head>

<div id = "loginform">
			<div class="module2"><header><h3 >&nbsp;&nbsp;&nbsp;Login Form</h3></header><input type = "image" id = "close_login" src = "<? echo site_url();?>/resources/images/icn_logout.png"></div>
				<?php echo validation_errors(); ?>
			<?php echo form_open('VerifyLogin'); ?>
                <input type = "text" id = "login" placeholder = "Userid" name = "userid"><br />
                <input type = "password" id = "password" name = "password" placeholder = "***"><br />
                <input type = "submit" id = "dologin" value = "Login">
            </form>

        </div>
<body>
	<header id="header">
		<hgroup>
			<h1 class="site_title" id="sidebar2"><a href="<?php echo site_url() ?>" id="sidebar2_title"></a><button class="toggleLink2" >=</button></h1>
			<h2 class="section_title">Warehouse Retail System</h2>	
		</hgroup>
	</header> <!-- end of header bar -->
	
	<section id="secondary_bar">
		<div class="user" id="sidebar3">
			<p>Welcome 
			
			<?php 
			if(isset($full_name))
			{
			echo ' '. $full_name;
			}
			else
			{
			echo 'Guest, Please Login' ;
			}
			?>
			
			</p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcon"><a href="<?php echo site_url() ?>">Home</a> > <a class="current"></a></article>
			</div><div class="btn_view_site">
			<?php 
			if(!(isset($logged_in))) {
			echo '<a id="show_login" style="cursor:pointer;float:right;">Login</a>';
			} 
			else { 
			echo '<a href="' . site_url('Home/logout') . '" id="show_logout" style="cursor:pointer;float:right;">Logout</a>';
			 } ?>
			</div>
		</div>
	</section><!-- end of secondary bar -->