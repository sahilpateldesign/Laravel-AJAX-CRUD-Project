@extends('layout.main')
@push('title')
    Index
@endpush
@push('css')
    <style>
        .dnone {display: none;}
    </style>
@endpush
@section('content')
    <div class="tableData mt-5">
        <div class="d-flex justify-content-between mb-3">
            <div class="w-25">
                <input class="form-control" id="searchInput" type="text" placeholder="Search...">
            </div>
            <a onClick="onFormPanel()" href="javascript:void(0);" class="btn btn-primary btn-form">Create <i class="fa fa-plus"></i></a>
            <a onClick="onListPanel()" href="javascript:void(0);" class="btn btn-primary btn-list dnone">Back <i class="fa fa-forward"></i></a>
        </div>



        <div class="table-responsive" id="list-panel">
            @include('partials.intricaretech_table')
        </div>

        <div class="formData dnone" id="form-panel">
            <form id="formData" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" class="dnone" name="userId" id="userId">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{old('name')}}">
                        <span class="text-danger error" id="errorMessagename"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{old('email')}}">
                        <span class="text-danger error" id="errorMessageemail"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" value="{{old('phone')}}">
                        <span class="text-warning d-block">without country code</span>
                        <span class="text-danger error" id="errorMessagephone"></span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label d-block">Gender <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" checked>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>
                        <span class="text-danger error" id="errorMessagegender"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3" id="profileImage">
                        <label for="image" class="form-label">Profile Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <span class="text-warning d-block">Upload file only jpeg, jpg, png</span>
                        <span class="text-danger error" id="errorMessageimage"></span>

                    </div>

                    <div class="col-md-6 mb-3" id="uploadFile">
                        <label for="file" class="form-label">Upload File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf,.doc,.docx,.txt">
                        <span class="text-warning d-block">Upload file only pdf, doc, docx, txt</span>
                        <span class="text-danger error" id="errorMessagefile"></span>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button  type="submit" id="updatecreate" class="btn btn-success"></button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    {{-- Image Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Image</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="imageDisplay" width="100%">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup blur', function() {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('index') }}",
                    method: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('.table-responsive').html(data);
                    }
                });
            });

            $(document).on('click','.image',function (){
                var image = $(this).attr('src');
                $('#imageDisplay').attr('src', image);
                $('#imageModal').modal('show');
            });


            $(document).on('click', '.deleteBtn', function() {
                let id = $(this).data('id');
                let token = "{{ csrf_token() }}";
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't delete this record!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var options = {
                            theme:"sk-cube-grid",
                            message:'Wait...',
                            backgroundColor:"#575758",
                            textColor:"white"
                        };

                        HoldOn.open(options);
                        $.ajax({
                            url: '{{ route('destroy', ':id') }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: token,
                            },
                            success: function(response) {
                                if (response.status === true) {
                                    toastr.success(response.message)
                                    refreshTable();
                                    HoldOn.close();
                                } else {
                                    toastr.error(response.message)
                                    HoldOn.close();
                                }
                            },
                            error: function(xhr) {
                                toastr.error('Something went wrong!')
                                HoldOn.close();
                            }
                        });
                    }
                });
            });

            // refresh function
            function refreshTable() {
                $.ajax({
                    url: "{{ route('index') }}",
                    type: 'GET',
                    success: function(data) {
                        $('.table-responsive').html(data);
                    },
                    error: function(xhr) {
                        toastr.error('Failed to refresh data!')
                    }
                });
            }

            // Create Form

            $('#formData').on('submit', function(event) {
                event.preventDefault();
                var options = {
                    theme:"sk-cube-grid",
                    message:'Wait...',
                    backgroundColor:"#575758",
                    textColor:"white"
                };

                HoldOn.open(options);
                $('.error').text('');

                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('updatestore') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    datatype: "json",
                    success: function(response) {
                        if (response.status === true) {
                            {{--window.location = '{{ route('index') }}';--}}
                            onListPanel();
                            toastr.success(response.message)
                            refreshTable();
                        }
                        HoldOn.close();
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $("#errorMessage"+key).text(value[0]);
                        });
                        HoldOn.close();
                    }
                });
            });




        });

        // Edit Data

        function onEdit(id) {
            $("#updatecreate").html('Update <i class="fa fa-refresh"></i>')
            $.ajax({
                type : 'GET',
                url: '{{ url("edit") }}/' + id,
                success: function (response) {
                    var data = response;

                    $('input[name ="gender"]').attr('checked',false);
                    $("#previewImage").remove();
                    $("#documentFile").remove();
                    $("#image").val("");
                    $("#file").val("");

                    $("#userId").val(data.id);
                    $("#name").val(data.name);
                    $("#email").val(data.email);
                    $("#phone").val(data.phone);
                    $("#"+data.gender).attr('checked',true)
                    if(data.image !== null){
                        $("#profileImage").append('<img id="previewImage" class="mt-2" width="150px" alt="Image" src="' + '/storage/user/image/' + data.image + '">');
                    }

                    if(data.file !== null){
                        var fileName = data.file;
                        var fileType = fileName.split('.').pop().toLowerCase();

                        var fileUrl = '/storage/user/file/' + data.file;

                        if (fileType === 'pdf') {
                            var fileContent = '<object class="mt-2" id="documentFile" data="' + fileUrl + '" type="application/' + fileType + '" width="100%" height="300px">';
                            fileContent += '<embed src="' + fileUrl + '" type="application/' + fileType + '">';
                            fileContent += '<p>This browser does not support Documents. Please download the Document to view it:';
                            fileContent += '<a href="' + fileUrl + '">Download Document</a>.';
                            fileContent += '</p>';
                            fileContent += '</embed>';
                            fileContent += '</object>';

                            $("#uploadFile").append(fileContent);
                        } else if (fileType === 'doc' || fileType === 'docx' || fileType === 'txt') {
                            $("#uploadFile").append('<a href="' + fileUrl + '">Download Document</a>');
                        }
                    }

                    onEditPanel();
                }
            });
        }


        function onFormPanel() {
            $('#formData').each(function () {
                this.reset();
            });
            $("#updatecreate").html('Submit <i class="fa fa-check"></i>')
            $("#userId").val('')
            $('input[name ="gender"]').attr('checked',false);
            $("#male").attr('checked',true)
            $("#previewImage").remove();
            $("#documentFile").remove();
            $("#image").val("");
            $("#file").val("");

            $('#list-panel, .btn-form, #searchInput').hide();
            $('#form-panel, .btn-list').show();
        }

        function onListPanel() {
            $('#list-panel, .btn-form, #searchInput').show();
            $('#form-panel, .btn-list').hide();
        }
        function onEditPanel() {
            $('#list-panel, .btn-form, #searchInput').hide();
            $('#form-panel, .btn-list').show();
        }

    </script>
@endpush
