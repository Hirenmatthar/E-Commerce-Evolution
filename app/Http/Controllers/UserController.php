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
use Illuminate\Support\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function index(Request $request, User $users)
    {
        return view('user.index',compact('users'));
    }
    public function getUsers(Request $request)
    {
        // Read value
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $searchValue = $request->input('search.value');

        // Total records
        $totalRecords = User::count();

        // Apply search filter
        $filteredRecords = User::where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records with pagination and search
        $records = User::where('name', 'like', '%' . $searchValue . '%')
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = [];
        $counter = $start + 1;

        foreach ($records as $record) {
            $row = [
                $counter,
                $record->name,
                $image = $record->image ? '<img src="' . asset($record->image) . '" alt="User Image" width="100">' : 'No Image',
                $record->roles,
                $record->email,
                '<a href="' . route('user.edit', $record->id) . '" class="btn"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;' .
                '<a href="' . route('user.show', $record->id) . '" class="btn"><i class="fa-solid fa-eye"></i></a>&nbsp;' .
                '<form action="' . route('user.destroy', $record->id) . '" method="POST" style="display:inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn"><i class="fa-solid fa-trash-can"></i></button>
                </form>'
            ];

            $data[] = $row;
            $counter++;
        }

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];

        return response()->json($response);
    }
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('user.create',compact('roles'));
    }
    public function show(User $user){
        return view('user.show',compact('user'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'roles' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'email' => 'required|email',
            'password' => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()]
        ]);

        $user = new User();
        $user->roles = $request->roles;
        $user->assignRole($request->input('roles'));

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(\public_path('user_images'), $imageName);
            $user->image = 'user_images/' . $imageName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.index')
                        ->with('success','User created successfully.');
    }
    public function edit(User $user)
    {
        $userRole = $user->roles;
        $roles = Role::pluck('name','name')->all();
        return view('user.edit', compact('user', 'roles', 'userRole'));
    }

    public function view_profile(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRole = Auth::user()->roles;
        return view('auth.profile', compact('roles', 'userRole'));
    }
    public function edit_profile(Request $request)
    {
        $request->validate([
            'roles' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'email' => 'required|email'
        ]);

        // Update the user's other fields
        $user = [
            'roles' => $request->roles,
            'name' => $request->name,
            'email' => $request->email,
            'image' => null
        ];

        $previousImage = Auth::user()->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('d-m-y') . "_" . $image->getClientOriginalName();
            $destinationPath = 'user_images';
            $path = $image->move($destinationPath, $imageName);

            if ($previousImage) {
                // Delete the previous image
                File::delete(public_path($previousImage));
            }
            $user['image'] = $path;
        } elseif ($request->has('delete_image')) {
            // Delete the image if delete_image checkbox is selected
            if ($previousImage) {
                File::delete(public_path($previousImage));
            }
            $user['image'] = null;
        } else {
            // No new image selected and delete_image checkbox not selected, keep the previous image
            $user['image'] = $previousImage;
        }

        DB::table('users')
                ->where('id',Auth::user()->id)
                ->update($user);


        return redirect()->route('view_profile')
            ->with('success', 'User updated successfully.');
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'email' => 'required|email'
        ]);

        // Update the user's other fields
        $user->roles = $request->roles;

        $previousImage = $user->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('d-m-y') . "_" . $image->getClientOriginalName();
            $destinationPath = 'user_images';
            $path = $image->move($destinationPath, $imageName);

            if ($previousImage) {
                // Delete the previous image
                File::delete(public_path($previousImage));
            }
            $user->image = $path;
        } elseif ($request->has('delete_image')) {
            // Delete the image if delete_image checkbox is selected
            if ($previousImage) {
                File::delete(public_path($previousImage));
            }
            $user->image = null;
        } else {
            // No new image selected and delete_image checkbox not selected, keep the previous image
            $user->image = $previousImage;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'User updated successfully.');
    }
    public function destroy(User $user)
    {
        if (method_field('DELETE')) {
            // Delete the image if delete_image checkbox is selected
            $previousImage = $user->image;
            if ($previousImage) {
                File::delete(public_path($previousImage));
            }
            $user->image = null;
            $user->delete();
            return redirect()->route('user.index')
                            ->with('success','User deleted successfully');
        }
    }
    public function set_password(Request $request, User $user)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => ['required','string',Password::min(8)->letters()->numbers()->mixedCase()->symbols()],
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = DB::table('users')->where('id',Auth::user()->id)->first();
        // Check if the old password matches the one in the database
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->route('view_profile')->with('error','Something went Wrong.');
        }

        // Check if the old and new passwords are the same
        if ($request->old_password === $request->new_password) {
            return redirect()->route('view_profile')->with('error','Something went Wrong.');
        }
        // Update the user's password
        $user = [
            'password' => Hash::make($request->new_password),
        ];
        DB::table('users')
                ->where('id',Auth::user()->id)
                ->update($user);

        // return response()->json(['message'=>'Password Updated Successfully!...']);
        return redirect()->route('view_profile')->with('success','Profile Updated Successfully.');
    }
}
