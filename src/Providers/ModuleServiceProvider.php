<?php

namespace Btybug\User\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'users');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'users');

        $tabs = [
            'edit_profile' => [
                [
                    'title' => 'Login Details',
                    'url' => '/admin/profile/login-details',
                    'icon' => 'fa fa-pencil'
                ],
                [
                    'title' => 'Profile',
                    'url' => '/admin/profile/edit',
                    'icon' => 'fa fa-user'
                ]
            ],
            'users' => [
                [
                    'title' => 'Admin users',
                    'url' => '/admin/users/configuration',
                    'icon' => 'fa fa-user'
                ],
                [
                    'title' => 'Site users',
                    'url' => '/admin/users/configuration-site-users',
                    'icon' => 'fa fa-users'
                ]
            ],
            'admins_users' => [
                [
                    'title' => 'Site Users',
                    'url' => '/admin/users',
                ],
                [
                    'title' => 'Admins',
                    'url' => '/admin/users/admins',
                ]
            ], 'role_membership' => [
                [
                    'title' => 'Roles',
                    'url' => '/admin/users/roles',
                ],
                [
                    'title' => 'Statuses',
                    'url' => '/admin/users/roles/statuses',
                ],
                [
                    'title' => 'Conditions',
                    'url' => '/admin/users/roles/conditions',
                ]
            ]
        ];
        $toggleTabs = ['profile' => [
            [
                'title' => 'Timeline',
                'view' => 'users::profile.home',
                'id' => 'home',
                'icon' => 'fa fa-pencil'
            ], [
                'title' => 'About',
                'view' => 'users::profile.about',
                'id' => 'profile',
                'icon' => 'fa fa-user'

            ], [
                'title' => 'Activities',
                'view' => 'users::profile.activities',
                'id' => 'activities',
                'icon' => 'fa fa-laptop'

            ], [
                'title' => 'Message',
                'view' => 'users::profile.messages',
                'id' => 'messages',
                'icon' => 'fa fa-envelope'

            ],
        ],
            'edit_user' => [
                [
                    'title' => 'Profile',
                    'view' => 'users::account.profile',
                    'id' => 'profile',
                    'icon' => 'fa fa-laptop'
                ],
                [
                    'title' => 'Login',
                    'view' => 'users::account.login',
                    'id' => 'login',
                    'icon' => 'fa fa-envelope'
                ], [
                    'title' => 'Panel',
                    'view' => 'users::account.panel',
                    'id' => 'panel',
                    'icon' => 'fa fa-object-ungroup'
                ],
            ]

        ];
        \Eventy::action('toggle.tabs', $toggleTabs);

        \Eventy::action('my.tab', $tabs);
        $userOptions = [
            'lock' => [
                'html' => '<a class="btn btn-danger btn-xs" href="/admin/users/[id]/lock"><i class="fa fa-lock"></i></a>'
            ]
        ];
        \Eventy::action('user.options', $userOptions);

        \Eventy::action('admin.menus', [
            "title" => "Users",
            "custom-link" => "",
            "icon" => "fa fa-users",
            "is_core" => "yes",
            "children" => [
                [
                    "title" => "All Users",
                    "custom-link" => "/admin/users",
                    "icon" => "fa fa-angle-right",
                    "is_core" => "yes"
                ], [
                    "title" => "Roles & Memberships",
                    "custom-link" => "/admin/users/roles",
                    "icon" => "fa fa-angle-right",
                    "is_core" => "yes"
                ]
            ]
        ]);

    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
