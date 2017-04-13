<?php

namespace Akane\Core;

class Session
{
	public $storage = array();

    public static function isOpen()
    {
        return session_id() !== '';
    }

    public function open()
    {
        $this->configure();

        if (ini_get('session.auto_start') == 1) {
            session_destroy();
        }
        
        ini_set('session.gc_maxlifetime', '14400');

        session_name('KB_SID');
        session_start();

        $this->setStorage($_SESSION);
    }

    public function close()
    {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );

        session_unset();
        session_destroy();
    }

    private function configure()
    {
        session_set_cookie_params(
            3600,
            '/',
            null,
            false,
            true
        );

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.hash_function', '1');
        ini_set('session.hash_bits_per_character', 6);
        ini_set('session.entropy_file', '/dev/urandom');
        ini_set('session.entropy_length', '256');
    }

    public function setStorage(array &$storage)
    {
        $this->storage =& $storage;

        foreach ($storage as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getAll()
    {
        $session = get_object_vars($this);
        unset($session['storage']);

        return $session;
    }

    public function flush(){
        $session = get_object_vars($this);
        unset($session['storage']);
        
        foreach ($session as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}
