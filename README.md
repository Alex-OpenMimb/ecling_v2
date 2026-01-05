## Sincronizar permisos de configuración

Disponemos del comando `php artisan app:sync-configuration-permissions` para crear y asignar los permisos relacionados con la sección de configuraciones.

### Permisos que se generan

- `admin.configurations`
- `admin.configurations.title.image.view`
- `admin.configurations.title.image.create`
- `admin.configurations.title.image.edit`
- `admin.configurations.title.image.delete`

### Roles involucrados

El comando busca los roles:

- `Admin`
- `Gerente General`
- `Administrativo`

Si alguno no existe se mostrará un mensaje de error y el proceso finalizará sin cambios.

### Cómo usarlo

1. Asegúrate de que los roles anteriores estén creados.
2. Ejecuta el comando:

   ```
   php artisan app:sync-configuration-permissions
   ```

3. Si todo es correcto verás el mensaje `Configuration permissions synced successfully.` indicando que los permisos fueron creados (si no existían) y asignados a los roles.

### Ampliaciones

- Para agregar permisos adicionales, edita el arreglo `$permissionNames` dentro de `app/Console/Commands/SyncConfigurationPermissions.php`.
- Para incluir más roles, agrega las consultas dentro del comando y súmalos al arreglo `$targetRoles`.

