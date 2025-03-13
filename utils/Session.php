<?php

class Session
{
    public static function start()
    {
        session_start([
            "name" => "FRSID",
            "sid_length" => 128,
            "sid_bits_per_character" => 6,
        ]);
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }

    public static function setLifetime($lifetime)
    {
        session_set_cookie_params($lifetime);
        ini_set('session.gc_maxlifetime', $lifetime);
    }
}

Session::start();