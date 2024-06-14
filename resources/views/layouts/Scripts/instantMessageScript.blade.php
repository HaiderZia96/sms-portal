<script>
    // Start Instant Message Validation

    $('#instantMessage').validate({
        rules: {
        title: {
            required: true
        },
        mask: {
            required: true
        },
        message: {
            required: true
        },
        number: {
            required: true,
        },
        },
        messages: {
        title: {
            required: "Please enter a title",
        },
        mask: {
            required: "Please enter a mask",
        },
        message: {
            required: "Please enter a message",
        },
        description: "Please enter a full description"
        },
        number: {
            required: "Please enter a number"
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
    // End Instant Message Validation

    var base_url= {!! json_encode(url('/')) !!}

    // Start Instant Message table
    $(function () {
    $("#instant").DataTable({
        "responsive": false, "lengthChange": true, "autoWidth": false,
        "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
        "lengthMenu": [[10, 20, 30, 50, 100000000], [10, 20, 30, 50, "All"]],
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            'colvis'
        ],
          processing: true,
          serverSide: true,
          ajax: "{{ route('get-instant-message') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'title', name: 'title'},
              {data: 'message', name: 'message'},
              {data: 'mask', name: 'mask'},
              {data: 'number', name: 'number'},
              {data: 'created_by', name: 'created_by'},
              {data: 'created_at', name: 'created_at'},
              {data: 'u_id', name: 'u_id'},
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
                var show ='{{ route("instant-message.show", ":u_id") }}';
                show = show.replace(':u_id', data.u_id);
                return '<a href="'+show+'" class="btn btn-sm btn-warning mx-2">More Detail</a>';
              }
              }
              ],
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
    
    // End Instant Message table



</script>