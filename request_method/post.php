<?php

if(isset($_POST['controller'])){
    $controller = $_POST['controller'];
   
    if($controller == "signup"){
        if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])){
           AuthController::signup($_POST['name'], $_POST['email'], $_POST['password'], $conn);
        }
        else{
            http_response_code(400);
            echo json_encode(["signupFieldsReqErr"=>"Name, email and password fields are required"]);
        }
    }
    elseif($controller == "login"){
        if(isset($_POST['email']) && isset($_POST['password'])){
            AuthController::login($_POST['email'], $_POST['password'], $conn);
         }
         else{
             http_response_code(400);
             echo json_encode(["loginFieldsReqErr"=>"Email and password field is required"]);
         }
    }
    elseif($controller == "create"){
      $isAuth = AuthController::authCheck($conn);

      if($isAuth){
        if(isset($_POST['title']) && isset($_POST['description'])){
            PostController::create($_POST['title'], $_POST['description'], $isAuth, $conn);
         }
         else{
             http_response_code(400);
             echo json_encode(["postsFieldsReqErr"=>"Title and description field is required"]);
         }
      }
      else{
        http_response_code(401);
        echo json_encode(["authenticated"=>false]);
      }
    }
    elseif($controller == "update"){
        $isAuth = AuthController::authCheck($conn);
  
        if($isAuth){
          if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['id'])){
              PostController::update($_POST['id'], $_POST['title'], $_POST['description'], $isAuth, $conn);
           }
           else{
               http_response_code(400);
               echo json_encode(["postsFieldsReqErr"=>"Id, title and description field is required"]);
           }
        }
        else{
          http_response_code(401);
          echo json_encode(["authenticated"=>false]);
        }
      }
      elseif($controller == "delete"){
        $isAuth = AuthController::authCheck($conn);
  
        if($isAuth){
          if(isset($_POST['id'])){
              PostController::delete($_POST['id'], $isAuth, $conn);
           }
           else{
               http_response_code(400);
               echo json_encode(["idReqErr"=>"Id field is required"]);
           }
        }
        else{
          http_response_code(401);
          echo json_encode(["authenticated"=>false]);
        }
      }
      elseif($controller == "authCheck"){
        $isAuth = AuthController::authCheck($conn);
  
        if($isAuth){
        echo json_encode(["authenticated"=> true]);
        }
        else{
          http_response_code(401);
          echo json_encode(["authenticated"=>false]);
        }
      }
      else{
        http_response_code(400);
        echo json_encode(["invalidController"=>"please provide valid controller name in the request body"]);
      }
      

}
else{
    http_response_code(400);
    echo json_encode(["controllerNotFoundErr"=>"controller field not found in the request body"]);
}

?>