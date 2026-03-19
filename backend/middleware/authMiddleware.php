<?php
session_start();

class AuthMiddleware {

    public static function check(){

        if(!isset($_SESSION['user_id'])){

            http_response_code(401);

            echo json_encode([
                "success"=>false,
                "message"=>"Unauthorized. Please login."
            ]);

            exit;
        }

    }

}