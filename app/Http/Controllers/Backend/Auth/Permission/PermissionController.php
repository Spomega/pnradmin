<?php

namespace App\Http\Controllers\Backend\Auth\Permission;

use App\Http\Requests\Backend\Auth\Permission\ManagePermissionRequest;
use App\Http\Requests\Backend\Auth\Permission\StorePermissionRequest;
use App\Http\Requests\Backend\Auth\Permission\UpdatePermissionRequest;
use App\Repositories\Backend\Auth\PermissionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auth\Permission;
class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     *
     */
    protected  $permissionRepository;

    /**
     * @param PermissionRepository permissionRepository
     *
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
         $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManagePermissionRequest $request)
    {
        //
        return view('backend.auth.permission.index')
            ->withPermissions($this->permissionRepository
            ->orderBy('id','asc')
            ->paginate('25'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ManagePermissionRequest $request)
    {
        //
       return view('backend.auth.permission.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionRequest $request)
    {
        //
        $this->permissionRepository->create($request->only('name'));
        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('alerts.backend.permissions.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param   ManagePermissionRequest $request
     * @param   Permission $permission
     * @return Permission $permission
     */
    public function edit(ManagePermissionRequest $request,Permission $permission )
    {
        return view('backend.auth.permission.edit')
             ->withPermission($permission);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePermissionRequest $request
     * @param  Permission $permission
     * @return mixed
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        //
        $this->permissionRepository->update($permission, $request->only('name'));
        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('alerts.backend.permissions.updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ManagePermissionRequest $request
     * @param  Permission $permission
     * @return mixed
     */
    public function destroy(ManagePermissionRequest $request,Permission $permission)
    {
        $this->permissionRepository->deleteById($permission->id);

      //  event(new RoleDeleted($role));

        return redirect()->route('admin.auth.permission.index')->withFlashSuccess(__('alerts.backend.permissions.deleted'));
    }
}
