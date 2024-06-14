@extends('layouts.adminlayout')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscriber</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Subscriber</li>
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
              {{-- Edit Subscriber : Start --}}
                    <!-- Modal -->
                <div class="modal fade" id="subscriberEditmodal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="form-header text-uppercase">
                          <i aria-hidden="true" class="fa fa-list"></i>
                            Edit Subscriber
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="card" style="box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);">
                            <div class="card-body">
                              <form method="post" action="{{route('update-subscriber')}}" method="post" id="subscriberUpdateForm">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Subscriber List</label>
                                  <div class="col-sm-10">
                                    <select class="subscriber-edit-selection form-control" id="subscriber-edit-selection" name="subscriber_list_id">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                <label for="number" class="col-sm-2 col-form-label">Number</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="number" name="number" required>
                                </div>
                                </div>

                                </div>
                                <div class="form-footer float-right">
                                    <button type="submit" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> CANCEL</button>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div><!--End Row-->
                    </div>
                  </div>
                </div>

              {{-- Edit Subscriber : END --}}

              {{-- Delete Subscriber : Start --}}
              <!-- Modal -->
                <div class="modal fade" id="subscriberDeletemodal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="form-header text-uppercase">
                                    <i aria-hidden="true" class="fa fa-list"></i>
                                        Delete Subscriber
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card" style="box-shadow: 0 0px 0px rgba(0, 0, 0, 0.1);">
                                            <div class="card-body">
                                            <form method="post" id="subscriberDeleteForm">
                                                @method('DELETE')
                                                @csrf
                                                <div class="form-group row">
                                                  <label for="roles" class="col-sm-2 col-form-label">Subscriber List</label>
                                                  <div class="col-sm-10">
                                                    <select class="subscriber-delete-selection form-control" id="subscriber-delete-selection" name="subscriber_list_id" disabled>
                                                        <option value=""></option>
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="form-group row">
                                                <label for="number" class="col-sm-2 col-form-label">Number</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control" id="number" name="number" disabled>
                                                </div>
                                                </div>
                                                </div>
                                                <div class="form-footer float-right">
                                                    <button type="submit" class="btn btn-success" data-dismiss="modal"><i class="fa fa-times"></i> CANCEL</button>
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-check-square-o"></i> Delete</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--End Row-->
                            </div>
                        </div>
                    </div>
                </div>
              {{-- Delete Subscriber : End --}}
              <div class="card-header">
                <h3 class="card-title">All Subscriber</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="Subscriber" cellspacing="0"  class="table display table-bordered data-table table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Subscriber List Name</th>
                    <th>Creator Name</th>
                    <th>Creator Number</th>
                    <th>Created At</th>
                    <th>Action</th>
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
// Start Subscriber Validation

    
$('#SubscriberForm').validate({
        rules: {
        title: {
            required: true
        },
        text: {
            required: true
        },
        subscriber_list_id: {
            required: true
        },
        number: {
            required: true,
            maxlength: 10
        },
        },
        messages: {
        title: {
            required: "Please enter a title",
        },
        text: {
            required: "Please enter a text",
        },
        subscriber_list_id: {
            required: "Please enter a Subscriber List Id",
        },
        description: "Please enter a full description"
        },
        number: {
            required: "Please enter a number",
            minlength: "Your number must be at least 10 characters"
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
        }
    });
    // End Subscriber Validation
    var base_url= {!! json_encode(url('/')) !!}
    var val = {!! json_encode( $showContactDetails['subscriber_list_id']) !!}
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
                  columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            'colvis'
        ],
          
          processing: true,
          serverSide: true,
          ajax: `{{ route('get-subscriber',  json_encode( $showContactDetails['subscriber_list_id'])) }}`,
          columns: [
              {data: 'id', name: 'id'},
              {data: 'subscriber_list_id', name: 'subscriber_list_id'},
              {data: 'name', name: 'name'},
              {data: 'number', name: 'number'},
              {data: 'created_at', name: 'created_at'},
              {data: 'id', name: 'id'},
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
                  return '<button type="button" class="btn btn-info btn-sm waves-effect waves-light m-1" id="subscriberedit"><i class="fa fa-pen-square"></i></button><button type="button" class="btn btn-danger btn-sm waves-effect waves-light m-1" id="subscriberdelete"><i class="fa fa-trash-alt"></i></button>';
          }
          }],
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