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

        // Start Message table
        $(function () {
        $("#message").DataTable({
            "responsive": true, "lengthChange": true, "autoWidth": false,
            "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
            "lengthMenu": [[10, 20, 30, 50, 100000000], [10, 20, 30, 50, "All"]],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
              processing: true,
              serverSide: true,
              ajax: "{{ route('get-message') }}",
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'text', name: 'text'},
                  {data: 'description', name: 'description'},
                  {data: 'id', name: 'id'},
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
              }
              ],
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
      
        $('#message').on( 'click', 'button#subscriberedit', function () {
            var e = $("#subscriberEditmodal");
            e.modal();
              var $tr = $(this).closest('tr');
              var rowData = $('#message').DataTable().row($tr).data();

              $("#subscriberUpdateForm").append('<input type="hidden" class="form-control" id="message-id" name="message-id">');
              $(e).find('textarea[name="text"]').val(rowData.text);
              $(e).find('textarea[name="description"]').val(rowData.description);
              $(e).find('input[name="message-id"]').val(rowData.id);
          } );

          $('#subscriberEditmodal').on('hidden.bs.modal', function () {
            $("#subscriberUpdateForm").find('#message-id').remove();
          });

          $('#message').on( 'click', 'button#subscriberdelete', function () {
            var e = $("#subscriberDeletemodal");
            e.modal();
              var $tr = $(this).closest('tr');
              var rowData = $('#message').DataTable().row($tr).data();

              var url= base_url+'/message-delete/'+rowData.id;
              $('#subscriberDeleteForm').attr('action', url);


              $(e).find('textarea[name="text"]').val(rowData.text);
              $(e).find('textarea[name="description"]').val(rowData.description);
              $(e).find('input[name="message-id"]').val(rowData.id);
          } );
    // End Message table
</script>