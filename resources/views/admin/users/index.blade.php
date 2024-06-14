@extends('layouts.adminlayout')

@section('content')
<div class="content-wrapper">@if ($errors->any())
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
        <div class="col-lg-12">
          <div class="card">

          {{-- Edit Role : Start --}}
                    <!-- Modal -->
                <div class="modal fade" id="userEditmodal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="form-header text-uppercase">
                          <i aria-hidden="true" class="fa fa-list"></i>
                            Edit User
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
                              <form method="post" action="{{route('update-users')}}" method="post" id="userUpdateForm">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                </div>
                                <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                </div>
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Roles</label>
                                  <div class="col-sm-10">
                                    <select class="role-edit-selection form-control" id="role-edit-selection" name="role">
                                      <option value="" selected="selected">Select Role</option>
                                      <option value="1">Admin</option>
                                      <option value="2">Users</option>
                                    </select>
                                  </div>
                                </div>
                                
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Groups</label>
                                  <div class="col-sm-10">
                                    <select class="group-edit-selection form-control" id="group-edit-selection" name="group_id">
                                        <option value=""></option>
                                    </select>
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

            {{-- Edit Role : END --}}

            {{-- Delete Role : Start --}}
            <!-- Modal -->
                <div class="modal fade" id="roleDeletemodal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="form-header text-uppercase">
                                    <i aria-hidden="true" class="fa fa-list"></i>
                                        Delete User
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
                                            <form method="post" id="roleDeleteForm">
                                                @method('DELETE')
                                                @csrf
                                                <div class="form-group row">
                                                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                                                  <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="name" name="name" disabled>
                                                  </div>
                                                </div>
                                                <div class="form-group row">
                                                  <label for="email" class="col-sm-2 col-form-label">Email</label>
                                                  <div class="col-sm-10">
                                                    <input type="email" class="form-control" id="email" name="email" disabled>
                                                  </div>
                                                </div>
                                                <div class="form-group row">
                                                  <label for="roles" class="col-sm-2 col-form-label">Roles</label>
                                                  <div class="col-sm-10">
                                                    <select class="role-edit-selection form-control" id="role-edit-selection" name="role" disabled>
                                                      <option value="" selected="selected">Select Role</option>
                                                      <option value="1">Admin</option>
                                                      <option value="2">Users</option>
                                                    </select>
                                                  </div>
                                                </div>
                                                
                                                <div class="form-group row">
                                                  <label for="roles" class="col-sm-2 col-form-label">Groups</label>
                                                  <div class="col-sm-10">
                                                    <select class="role-delete-selection form-control" id="role-delete-selection" name="group_id" disabled>
                                                      <option value=""></option>
                                                    </select>
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
            {{-- Delete Role : End --}}

<div class="card">
              <div class="card-header">
                <h3 class="card-title">All Users</h3>
                <div class="float-right">
                  <a href="{{route('users.create')}}" class="btn btn-warning">Create User</a>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usertable" class="table table-bordered data-table table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Groups</th>
                    <th>Role</th>
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
        </div>
      </div><!-- End Row-->

    </div>

</div>
@endsection