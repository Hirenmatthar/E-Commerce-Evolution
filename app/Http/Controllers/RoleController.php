<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;

class RoleController extends Controller
{
    use AuthorizesRequests;
    function __construct()
    {
        //  $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'getRoles', 'store', 'create', 'edit', 'update', 'show', 'destroy']]);
        //  $this->middleware('permission:role-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:role-list', ['only' => ['show']]);
        //  $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $roles = Role::all();
        return view('role.index',compact('roles'));
    }

    public function getRoles(Request $request)
    {
        // Read value
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $searchValue = $request->input('search.value');

        // Total records
        $totalRecords = Role::count();

        // Apply search filter
        $filteredRecords = Role::where('name', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records with pagination and search
        $records = Role::where('name', 'like', '%' . $searchValue . '%')
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
                '<a href="' . route('role.edit', $record->id) . '" class="btn"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;'.
                '<a href="' . route('role.show', $record->id) . '" class="btn"><i class="fa-solid fa-eye"></i></a>&nbsp;' .
                '<form action="' . route('role.destroy', $record->id) . '" method="POST" style="display:inline">
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
        $permissions = Permission::get();
        return view('role.create',compact('permissions'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'roleName' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('roleName')]);
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('role.index')
                        ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('role.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('role.edit', compact('role', 'roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'roles' => 'required',
            'permissions' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('roles');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('role.index')
                        ->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('role.index')
                        ->with('success','Role deleted successfully');
    }
}
