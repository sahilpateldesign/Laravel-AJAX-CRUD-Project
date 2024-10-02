<table class="table table-bordered table-hover" id="dataTable">
    <thead class="table-light">
    <tr>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>File</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$userdata)
        <tr>
            <td>{{$key+=1}}</td>
            <td>
                @if(!empty($userdata->image))
                    <img class="image" width="30px" src="{{asset('storage/user/image/'. $userdata->image)}}" alt="Image">
                @endif
            </td>
            <td>{{$userdata->name}}</td>
            <td>{{$userdata->email}}</td>
            <td>{{$userdata->phone}}</td>
            <td>{{$userdata->gender}}</td>
            <td>
                @if(!empty($userdata->file))
                    <a href="{{ asset('storage/user/file/' . $userdata->file) }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="fa fa-download"></i>
                    </a>
                @endif
            </td>
            <td>
                <a onclick="onEdit('{{ $userdata->id }}')" href="javascript:void(0);" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $userdata->id }}"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

    <div>
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
