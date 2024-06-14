<script>
    $(function () {
      //Date and time picker
    $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
    
    //Date and time picker
    $('#reservationdatetimeedit').datetimepicker({ icons: { time: 'far fa-clock' } });

    // Start Campaign Validation
    
    $('#campaignForm').validate({
        rules: {
        title: {
            required: true
        },
        message_id: {
            required: true
        },
        subscriber_list_id: {
            required: true
        },
        mask: {
            required: true
        },
        start_date_time: {
            required: true,
        },
        },
        messages: {
        title: {
            required: "Please enter a title",
        },
        message_id: {
            required: "Please enter a message",
        },
        subscriber_list_id: {
            required: "Please enter a Subscriber List Id",
        },
        description: "Please enter a full description"
        },
        start_date_time: {
            required: "Please enter a start date and time",
        },
        mask: {
            required: "Please enter a mask",
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
    // End Campaign Validation
    
    });

    var base_url= {!! json_encode(url('/')) !!}

    // Start Campaign table
    $(function () {
      $('#campagin-load').click(function() {
          location.reload();
      });
    $("#campaign").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false,
        "dom": '<"top"<"left-col"B><"right-col"f>><"top-Panding-col"l>rtip',
        "lengthMenu": [[10, 20, 30, 50, 100000000], [10, 20, 30, 50, "All"]],
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                  columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            'colvis'
        ],
          processing: true,
          serverSide: true,
          ajax: "{{ route('get-campaign') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'title', name: 'title'},
              {data: 'subscriber_list_id', name: 'subscriber_list_id'},
              {data: 'message_id', name: 'message_id'},
              {data: 'mask', name: 'mask'},
              {data: 'start_date_time', name: 'start_date_time'},
              {data: 'created_by', name: 'created_by'},
              {data: 'created_at', name: 'created_at'},
              // {data: 'id', name: 'id'},
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
              var show ='{{ route("campaign.show", ":id") }}';
                show = show.replace(':id', data.id);
                return '<a href="'+show+'" class="btn btn-sm btn-warning mx-2">More Detail</a>';
            }
          }
          // ,
            // {
            //   // puts a button in the last column
            //   targets: [-2], "orderable": false, render: function (a, b, data, d) {
            //           return '<button type="button" class="btn btn-info btn-sm waves-effect waves-light m-1" id="campaignedit"><i class="fa fa-pen-square"></i></button><button type="button" class="btn btn-danger btn-sm waves-effect waves-light m-1" id="campaigndelete"><i class="fa fa-trash-alt"></i></button>';
            //   }
            // }
          ],
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  
  $('#campaign').on( 'click', 'button#campaignedit', function () {
      var e = $("#campaignEditmodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#campaign').DataTable().row($tr).data();

        $("#campaignUpdateForm").append('<input type="hidden" class="form-control" id="campaign-id" name="campaign-id">');
        $(e).find('input[name="title"]').val(rowData.title);
        $(e).find('input[name="subscriber_list_id"]').val(rowData.subscriber_list_id);
        $(e).find('input[name="message_id"]').val(rowData.message_id);
        $(e).find('input[name="mask"]').val(rowData.mask);
        $(e).find('input[name="start_date_time"]').val(rowData.start_date_time);
        $(e).find('input[name="description"]').val(rowData.description);
        $(e).find('input[name="campaign-id"]').val(rowData.id);

        
        var selectedSubscriber = [];
        selectedSubscriber.push(rowData.list_id);
        var selectedmessage = [];
        selectedmessage.push(rowData.messageId);
        var selectedmasks = [];
        selectedmasks.push(rowData.mask);

        $('#subscriber-edit-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-campaignSubscriber",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#subscriber-edit-selection').append('<option value="' + d.id + '">' + d.title + '</option>');
                });

                $(e).find('#subscriber-edit-selection').val(selectedSubscriber).trigger('change');
                $('#subscriber-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
          $('#message-edit-selection').empty()
          $.ajax({
              type: "GET",
              url: base_url+"/get-all-message",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#message-edit-selection').append('<option value="' + d.id + '">' + d.text + '</option>');
                });

                $(e).find('#message-edit-selection').val(selectedmessage).trigger('change');
                $('#message-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
          $(e).find('#mask-edit-selection').val(selectedmasks).trigger('change');
                $('#mask-edit-selection').select2({
                    width: '100%',
                    height: 'auto',
                });

    } );

    $('#campaignEditmodal').on('hidden.bs.modal', function () {
      $("#campaignUpdateForm").find('#campaign-id').remove();
    });

    $('#campaign').on( 'click', 'button#campaigndelete', function () {
      var e = $("#campaignDeletemodal");
      e.modal();
        var $tr = $(this).closest('tr');
        var rowData = $('#campaign').DataTable().row($tr).data();

        var url= base_url+'/campaign-delete/'+rowData.id;
        $('#campaignDeleteForm').attr('action', url);

        $(e).find('input[name="title"]').val(rowData.title);
        $(e).find('input[name="subscriber_list_id"]').val(rowData.subscriber_list_id);
        $(e).find('input[name="message_id"]').val(rowData.message_id);
        $(e).find('input[name="mask"]').val(rowData.mask);
        $(e).find('input[name="start_date_time"]').val(rowData.start_date_time);
        $(e).find('input[name="description"]').val(rowData.description);
        $(e).find('input[name="campaign-id"]').val(rowData.id);

        
        var selectedSubscriber = [];
        selectedSubscriber.push(rowData.list_id);
        var selectedmessage = [];
        selectedmessage.push(rowData.messageId);
        var selectedmasks = [];
        selectedmasks.push(rowData.mask);

        $('#subscriber-delete-selection').empty()
        $.ajax({
              type: "GET",
              url: base_url+"/get-all-campaignSubscriber",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#subscriber-delete-selection').append('<option value="' + d.id + '">' + d.title + '</option>');
                });

                $(e).find('#subscriber-delete-selection').val(selectedSubscriber).trigger('change');
                $('#subscriber-delete-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
          $('#message-delete-selection').empty()
          $.ajax({
              type: "GET",
              url: base_url+"/get-all-message",
              success: function(data){
                var opts = $.parseJSON(data);
                $.each(opts, function(i, d) {
                  $('#message-delete-selection').append('<option value="' + d.id + '">' + d.text + '</option>');
                });

                $(e).find('#message-delete-selection').val(selectedmessage).trigger('change');
                $('#message-delete-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
              }
          });
          $(e).find('#mask-delete-selection').val(selectedmasks).trigger('change');
                $('#mask-delete-selection').select2({
                    width: '100%',
                    height: 'auto',
                });
    } );
    // End Campaign table

</script>