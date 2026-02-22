<?php
namespace permission\role\logic;

use permission\role\objects\Role;
use permission\role\repository\RolePermissionRepository;
use permission\role\repository\RoleRepository;

class SetRole{
    protected RoleRepository $roleRepo;
    protected RolePermissionRepository $permissionRepo;

    public function __construct(){
        $this->roleRepo = new RoleRepository();
        $this->permissionRepo = new RolePermissionRepository();
    }

    public function set(Role $role):void{
        $collector = $this->roleRepo->listRoles([
            'userId' => $role->userId()
        ]);
        if($collector->hasItem()){
            $this->roleRepo->edit($role);
            $this->permissionRepo->edit($role->permission());
            return;
        }
        $this->roleRepo->create($role);
        $this->permissionRepo->create($role->permission());
    }
}