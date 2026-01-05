<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncConfigurationPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-configuration-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create configuration permissions and assign them to administrative roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissionNames = [
            'admin.configurations',
            'admin.configurations.title.image.view',
            'admin.configurations.title.image.create',
            'admin.configurations.title.image.edit',
            'admin.configurations.title.image.delete',
        ];

        $permissions = collect();

        foreach ($permissionNames as $permissionName) {
            $permissions->push(
                Permission::firstOrCreate(['name' => $permissionName])
            );
        }

        $admin = Role::where('name', 'Admin')->first();
        $generalManager = Role::where('name', 'Gerente General')->first();
        $adminManager = Role::where('name', 'Administrativo')->first();

        if (! $admin || ! $generalManager || ! $adminManager) {
            $this->error('One or more target roles were not found. Please ensure the roles exist before running this command.');

            return Command::FAILURE;
        }

        $targetRoles = collect([$admin, $generalManager, $adminManager]);

        $permissions->each(function (Permission $permission) use ($targetRoles) {
            $targetRoles->each(fn (Role $role) => $role->givePermissionTo($permission));
        });

        $this->info('Configuration permissions synced successfully.');

        return Command::SUCCESS;
    }
}
