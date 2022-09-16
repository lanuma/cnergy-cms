<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Redirect;
use function MongoDB\BSON\toJSON;

class RoleController extends Controller
{

    public function index()
    {
        $roles = Role::all();
        return view('admin.role.index',
            ['roles' => $roles]);
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function store(RoleRequest $request)
    {
        $data = $request->input();
        $role = new Role([
            'role' => ucwords($data['role'])
        ]);
        try {
            $role->save();
            return \redirect('role')->with('status', 'Successfully Add New Role');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $role = Role::where('id', $id)->first();
        return view('admin.role.update')->with('role', $role);
    }

    public function update(RoleRequest $request, $id)
    {
        $data = $request->input();
        try {
            Role::where('id', $id)->update(['role' => strtoupper($data['role'])]);
            return redirect("role")->with("status", "Successfully to Update Role ");
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Role::destroy($id);
            return Redirect::back()->with('status', 'Successfully to Delete Role');
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

    }
}
