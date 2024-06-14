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
          <button id="campagin-load" class="btn btn-primary">Load Campaign</button>
      </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('error') }}
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
              <form id="campaignForm" action="{{route('campaign.store')}}" method="POST">
                @csrf
                <div class="card-header">
                  <h3 class="card-title">Add New Campaign</h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputdescription1">Title</label>
                        <input type="text" value="" name="title" class="form-control" placeholder="Enter Title">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="exampleInputTitle1">Subscriber List</label>
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
                        <label for="exampleInputdescription1">Message ID</label>
                        <select name="message_id"  class="form-control">
                          <option value= "" >Select System ID</option>
                          @foreach($messages  as $message)
                            <option value="{{$message->id}}">{{$message->id}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <!-- Date and time -->
                      <div class="form-group">
                        <label>Schedule Date and time:</label>
                          <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                              <input type="text" name="start_date_time" class="form-control datetimepicker-input" data-target="#reservationdatetime"/>
                              <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>
                      <!-- /.form group -->
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
                        <label for="exampleInputdescription1">Description</label>
                        <input type="text" value="" name="description" class="form-control" placeholder="Enter Description">
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
              {{-- Edit Campaign : Start --}}
                    <!-- Modal -->
                <div class="modal fade" id="campaignEditmodal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="form-header text-uppercase">
                          <i aria-hidden="true" class="fa fa-list"></i>
                            Edit Campaign
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
                              <form method="post" action="{{route('update-campaign')}}" method="post" id="campaignUpdateForm">
                                @method('PUT')
                                @csrf
                                <div class="form-group row">
                                <label for="title" class="col-sm-2 col-form-label">Title</label>
                                <div class="col-sm-10">
                                  <input type="text" value="" name="title" class="form-control" placeholder="Enter Title">
                                </div>
                                </div>
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Subscriber List</label>
                                  <div class="col-sm-10">
                                    <select class="subscriber-edit-selection form-control" id="subscriber-edit-selection" name="subscriber_list_id">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Message List</label>
                                  <div class="col-sm-10">
                                    <select class="message-edit-selection form-control" id="message-edit-selection" name="message_id">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="roles" class="col-sm-2 col-form-label">Mask</label>
                                  <div class="col-sm-10">
                                    <select class="mask-edit-selection form-control" id="mask-edit-selection" name="mask">
                                      <option value="">Select Mask</option>
                                      <option value="TUF" >TUF</option>
                                      <option value="TUF.ENGR" >TUF.ENGR</option>
                                      <option value="TUF.FSD" >TUF.FSD</option>
                                      <option value="TUF.HEALTH" >TUF.HEALTH</option>
                                      <option value="TUF.PORTAL" >TUF.PORTAL</option>
                                      <option value="TUF.RESULT" >TUF.RESULT</option>
                                      <option value="TUFFIANS" >TUFFIANS</option>
                                      <option value="UMDC" >UMDC</option>
                                      <option value="UMDC.FSD" >UMDC.FSD</option>
                                      <option value="UNIofFSD" >UNIofFSD</option>
                                      <option value="GIU" >GIU</option>
                                      <option value="GSS" >GSS</option>
                                      <option value="MTH" >MTH</option>
                                      <option value="UCS" >UCS</option>
                                      <option value="UCW" >UCW</option>
                                      <option value="AFH" >AFH</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="reservationdatetimeedit" class="col-sm-2 col-form-label">Date and time:</label>
                                  <div class="col-sm-10">
                                    <div class="input-group date" id="reservationdatetimeedit" data-target-input="nearest">
                                      <input type="text" name="start_date_time" class="form-control datetimepicker-input" data-target="#reservationdatetimeedit"/>
                                      <div class="input-group-append" data-target="#reservationdatetimeedit" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="" class="col-sm-2 col-form-label">Description</label>
                                  <div class="col-sm-10">
                                    <input type="text" value="" name="description" class="form-control" placeholder="Enter Description">
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

                {{-- Edit Campaign : END --}}

                {{-- Delete Campaign : Start --}}
                <!-- Modal -->
                <div class="modal fade" id="campaignDeletemodal">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h4 class="form-header text-uppercase">
                              <i aria-hidden="true" class="fa fa-list"></i>
                                  Delete Campaign
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
                                      <form method="post" id="campaignDeleteForm">
                                          @method('DELETE')
                                          @csrf
                                          <div class="form-group row">
                                          <label for="title" class="col-sm-2 col-form-label">Title</label>
                                          <div class="col-sm-10">
                                            <input type="text" value="" name="title" class="form-control" placeholder="Enter Title" disabled>
                                          </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="roles" class="col-sm-2 col-form-label">Subscriber List</label>
                                            <div class="col-sm-10">
                                              <select class="subscriber-delete-selection form-control" id="subscriber-delete-selection" name="subscriber_list_id" disabled>
                                                  <option value=""></option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="roles" class="col-sm-2 col-form-label">Message List</label>
                                            <div class="col-sm-10">
                                              <select class="message-delete-selection form-control" id="message-delete-selection" name="message_id" disabled>
                                                  <option value=""></option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="roles" class="col-sm-2 col-form-label">Mask</label>
                                            <div class="col-sm-10">
                                              <select class="mask-delete-selection form-control" id="mask-delete-selection" name="mask" disabled>
                                                <option value="">Select Mask</option>
                                                <option value="TUF" >TUF</option>
                                                <option value="TUF.ENGR" >TUF.ENGR</option>
                                                <option value="TUF.FSD" >TUF.FSD</option>
                                                <option value="TUF.HEALTH" >TUF.HEALTH</option>
                                                <option value="TUF.PORTAL" >TUF.PORTAL</option>
                                                <option value="TUF.RESULT" >TUF.RESULT</option>
                                                <option value="TUFFIANS" >TUFFIANS</option>
                                                <option value="UMDC" >UMDC</option>
                                                <option value="UMDC.FSD" >UMDC.FSD</option>
                                                <option value="UNIofFSD" >UNIofFSD</option>
                                                <option value="GIU" >GIU</option>
                                                <option value="GSS" >GSS</option>
                                                <option value="MTH" >MTH</option>
                                                <option value="UCS" >UCS</option>
                                                <option value="UCW" >UCW</option>
                                                <option value="AFH" >AFH</option>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="reservationdatetime" class="col-sm-2 col-form-label">Date and time:</label>
                                            <div class="col-sm-10">
                                              <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                                <input type="text" name="start_date_time" class="form-control datetimepicker-input" data-target="#reservationdatetime" disabled/>
                                                <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                              <input type="text" value="" name="description" class="form-control" placeholder="Enter Description" disabled>
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
                {{-- Delete Campaign : End --}}
              <div class="card-header">
                <h3 class="card-title">All Campaigns</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <table id="campaign" class="table table-bordered data-table table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Subscriber List Name</th>
                    <th>Message List Name</th>
                    <th>Mask</th>
                    <th>Start Date Time</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    {{-- <th>Action</th> --}}
                    <th>Detail</th>
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
