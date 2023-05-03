<?php

class ApiSession
{
    private static $token = null;
    static function set($token)
    {
        self::$token = $token;
    }

    static function get()
    {
        $conn = conn();
        $db   = new Database($conn);
        $user = $db->single('users',[
            'auth_token' => self::$token
        ]);

        return (new ArrayHelper(['user' => $user]))->toObject();
    }
}