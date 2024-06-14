@extends('layouts.adminlayout')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Campaign</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Campaign</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    @if(session()->has('success'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
          {{ session()->get('success') }}
      </div>
    @endif
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <!-- /.card-header -->
            </div>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Campaign Detail</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              @if(empty($showDetails))
                <div class="text-center red-color blink_me">
                  <h4 class="mb-3"> *Campaign has scheduled, please wait...</h4>
                </div>
              @else
                <div class="text-center red-color blink_me">
                  <h4 class="mb-3"> *The messages are being sent and if you want to check the progress, Please refresh the page...</h4>
                </div>
              @endif
                <table id="Subscriber" cellspacing="0"  class="table display table-bordered data-table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>campaign Name</th>
                      <th>Message Name</th>
                      <th>Number</th>
                      <th>Mask</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
      
    </section>
    <!-- /.content -->
  </div>
  
  
@endsection


@if(!empty($showDetails) && isset($showDetails))
@section('footer-scripts')
<script>
  
</script>
<script>
        // Start Subcriber table
        $(function () {

    $("#Subscriber").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false,
        "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
         "lengthMenu": [[10, 20, 30, 500, 1000, 100000, 100000000], [10, 20, 30, 500, 1000, 100000, "All"]],
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
          
          processing: true,
          serverSide: true,
          ajax: `{{ route('get-campaign-detail',  $showDetails) }}`,
          columns: [
              {data: 'id', name: 'id'},
              {data: 'campaign_title', name: 'campaign_title'},
              {data: 'message_title', name: 'message_title'},
              {data: 'number', name: 'number'},
              {data: 'mask', name: 'mask'},
              {data: 'status', name: 'status'},
          ],
          columnDefs: [
            {
                render: function ( data, type, row,meta ) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                searchable:false,
                orderable:true,
                targets: 0
            }
          ],
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  
  $('#Subscriber').on( 'click', 'button#subscriberedit', function () {
      var e = $("#subscriberEditmodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#Subscriber').DataTable().row($tr).data();

        $("#subscriberUpdateForm").append('<input type="hidden" class="form-control" id="subscriber-id" name="subscriber-id">');
        $(e).find('input[name="number"]').val(rowData.number);
        $(e).find('input[name="subscriber-id"]').val(rowData.id);

        
        var selectedGroups = [];
        selectedGroups.push(rowData.list_id);

        $('#subscriber-edit-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-subscriber",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#subscriber-edit-selection').append('<option value="' + d.id + '">' + d.title + '</option>');
                });

                $(e).find('#subscriber-edit-selection').val(selectedGroups).trigger('change');
                $('#subscriber-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });

    } );

    $('#subscriberEditmodal').on('hidden.bs.modal', function () {
      $("#subscriberUpdateForm").find('#subscriber-id').remove();
    });

    $('#Subscriber').on( 'click', 'button#subscriberdelete', function () {
      var e = $("#subscriberDeletemodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#Subscriber').DataTable().row($tr).data();

        var url= base_url+'/subscriber-delete/'+rowData.id;
        $('#subscriberDeleteForm').attr('action', url);

        $(e).find('input[name="number"]').val(rowData.number);
        $(e).find('input[name="subscriber-id"]').val(rowData.id);

        var selectedGroups = [];
        selectedGroups.push(rowData.list_id);

        $('#subscriber-delete-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-subscriber",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#subscriber-delete-selection').append('<option value="' + d.id + '">' + d.title + '</option>');
                });

                $(e).find('#subscriber-delete-selection').val(selectedGroups).trigger('change');
                $('#subscriber-delete-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
    } );
    // End Subcriber table
</script>
@endsection
@endif