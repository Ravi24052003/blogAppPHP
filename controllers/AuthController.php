<?php

class AuthController{
    public static function signup($name, $email, $password, $conn){
        $hash = password_hash($password, PASSWORD_BCRYPT);
    
        // Check if email already exists
        $query = "SELECT id FROM users WHERE email = '$email'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["emailErr" => "Email already exists."]);
        } else {
            // Insert new user
            $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hash')";
            $stmt = $conn->prepare($query);

            $result = $stmt->execute();

            if ($result) {
            $query = "SELECT id FROM users WHERE email = '$email'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $result['id'];

           $token = base64_encode($userId . ':' . time());

                echo json_encode(["registrationSuccess" => "User registered successfully", "token"=>$token]);
            } else {
                http_response_code(400);
                echo json_encode(["registrationErr" => "Unable to register user."]);
            }
        }
    }

    public static function login($email, $password, $conn){
        $query = "SELECT id, password FROM users WHERE email = '$email'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $result['password'])) {
               
                $userId = $result['id'];

                $token = base64_encode($userId . ':' . time());

                echo json_encode(["loginSuccess" => "Login successful.", "token"=>$token]);
            } else {
                http_response_code(400);
                echo json_encode(["invalidPassword" => "Invalid password."]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["notFound" => "Email not found."]);
        }
    }


    public static function authCheck($conn){
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
          $token = $headers['Authorization'];
          $decodedToken = base64_decode($token);
          $arr = explode( ':', $decodedToken);
          $userId = $arr[0];
          $query = "SELECT * FROM users WHERE id = '$userId'";
          $stmt = $conn->prepare($query);
          $stmt->execute();
          if($stmt->rowCount() > 0){
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // echo json_encode($result);

            return $result['id'];
          }
          else{
            // http_response_code(401);
            // echo json_encode(["message"=>"Invalid token"]);
            return false;
          }
      
        }
        else{
            // http_response_code(401);
            // echo json_encode(["message"=>"No Authorization header is present in the request"]);
            return false;
        }
    }
}

?>