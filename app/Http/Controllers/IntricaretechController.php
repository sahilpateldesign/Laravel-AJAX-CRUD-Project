<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class IntricaretechController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->get('search');
            if (empty($search)){
                $data = $this->user->paginate(10);
            }else{
                $data = $this->user->when($search == 'male', function ($query) {
                    $query->where('gender', 'male');
                })->when($search != 'male', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('gender', 'like', '%' . $search . '%');
                })->paginate(10);
            }

            return view('partials.intricaretech_table', compact('data'))->render();
        }

        $data = $this->user->paginate(10);
        return view('intricaretech.index',compact('data'));
    }


    public function updatestore(Request $request)
    {
        $id = $request->userId;
        $uId = $id == '' ? '' : ','.$id;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email'.$uId,
            'phone' => 'required|digits:10',
            'gender' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'file' => 'mimes:pdf,doc,docx,txt|max:5120',
        ]);

//        echo "<pre>";
//        print_r($request->all());
        if($id == ''){
            $save = $this->user;
            $save->name = $request->name;
            $save->email = $request->email;
            $save->phone = $request->phone;
            $save->gender = $request->gender;
            if ($request->has('image')) {
                $save->image = file_uploader('user/image/', 'png', $request->file('image'));
            }
            if ($request->has('file')) {
                $formate = $request->file('file')->getClientOriginalExtension();
                $save->file = file_uploader('user/file/', $formate, $request->file('file'));
            }
            $save->save();

            return response()->json(['status'=>true,'message'=>'Create Successfully']);
        }else if ($uId !== '' && $id !== ''){
            $update = $this->user->where('id',$id)->first();
            if(!empty($update)){
                $update->name = $request->name;
                $update->email = $request->email;
                $update->phone = $request->phone;
                $update->gender = $request->gender;
                if ($request->has('image')) {
                    $update->image = file_uploader('user/image/', 'png', $request->file('image'));
                }
                if ($request->has('file')) {
                    $formate = $request->file('file')->getClientOriginalExtension();
                    $update->file = file_uploader('user/file/', $formate, $request->file('file'));
                }
                $update->save();
                return response()->json(['status'=>true,'message'=>'Updated Successfully']);
            }
        }

        return response()->json(['status'=>false,'message'=>'Invalid Argument']);
    }

    public function edit(Request $request,$id)
    {
        $user = $this->user->where('id',$id)->first();

        if(!empty($user)){
            return response()->json($user);
//            return view('intricaretech.edit',compact('user'));
        }

        Toastr::error('Invalid Argument');
        return back();
    }


    public function destroy($id)
    {
        $user = $this->user->where('id',$id)->first();

        if(!empty($user)){
            file_remover('user/image/', $user->image);
            file_remover('user/file/', $user->file);
            $user->delete();
            return response()->json(['status'=>true,'message'=>'Deleted Successfully']);
        }

        return response()->json(['status'=>false,'message'=>'Invalid Argument']);
    }
}
