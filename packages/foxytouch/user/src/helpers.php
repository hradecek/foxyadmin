<?php

if (! function_exists('print_users_roles')) {
    function print_users_roles($user, $separator = ' / ')
    {
        return implode($separator, array_column($user->groups->toArray(), 'name'));
    }
}

if (! function_exists('user_profile_picture_uri')) {
    function user_profile_picture_uri($user)
    {
        $path = isset($user) && $user->profile_picture_uri ?
            $user->profile_picture_uri :
            config()->get('users.default_picture');
        return asset($path, 'user');
    }
}
