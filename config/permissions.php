<?php

use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Support\Facades\Config;

return [
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles' => [
        ['name' => 'superadmin', 'level' => 500],
        ['name' => 'admin', 'level' => 490],

    ],

    'locales' => [
        'en',
        'pt-BR',
        'es'
    ],

    'models' => [
        'activity-log',
        'permissions',
        'roles',
        'users',

    ],

    'translates' => [
        'en' => [
            'activity-log' => 'Activity Log',
            'users' => 'Users',
            'permissions' => 'Permissions',
            'roles' => 'Roles',
            'create' => 'Create',
            'read' => 'Read',
            'update' => 'Update',
            'delete' => 'Delete',
            'list' => 'List',

            /**
             * roles names
             */
            'superadmin' => 'Super Administrator',
            'admin' => 'Administrator',

        ],
        'pt-BR' => [
            'activity-log' => 'Log de Atividades',

            'users' => 'Usuários',
            'permissions' => 'Permissões',
            'roles' => 'Papéis',

            'create' => 'Adicionar',
            'read' => 'Ler',
            'update' => 'Editar',
            'delete' => 'Excluir',
            'list' => 'Listar',

            /**
             * roles names
             */
            'superadmin' => 'Super Administrador',
            'admin' => 'Administrador',

        ],
        'es' => [
            'activity-log' => 'Log de Atividades',

            'users' => 'Usuarios',
            'permissions' => 'Permisos',
            'roles' => 'Roles',

            'create' => 'Crear',
            'read' => 'Ler',
            'update' => 'Actualizar',
            'delete' => 'Eliminar',
            'list' => 'Listar',

            /**
             * roles names
             */
            'superadmin' => 'Super Administrador',
            'admin' => 'Administrador',

        ]

    ],

    'perms' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'l' => 'list',

    ],

];
