@extends('layouts.adminlayout')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Instant Message</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Instant Message</li>
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
                <h3 class="card-title">Instant Message Detail</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="text-center red-color blink_me">
                <h4 class="mb-3"> *The messages are being sent and if you want to check the progress, Please refresh the page...</h4>
                </div>
                <table id="Subscriber" cellspacing="0"  class="table display table-bordered data-table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Message</th>
                      <th>Mask</th>
                      <th>Number</th>
                      <th>Created By</th>
                      <th>Created At</th>
                      <th>Status</th>
                      <th>Recheck Status</th>
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
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            'colvis'
        ],
          
          processing: true,
          serverSide: true,
          ajax: `{{ route('get-instant-detail',  $showDetails['u_id']) }}`,
          columns: [
            {data: 'id', name: 'id'},
              {data: 'title', name: 'title'},
              {data: 'message', name: 'message'},
              {data: 'mask', name: 'mask'},
              {data: 'number', name: 'number'},
              {data: 'created_by', name: 'created_by'},
              {data: 'created_at', name: 'created_at'},
              {data: 'status', name: 'status'},
              {data: 'msg_id', name: 'msg_id'},
          ],
          columnDefs: [
            {
                render: function ( data, type, row,meta ) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                searchable:false,
                orderable:true,
                targets: 0
            },
            {
              // puts a button in the last column
              targets: [-1], "orderable": false, render: function (a, b, data, d) {
                var show ='{{ route("instant-check-status", ":msg_id") }}';
                show = show.replace(':msg_id', data.msg_id);
                return '<a href="'+show+'" class="btn btn-sm btn-warning mx-2">Recheck Status</a>';
              }
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