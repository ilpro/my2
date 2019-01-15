<?php

class Auth {

    
    public static function checkAuth( $redirect=true ){
        global $ini;
        if (!isset($_SESSION['user']['role'])) {
            if( isset($_COOKIE['email']) ) {
                $user = new User(Clear::dataS($_COOKIE['email']));
                $log = $user->login2(Clear::dataS($_COOKIE['pass']), Clear::dataS($_COOKIE['salt']), Clear::dataS($_COOKIE['iteration']));
                if($log['login']=='notExists'){
                    unset($_SESSION['user']);
                    unset($_COOKIE['email']);
                    unset($_COOKIE['pass']);
                    unset($_COOKIE['salt']);
                    unset($_COOKIE['iteration']);
                    setcookie('email', '', time()-1,'/');
                    setcookie('pass', '', time()-1,'/');
                    setcookie('salt', '', time()-1,'/');
                    setcookie('iteration', '', time()-1,'/');
//                    header("Location: " . $ini['url.admin'] );
//                    exit();
                }

                if( $redirect ) {
                    header("Location: " . $ini['url.admin']);
                    exit();
                }
            }

        }


    }

}