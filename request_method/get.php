<?php

if(isset($_GET['controller'])){
    $controller = $_GET['controller'];
   
    if($controller == "readAll"){
        $isAuth = AuthController::authCheck($conn);

      if($isAuth){
        PostController::readAll($conn); 
      }
      else{
        http_response_code(401);
        echo json_encode(["authenticated"=>false]);
      }
    }
      else if($controller == "readOwn"){
        $isAuth = AuthController::authCheck($conn);

      if($isAuth){
        PostController::readOwn($isAuth, $conn); 
      }
      else{
        http_response_code(401);
        echo json_encode(["authenticated"=>false]);
      }
    }
    else{
      http_response_code(400);
      echo json_encode(["invalidController"=>"Please provide valid controller name in the query string"]);
    }
    
}
else{
    http_response_code(400);
    echo json_encode(["controllerNotFound"=>"controller field not found in the query string"]);
}

?>