<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if( !Role::count() ){
            $this->create_roles();
            $this->create_permissions();
            $roles = $this->extract_roles();
            $this->assign_high_permissions($roles);
            $this->assign_medium_permissions( $roles);
        }


    }



    private function create_roles()
    {
        $ROLES = [
            'Admin',
            'Gerente General',
            'Administrativo',
            'Técnico',
        ];
        foreach ($ROLES as $index => $role){
            Role::create(['name'=>$role]);
        }

    }



    private function create_permissions()
    {
        $PERMISSIONS = [
            'admin.users',
            'admin.create.user',
            'admin.edit.user',
            'admin.profile.user',
            'admin.clients',
            'admin.create.client',
            'admin.edit.client',
            'admin.headquarters',
            'admin.create.headquarters',
            'admin.edit.headquarters',
            'admin-store',
            'admin.components', // Management of spare part and tools.
            'admin-general-equipments',
            'admin.equipments',
            'admin.equipments.create',
            'admin.equipments.edit',
            'admin.clients-equipments',
            'admin.clients-equipments.create',
            'admin.clients-equipments.edit',
            'admin.clients-equipments.photo',
            'admin-preventive',
            'admin.preventive-activity',
            'admin.preventive-routine',
            'admin.preventive-routine.create',
            'admin.preventive-routine.edit',
            'admin.schedule',
            'admin.corrective-activities',
            'admin.corrective-management',
            'admin.corrective-management.create',
            'admin.corrective-management.edit',
            'admin.planner.schedule',
            'admin.planner',
            'admin.visit',
            'admin.visit.create',
            'admin.visit.edit',
            'admin.visit.delete',
            'admin.planner.corrective',
            'admin.service-order',
            'admin.service-order.schedule.create',
            'admin.service-order.corrective.create',
            'admin.general-reports',
            'admin.materials',// Management of equipment´s component (brand, serial,etc).
            'admin.general-reports.create.form',
            'admin.general-reports.edit.form',
            'handel-status',
            'admin.pending',
            'handle-limited-time-report' //display button to update limited hours to edit the reports.


        ];

        foreach ($PERMISSIONS as $index => $permission){
            Permission::create(['name'=> $permission]);
        }
    }



    private function extract_roles()
    {
        $admin = Role::where('name','Admin')->get();
        $general_manager = Role::where('name','Gerente General')->get();
        $admin_manager = Role::where('name','Administrativo')->get();
        $technic = Role::where('name','Técnico')->get();

        return [
            'admin'=> $admin,
            'general_manager'=> $general_manager,
            'admin_manager'=> $admin_manager,
            'technic'=> $technic
        ];
    }



    private function assign_high_permissions( $roles)
    {
        $admin            = $roles['admin'];
        $general_manager  = $roles['general_manager'];
        $admin_manager    =  $roles['admin_manager'];
        $high_permissions = Permission::all();
        foreach ( $high_permissions AS $index => $permission ){
            $permission->syncRoles([$admin,$admin_manager, $general_manager]);
        }

    }

    private function assign_medium_permissions( $roles )
    {
        $medium_permissions = [
            'admin.profile.user',
            'admin.corrective-activities',
            'admin.corrective-management',
            'admin.corrective-management.create',
            'admin.planner',
            'admin.service-order',
            'admin.general-reports',
            'admin.general-reports.create.form',
            'admin-store',
            'admin.materials',
            'admin.service-order.corrective.create',
            'admin.planner.corrective',
            'admin.corrective-management.edit',
            'admin.general-reports.edit.form',


        ];
        $permissions = Permission::whereIn('name',$medium_permissions)->get();

        foreach ( $permissions AS $index => $permission ){
            $permission->assignRole($roles['technic']);
        }

    }

}
