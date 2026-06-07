<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class SubAdminController extends Controller
{
    public function __construct()
    {
        if (!session('admin_id')) {
            abort(redirect()->route('admin.login'));
        }
    }

    protected function isMaster(): bool
    {
        $admin = Admin::find(session('admin_id'));
        return $admin && $admin->is_master;
    }

    protected function abortIfNotMaster()
    {
        if (!$this->isMaster()) {
            abort(403, 'Only the main Admin can manage sub-admins.');
        }
    }

    public function index()
    {
        $this->abortIfNotMaster();
        $subAdmins = Admin::where('is_master', false)->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.sub-admins.index', compact('subAdmins'));
    }

    public function create()
    {
        $this->abortIfNotMaster();
        return view('admin.sub-admins.create');
    }

    public function store(Request $request)
    {
        $this->abortIfNotMaster();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = bcrypt($data['password']);

        Admin::create($data);

        return redirect()->route('admin.sub-admins')->with('success', 'Sub Admin created successfully.');
    }

    public function edit($id)
    {
        $this->abortIfNotMaster();

        $subAdmin = Admin::where('is_master', false)->findOrFail($id);
        return view('admin.sub-admins.edit', compact('subAdmin'));
    }

    public function update(Request $request, $id)
    {
        $this->abortIfNotMaster();

        $subAdmin = Admin::where('is_master', false)->findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $subAdmin->update($data);

        return redirect()->route('admin.sub-admins')->with('success', 'Sub Admin updated successfully.');
    }

    public function destroy($id)
    {
        $this->abortIfNotMaster();

        $subAdmin = Admin::where('is_master', false)->findOrFail($id);
        $subAdmin->delete();

        return redirect()->route('admin.sub-admins')->with('success', 'Sub Admin deleted successfully.');
    }
}
