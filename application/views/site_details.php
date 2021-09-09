<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Router Sites</title>
    <!-- Bootstrap Core CSS -->
    <link href="<?PHP echo base_url();?>assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    #myDataTables > tbody,thead > tr > td, th{
        text-align: center;
    }
    .red-astrick{
        color: red;
    }
    .container{
        padding: 30px;
    }
    .btn-style{
        padding: 10px;
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="btn-style">
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Add New Route</button>
        </div>
        <?php if($this->session->flashdata('success_message') != ""){?><div style="text-align: center; color:green; font-size: 15px;"><?php echo $this->session->flashdata('success_message'); ?></div><?php } ?>
        <?php if($this->session->flashdata('error_message') != ""){?><div style="text-align: center; color:red; font-size: 15px;"><?php echo $this->session->flashdata('error_message'); ?></div><?php } ?>
        <table id="myDataTables" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Sap Id</th>
                    <th>hostname </th>
                    <th>LoopBack</th>
                    <th>mac_address </th>
                    <th>createdon</th>
                    <th>isDeleted</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Create New Route Details</h4>
          <h5 class="success" style="color:green"></h5>
        </div>
        <div class="modal-body">
            <form method="post" id="addRouteForm" class="MyForm" action="">
              <div class="form-group">
                <label for="sap-ip" class="col-form-label">Sap ID<strong class="red-astrick">*</strong>:</label>
                <input type="text" class="form-control" name="sap_id" id="sap-ip">
              </div>
              <div class="form-group">
                <label for="hostname" class="col-form-label">Hostname<strong class="red-astrick">*</strong>:</label>
                <input type="text" class="form-control" name="hostname" id="hostname">
              </div>
              <div class="form-group">
                <label for="loopback" class="col-form-label">Loopback<strong class="red-astrick">*</strong>:</label>
                <input type="text" class="form-control" name="loopback" id="loopback">
              </div>
              <div class="form-group">
                <label for="mac_address" class="col-form-label">Mac Address<strong class="red-astrick">*</strong>:</label>
                <input type="text" class="form-control" name="mac_address" id="mac_address">
                <input type="hidden" name="rec_id" id="rec_id">
                
              </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary saveBtn add-new-entry">Save</button>
        </div>
      </div>
      
    </div>
  </div>
  
    <!-- jQuery -->
    <script src="<?PHP echo base_url();?>assets/js/jquery-3.5.1.js"></script>

    <!-- DataTables JavaScript -->
    <script src="<?PHP echo base_url();?>assets/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        var table;
    
        $(document).ready(function() {

        //datatables
        table = $('#myDataTables').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength" : 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "ajax":{  
                url:"<?PHP echo base_url(); ?>assignment/dataparser",  
                type:"POST" ,
                data : function ( data ) {

                    // data.lead_status = $('#lead_status').val();
                    // data.user = $('#user').val();
                    // data.fromdate = $('#fromdate').val();
                    // data.todate = $('#todate').val();
                } /*,
                success:function(ret_data){ 
                    console.log(ret_data);
                }*/
           },  
           // "columnDefs":[  
           //      {  
           //           "targets":[0, 3, 4],  
           //           "orderable":false,  
           //      },  
           // ], 
        });
    })

    //add new entry
    $(".add-new-entry").click(function(e) {
        $.ajax({
           type: "POST",
           url: '<?PHP echo base_url(); ?>assignment/add',
           data: $("#addRouteForm").serialize(),
           success: function(data)
           {
            
            if(data.status!=false) {
                $('.success').text(data.success);
                setTimeout(function(){
                    window.location.reload();
                }, 1000);
            } else {
                  $('.error').html('');  
                  $.each(data.errors, function(key, val) {
                  $('[name="'+ key +'"]', $("#addRouteForm")).after(val);
                })
            }
           
           }
         });
      e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //edit record
    $(document).on( 'click', '.edit-entry', function(e){
        $.ajax({
           type: "POST",
           url: '<?PHP echo base_url(); ?>assignment/edit',
           data: $("#editRouteForm").serialize(),
           success: function(data)
           {
            
            if(data.status!=false) {
                $('.success').text(data.success);
                setTimeout(function(){
                    window.location.reload();
                }, 1000);
            } else {
                  $('.error').html('');  
                  $.each(data.errors, function(key, val) {
                  $('[name="'+ key +'"]', $("#editRouteForm")).after(val);
                })
            }
           
           }
         });
      e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $(document).on( 'click', 'a.editingTRbutton', function(ele){
        var tr = ele.target.parentNode.parentNode;
        var id = tr.cells[0].textContent;
        var sapId = tr.cells[1].textContent;
        var hostname = tr.cells[2].textContent;
        var loopback = tr.cells[3].textContent;
        var macaddress = tr.cells[4].textContent;

        $('#rec_id').val(id);
        $('#sap-ip').val(sapId);
        $('#hostname').val(hostname);
        $('#loopback').val(loopback);
        $('#mac_address').val(macaddress);
        $('.modal-title').text('Edit Route Details');
        $('.saveBtn').removeClass('add-new-entry').addClass('edit-entry');
        $('.MyForm').attr('id','editRouteForm');
        // $("form#ModalForm").attr('action', window.location.href+'/update/'+id);
        // $("a#saveModalButton").attr('href', window.location.href+'/update/'+id);
    });

    $("#myModal").on("hidden.bs.modal", function () {
        $('#rec_id').val("");
        $('#sap-ip').val("");
        $('#hostname').val("");
        $('#loopback').val("");
        $('#mac_address').val("");
        $('.modal-title').text('Create New Route Details'); 
        $('.saveBtn').removeClass('edit-entry').addClass('add-new-entry');
        $('.MyForm').attr('id','addRouteForm');
    });
</script>
</body>
</html>   