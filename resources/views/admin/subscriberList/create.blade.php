@extends('layouts.adminlayout')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscriber List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Subscriber List</li>
            </ol>
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
              <div class="card-header">
                <h3 class="card-title">Create Subscriber List</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="SubscriberForm" action="{{route('subscriber-list.store')}}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="exampleInputTitle1">Title*</label>
                              <input type="name" name="title" value="{{ old ('title') }}" class="form-control" id="exampleInputTitle1" placeholder="Enter Title">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="exampleInputdescription1">Description*</label>
                              <textarea name="description" placeholder="Enter Description" id="exampleInputdescription1" class="form-control rounded-0" style="height: 70px;">{{ old ('description') }}</textarea>
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
            {{-- Edit Subscriber List : Start --}}
                    <!-- Modal -->
                <div class="modal fade" id="subscriberEditmodal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="form-header text-uppercase">
                          <i aria-hidden="true" class="fa fa-list"></i>
                            Edit Subscriber List
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
                              <form method="post" action="{{route('update-subscriber-list')}}" method="post" id="subscriberUpdateForm">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                </div>
                                <div class="form-group row">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                  <textarea name="description" placeholder="Enter Description" id="description" class="form-control rounded-0" style="height: 70px;"></textarea>
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

            {{-- Edit Subscriber List : END --}}

            {{-- Delete Subscriber List : Start --}}
            <!-- Modal -->
                <div class="modal fade" id="subscriberDeletemodal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="form-header text-uppercase">
                                    <i aria-hidden="true" class="fa fa-list"></i>
                                        Delete Subscriber List
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
                                                <label for="title" class="col-sm-2 col-form-label">Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="title" name="title" disabled>
                                                </div>
                                                </div>
                                                <div class="form-group row">
                                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                                <div class="col-sm-10">
                                                  <textarea name="description" placeholder="Enter Description" id="description" class="form-control rounded-0" style="height: 70px;" disabled></textarea>
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
            {{-- Delete Subscriber List : End --}}
              <div class="card-header">
                <h3 class="card-title">All Subscriber List</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <table id="SubscriberList" class="table table-bordered data-table table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Created By</th>
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