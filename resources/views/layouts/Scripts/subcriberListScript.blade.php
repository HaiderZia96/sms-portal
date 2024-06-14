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
        description: {
            required: true
        },
        subscriber_list_id: {
            required: true
        },
        number: {
            required: true,
            maxlength: 12
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
        description: "Please enter a description"
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

    // Start Subcriber List table
    $(function () {
    $("#SubscriberList").DataTable({
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
          ajax: "{{ route('get-subscriber-list') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'title', name: 'title'},
              {data: 'description', name: 'description'},
              {data: 'created_by', name: 'created_by'},
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
  
  $('#SubscriberList').on( 'click', 'button#subscriberedit', function () {
      var e = $("#subscriberEditmodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#SubscriberList').DataTable().row($tr).data();

        $("#subscriberUpdateForm").append('<input type="hidden" class="form-control" id="subscriber-id" name="subscriber-id">');
        $(e).find('input[name="title"]').val(rowData.title);
        $(e).find('textarea[name="description"]').val(rowData.description);
        $(e).find('input[name="subscriber-id"]').val(rowData.id);
    } );

    $('#subscriberEditmodal').on('hidden.bs.modal', function () {
      $("#subscriberUpdateForm").find('#subscriber-id').remove();
    });

    $('#SubscriberList').on( 'click', 'button#subscriberdelete', function () {
      var e = $("#subscriberDeletemodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#SubscriberList').DataTable().row($tr).data();

        var url= base_url+'/subscriber-list-delete/'+rowData.id;
        $('#subscriberDeleteForm').attr('action', url);


        $(e).find('input[name="title"]').val(rowData.title);
        $(e).find('textarea[name="description"]').val(rowData.description);
        $(e).find('input[name="subscriber-id"]').val(rowData.id);
    } );
    // End Subcriber List table

</script>