<?php
return [
    [
        'section' => 'Admin Roles',
        'functions' => [
            [
                'title' => 'Admin Roles',
                'des' => 'This function provides all Admin Roles Listing',
                'params' => 'NONE',
                'return' => 'Admin Roles Listing',
                'code_snap' => 'BBAdminRoles()'
            ],
            [
                'title' => 'Admin Roles List',
                'des' => 'This function provides all Admin Roles in List Style',
                'params' => 'NONE',
                'return' => 'Admin Roles in laravel lists As ["id"=>"name"]  ',
                'code_snap' => 'BBAdminRolesList()'
            ],
            [
                'title' => 'Admin Role',
                'des' => 'This function provides Admin Role Details',
                'params' => 'id of role',
                'return' => 'Admin Role Details',
                'code_snap' => 'BBAdminRole($id)'
            ],
            [
                'title' => 'Admin Users By Role',
                'des' => 'This function provides Admin Users By Role',
                'params' => 'id of role',
                'return' => 'Admin Users Collection',
                'code_snap' => 'BBAdminRoleUsers($id)'
            ]

        ]
    ],

    [
        'section' => 'Admin Users',
        'functions' => [
            [
                'title' => 'Admin Users',
                'des' => 'This function provides all Admin Users Listing',
                'params' => 'NONE',
                'return' => 'Admin Users Listing',
                'code_snap' => 'BBAdminUsers()'
            ],
            [
                'title' => 'Admin User',
                'des' => 'This function provides Admin User Details',
                'params' => 'id of user',
                'return' => 'Admin User Details',
                'code_snap' => 'BBAdminUser($id)'
            ],
            [
                'title' => 'Logedin User',
                'des' => 'This function provides Loged in User Details',
                'params' => 'NONE',
                'return' => 'Loged in User Details',
                'code_snap' => 'BBAuthUser()'
            ]
        ]
    ],
    [
        'section' => 'User Groups',
        'functions' => [
            [
                'title' => 'User Groups',
                'des' => 'This function provides all User Groups Listing',
                'params' => 'NONE',
                'return' => 'User Groups Listing',
                'code_snap' => 'BBUserGroups()'
            ],
            [
                'title' => 'User Group',
                'des' => 'This function provides User Group Details',
                'params' => 'id of group',
                'return' => 'User Group Details',
                'code_snap' => 'BBUserGroup($id)'
            ]
        ]
    ],

    [
        'section' => 'Users',
        'functions' => [
            [
                'title' => 'Users',
                'des' => 'This function provides all Users Listing',
                'params' => 'NONE',
                'return' => 'Users Listing',
                'code_snap' => 'BBUsers()'
            ],
            [
                'title' => 'User',
                'des' => 'This function provides User Details',
                'params' => 'id of user',
                'return' => 'User Details',
                'code_snap' => 'BBUser($id)'
            ]
        ]
    ],
    [
        'section' => 'Small Helpers',
        'functions' => [
            [
                'title' => 'Get User Name',
                'des' => 'This function provides given user\'s user name, If id given finds against that id else try to find logedin user',
                'params' => 'id|null',
                'return' => 'User Name',
                'code_snap' => 'BBGetUserName($id)'
            ],
            [
                'title' => 'Get User Avatar',
                'des' => 'This function provides given user\'s avatar, If id given finds against that id else try to find logedin user',
                'params' => 'id of user',
                'return' => 'User Avatar',
                'code_snap' => 'BBGetUserAvatar($id)'
            ],
            [
                'title' => 'Get User Cover',
                'des' => 'This function provides given user\'s cover, If id given finds against that id else try to find logedin user',
                'params' => 'id of user',
                'return' => 'User Avatar',
                'code_snap' => 'BBGetUserCover($id)'
            ],
            [
                'title' => 'Get User Role',
                'des' => 'This function provides given user\'s role, If id given finds against that id else try to find logedin user',
                'params' => 'id of user',
                'return' => 'User Role',
                'code_snap' => 'BBGetUserRole($id)'
            ],
            [
                'title' => 'Get User Email',
                'des' => 'This function provides given user\'s email, If id given finds against that id else try to find logedin user',
                'params' => 'id of user',
                'return' => 'User Email',
                'code_snap' => 'BBGetUserEmail($id|empty)'
            ],[
                'title' => 'Get User Join Date',
                'des' => 'This function provides given user\'s join date, If id given finds against that id else try to find logedin user',
                'params' => 'id of user',
                'return' => 'User Join Date',
                'code_snap' => 'BGetUserJoin($id|empty)'
            ]

        ]
    ],

];