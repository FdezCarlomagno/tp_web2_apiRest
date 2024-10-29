<?php
    class JWT_middleware {
        public function run($req, $res) {
            if(!isset($_SERVER['HTTP_AUTHORIZATION'])){
                return;
            }

            $auth_header = $_SERVER['HTTP_AUTHORIZATION']; // "Bearer un.token.firma"
            $auth_header = explode(' ', $auth_header); // ["Bearer", "un.token.firma"]
            if(count($auth_header) != 2) {
                return;
            }
            if($auth_header[0] != 'Bearer') {
                return;
            }

            $jwt = $auth_header[1];
            $user = validateJWT($jwt);
            
            if(!$user){
                return;
            }

            $res->user = $user;
        }
    }