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
        <div class="row">
          <div class="col-sm-12">
            <a class="float-sm-right ml-2" href="{{asset('img/Testing-campaign.xlsx')}}" target="_blank"><button type="button" class="btn btn-primary">Download Sample File</button></a>
          </div>
        </div>
        
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
      <div class="error">{{ $errors->first('firstname') }}</div>
    @endif
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
              <!-- form start -->
              <form id="SubscriberForm" class="add_number" action="{{route('subscriber.store')}}" method="POST">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Add New Contact Number</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <input type="hidden" name="name" value="{{ Auth::user()['name'] }}" class="form-control rounded-0">
                    <input type="hidden" name="email" value="{{ Auth::user()['email'] }} " class="form-control rounded-0">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputTitle1">Subscriber List*</label>
                        <select name="subscriber_list_id"  class="form-control">
                          <option value= "" >Select Subscriber List</option>
                          @foreach($subscriberLists  as $subscriberList)
                            <option value="{{$subscriberList->id}}">{{$subscriberList->title}}</option>
                          @endforeach
                        </select> 
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputdescription1">Number*</label>
                        <input type="number" value="" name="number" class="form-control" placeholder="Enter Number">
                        <span><small>* Only Formate Allowed <b>923001234567</b></small></span>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputdescription1">Description*</label>
                        <input type="text" value="" name="description" class="form-control" placeholder="Enter Description">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <button type="button" class="btn btn_hide">Upload CSV File</button>
                        <button type="button" class="btn btn_show d-none">Upload By Single Number</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>


              <form id="SubscriberForm" class="add_csv d-none" action="{{route('subscriber-csv-upload')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Upload CSV File</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <input type="hidden" name="name" value="{{ Auth::user()['name'] }}" class="form-control rounded-0">
                    <input type="hidden" name="email" value="{{ Auth::user()['email'] }} " class="form-control rounded-0">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputTitle1">Subscriber List*</label>
                        <select name="subscriber_list_id"  class="form-control">
                          <option value= "" >Select Subscriber List</option>
                          @foreach($subscriberLists  as $subscriberList)
                            <option value="{{$subscriberList->id}}">{{$subscriberList->title}}</option>
                          @endforeach 
                        </select> 
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputdescription3">Upload .CSV File*</label>
                        <input type="file" name="number" class="form-control file_csv" />
                        <span><small>* Only Formate Allowed  <b>+923001234567</b></small></span>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputdescription2">Description*</label>
                        <input type="text" value="" name="description" class="form-control" placeholder="Enter Description">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <button type="button" class="btn btn_hide">Upload CSV File</button>
                        <button type="button" class="btn btn_show d-none">Upload By Single Number</button>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>


            </div>
            <!-- /.card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Subscriber</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="subscriber" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Subscriber List Name</th>
                    <th>Creator Name</th>
                    <th>Creator Email</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  @foreach($data as $dt)
                  <tbody>
                  </tbody>
                  @endforeach
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
  var base_url= {!! json_encode(url('/')) !!}

  // Start subscriber Message table
  $(function () {
  $("#subscriber").DataTable({
      "responsive": false, "lengthChange": true, "autoWidth": false,
      "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
      "lengthMenu": [[10, 20, 30, 50, 100000000], [10, 20, 30, 50, "All"]],
      buttons: [
          {
              extend: 'copyHtml5',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5 ]
              }
          },
          {
              extend: 'excelHtml5',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5 ]
              }
          },
          {
              extend: 'csvHtml5',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5 ]
              }
          },
          {
              extend: 'pdfHtml5',
              exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5 ]
              }
          },
          {
              extend: 'print',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5 ]
              }
          },
          'colvis'
      ],
        processing: true,
        serverSide: true,
        ajax: "{{ route('getSubscrib') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'title', name: 'title'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'created_by', name: 'created_by'},
            {data: 'created_at', name: 'created_at'},
            {data: 'subscriber_list_id', name: 'subscriber_list_id'},
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
              var show ='{{ route("subscriber.show", ":subscriber_list_id") }}';
              show = show.replace(':subscriber_list_id', data.subscriber_list_id);
              return '<a href="'+show+'" class="btn btn-sm btn btn-primary mx-2">Contact Detail</a>';
            }
            }
            ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  
  // End Instant Message table
</script>
@endsection