<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Pagination\Paginator;

class UserController extends Controller
{
    public function index(User $user)
    {
        // $search = $request->input('search');
        // $query = User::query();

        // if ($search) {
        //     $query->where('name', 'LIKE', "%$search%");
        // }

        // $users = $query->latest()->paginate(5);
        // Paginator::useBootstrap();

        // return view('user.index', compact('users', 'search'))
        //     ->with('i', ($users->currentPage() - 1) * 5);
        return view('user.index',compact('user'));
    }
    public function getUsers(Request $request){

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        // $columnIndex_arr = $request->get('order');
        // $columnName_arr = $request->get('columns');

        // $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        // $columnIndex = $columnIndex_arr[0]['column']; // Column index
        // $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        // $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        // $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecords = User::count();
        $totalRecordswithFilter = User::where('name', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        $records = User::
            where('name', 'like', '%' . $searchValue . '%')
            ->orderBy('id','desc')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data = [];
        $counter = $start+1;
        foreach ($records as $record) {

            $row = [
                $counter,
                $record->name,
                $record->email,
                // Add your action buttons HTML here
                '<a href="' . route('user.edit', $record->id) . '" class="btn"><i class="fa-solid fa-pen"></i></a>&nbsp;' .
            '<a href="' . route('user.show', $record->id) . '" class="btn"><i class="fa-solid fa-eye"></i></a>&nbsp;' .
            '<a data-id="' . $record->id . '" href="' . route('user.destroy', $record->id) . '" class="btn"><i class="fa-solid fa-trash-can"></i></a>'
            ];

            $data[] = $row;
            $counter++;
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordswithFilter,
            'data' => $data,
        ];

        return response()->json($response);
    }
    public function create()
    {
        return view('user.create');
    }
    public function show(User $user){
        return view('user.show',compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        ]);

        $input = $request->all();

        User::create($input);

        return redirect()->route('user.index')
                        ->with('success','User created successfully.');
    }
    public function edit(User $user)
    {
        return view('user.edit',compact('user'));
    }
    public function view_profile(User $user)
    {
        $user = User::where('id',session('id'))->first();
        return view('auth.profile',compact('user'));
    }
    public function edit_profile(Request $request)
    {

        $user = DB::table('users')->where('id',session('id'))->value('name','email');
        $user = [
            'name' => $request->name,
            'email' => $request->email
        ];

        DB::table('users')
            ->where('id',session('id'))
            ->update($user);

        return redirect()->route('view_profile')
            ->with('success', 'User updated successfully.');
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        // Update the user's other fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')
                        ->with('success','user deleted successfully');
    }
    public function set_password(Request $request, User $user)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
        'confirm_password' => 'required|same:new_password',
    ]);


    // Check if the old password matches the one in the database
    if (!Hash::check($request->old_password, $user->password)) {
        return response()->json(['message' => 'Invalid old password.'], 422);
    }

    // Check if the old and new passwords are the same
    if ($request->old_password === $request->new_password) {
        return response()->json(['message' => 'Old and new passwords cannot be the same.'], 422);
    }

    // Update the user's password
    $user->password = $request->new_password;
    $user->save();

    return response()->json(['message' => 'Password updated successfully.']);
}

}
