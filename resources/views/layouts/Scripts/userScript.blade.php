<script>
// Start User Validation
$('#quickForm').validate({
        rules: {
        email: {
            required: true,
            email: true,
        },
        password: {
            required: true,
            minlength: 8
        },
        role: {
            required: true
        },
        group_id: {
            required: true
        },
        name: {
            required: true
        },
        },
        messages: {
        email: {
            required: "Please enter a email address",
            email: "Please enter a vaild email address"
        },
        password: {
            required: "Please provide a password",
            minlength: "Your password must be at least 8 characters long"
        },
        role: {
            required: "Please enter a role",
        },
        group_id: {
            required: "Please enter a Group id",
        },
        name: "Please enter a full name"
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

    // End User Validation
    var base_url= {!! json_encode(url('/')) !!}
    // Start User table
  $(function () {
    $("#usertable").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
      "lengthMenu": [[10, 20, 30, 50, 100000000], [10, 20, 30, 50, "All"]],
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
        ajax: "{{ route('get-users') }}",
        columns: [
            {data: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'group_id', name: 'group_id'},
            {data: 'role', name: 'role'},
            {data: 'groupId', name: 'groupId'},
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
              return '<button type="button" class="btn btn-info btn-sm waves-effect waves-light m-1" id="edit"><i class="fa fa-pen-square"></i></button><button type="button" class="btn btn-danger btn-sm waves-effect waves-light m-1" id="delete"><i class="fa fa-trash-alt"></i></button>';
          }
        }],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
  $('#usertable').on( 'click', 'button#edit', function () {
      var e = $("#userEditmodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#usertable').DataTable().row($tr).data();

        $("#userUpdateForm").append('<input type="hidden" class="form-control" id="user-id" name="user-id">');
        $(e).find('input[name="name"]').val(rowData.name);
        $(e).find('input[name="email"]').val(rowData.email);
        $(e).find('input[name="role"]').val(rowData.role);
        $(e).find('input[name="group_id"]').val(rowData.group_id);
        $(e).find('input[name="user-id"]').val(rowData.id);
        var selectedGroups = [];
        selectedGroups.push(rowData.groupId);
        var selectedRoles = [];
        selectedRoles.push(rowData.roleId);

        $('#group-edit-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-group",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#group-edit-selection').append('<option value="' + d.id + '">' + d.name + '</option>');
                });

                $(e).find('#group-edit-selection').val(selectedGroups).trigger('change');
                $('#group-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });

          $(e).find('#role-edit-selection').val(selectedRoles).trigger('change');
                $('#role-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });

    } );

    $('#userEditmodal').on('hidden.bs.modal', function () {
      $("#userUpdateForm").find('#user-id').remove();
    });

    $('#usertable').on( 'click', 'button#delete', function () {
      var e = $("#roleDeletemodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#usertable').DataTable().row($tr).data();

        var url= 'user-delete/'+rowData.id;
        $('#roleDeleteForm').attr('action', url);


        $(e).find('input[name="name"]').val(rowData.name);
        $(e).find('input[name="email"]').val(rowData.email);
        $(e).find('input[name="role"]').val(rowData.role);
        $(e).find('input[name="group_id"]').val(rowData.group_id);
        $(e).find('input[name="user-id"]').val(rowData.id);
        var selectedGroups = [];
        selectedGroups.push(rowData.groupId);
        var selectedRoles = [];
        selectedRoles.push(rowData.roleId);


        $('#role-delete-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-group",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#role-delete-selection').append('<option value="' + d.id + '">' + d.name + '</option>');
                });

                $(e).find('#role-delete-selection').val(selectedGroups).trigger('change');
                $('#role-delete-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
          $(e).find('#role-edit-selection').val(selectedRoles).trigger('change');
                $('#role-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });

    } );

    // End User table
</script>