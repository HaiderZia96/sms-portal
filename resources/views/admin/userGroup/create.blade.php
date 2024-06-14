@extends('layouts.adminlayout')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users Group</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Users Group</li>
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
                <h3 class="card-title">Create Users Group</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" action="{{route('user_groups.store')}}" method="POST">
                @csrf
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="exampleInputName1">Full Name*</label>
                              <input type="name" name="name" class="form-control" id="exampleInputName1" placeholder="Enter full name">
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="exampleInputName1">Masks*</label>
                              <select name="mask[]"  class="form-control rounded-0 mask-multiple" data-placeholder="Select Masks" multiple="multiple" required>
                                @foreach($masks as $mask)
                                  <option value="{{$mask->name}}">{{$mask->name}}</option>
                                @endforeach
                              </select>
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
                <h3 class="card-title">All Users Group</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example122" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created By</th>
                    <th>Updated By</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  @foreach($data as $dt)
                      <div class="modal fade"  id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="form-header text-uppercase">
                                <i aria-hidden="true" class="fa fa-list"></i>
                                  Edit User Group
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
                                    <form method="post" action="{{route('user_groups.update','test')}}" method="post" id="userGroupUpdateForm">
                                      @method('PUT')
                                      @csrf
                                      <input type="hidden" name="group_id" id="group_id" value="">
                                      <div class="form-group row">
                                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="name" value="{{$dt->name}}" name="name" required>
                                        </div>
                                        {{-- <label for="mask[]" class="col-sm-2 col-form-label">Masks</label>
                                        <div class="col-sm-5">
                                          <select name="mask[]"  class="form-control rounded-0 mask-multiple" data-placeholder="Select Masks" multiple="multiple" required>
                                            @foreach($masks as $mask)
                                              <option value="{{$mask->name}}"  @foreach($mask_exps as $mask_exp) {{ $mask->name == $mask_exp ? 'selected' : '' }} @endforeach>{{$mask->name}}</option>
                                            @endforeach
                                          </select>
                                        </div> --}}
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
                  <tbody>
                  <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td>{{$dt->name}}</td>
                    <td>{{$dt->created_by}}</td>
                    <td>{{$dt->updated_by}}</td>
                    <td>
                      
                      {{-- @if(isset($dt->deleted_at) && !empty($dt->deleted_at))
                        <a href="{{route('user_groups.restore', $dt->id)}}" class="btn btn-primary">Restore</a>
                        <a href="{{ route('user_groups.delete', $dt->id) }}" onclick="event.preventDefault();
                          if(confirm('Are you sure you want to delete this?'))
                          document.getElementById('delete-form-{{ $dt->id }}').submit();">

                          <button type="submit" class="btn btn-danger">
                              Delete
                          </button>
                          <form id="delete-form-{{ $dt->id }}" action="{{ route('user_groups.delete', $dt->id) }}" method="POST" style="display: none;">
                              @csrf {{ method_field('DELETE') }}
                          </form>
                        </a>
                      @else --}}
                      <a href="{{route('user_groups.edit', $dt->id)}}">
                          <button type="submit" class="btn btn-primary" data-mytitle="{{$dt->name}}" data-groupid="{{$dt->id}}" data-toggle="modal">
                              <i class="fa fa-pen-square" title="edit"></i>
                          </button>
                      </a>

                        {{-- <a href="{{ route('user_groups.destroy', $dt->id) }}" onclick="event.preventDefault();
                          if(confirm('Are you sure you want to delete this?'))
                          document.getElementById('delete-form-{{ $dt->id }}').submit();">

                          <button type="submit" class="btn btn-danger">
                              <i class="fa fa-trash-alt" title="Delete"></i>
                          </button>
                          <form id="delete-form-{{ $dt->id }}" action="{{ route('user_groups.destroy', $dt->id) }}" method="POST" style="display: none;">
                              @csrf {{ method_field('DELETE') }}
                          </form>
                        </a>
                      @endif --}}
                    </td>
                  </tr>
                  </tbody>
                  @endforeach
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created By</th>
                    <th>Updated By</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              {{$data->links()}}
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
