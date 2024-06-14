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
              <div class="card-header">
                <h3 class="card-title">Create Instant Message</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="instantMessage" action="{{route('instant-message.store')}}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputtext1">Title</label>
                            <input type="text" value="" name="title" class="form-control" placeholder="Enter Title">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="exampleInputdescription1">Mask</label>
                          <select name="mask"  class="form-control rounded-0" required>
                            <option value="">Select Mask</option>
                            @foreach($masks as $mask)
                              <option value="{{$mask}}">{{$mask}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="exampleInputtext1">Number</label>
                            <input type="text" value="" name="number" class="form-control" placeholder="Example: 923001234567,923002234567,923003234567">
                            <span class="red-color blink_me">* Formate Allowed: <b>923001234567</b><br/>
                               * Not allowed <b>Space</b> for Number</span>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                            <label for="exampleInputmessage1">Message</label>
                            <textarea name="message" placeholder="Enter Message" id="exampleInputmessage1" class="form-control rounded-0" style="height: 80px;">{{ old ('message') }}</textarea>
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
                <h3 class="card-title">All Instant Message</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div class="table-responsive">
                <table id="instant" class="table table-bordered data-table table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Mask</th>
                    <th>Number</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Detail</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
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