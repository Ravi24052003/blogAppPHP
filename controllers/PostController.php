<?php

class PostController {
    public static function create($title, $description, $user_id, $conn) {
        $query = "INSERT INTO blog_posts (user_id, title, description) VALUES ('$user_id', '$title', '$description')";
        $stmt = $conn->prepare($query);

        if ($stmt->execute()) {
            $query = "SELECT id, user_id, title, description FROM blog_posts WHERE user_id='$user_id'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
    
           $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
           echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["postNotCreated" => "Unable to create post."]);
        }
    }

    public static function readAll($conn) {
        $query = "
        SELECT blog_posts.id, blog_posts.user_id, blog_posts.title, blog_posts.description, 
               users.name AS user_name, users.email AS user_email
        FROM blog_posts
        JOIN users ON blog_posts.user_id = users.id
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
    }

    public static function readOwn($user_id, $conn){
        $query = "SELECT id, user_id, title, description FROM blog_posts WHERE user_id='$user_id'";
        $stmt = $conn->prepare($query);
        $stmt->execute();

       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

       echo json_encode($result);
    }

    public static function update($id, $title, $description, $user_id, $conn) {
        // First, check if the post belongs to the user
        $checkQuery = "SELECT * FROM blog_posts WHERE id='$id' AND user_id='$user_id'";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute();
    
        if ($checkStmt->rowCount() > 0) {
            // If the post belongs to the user, proceed with the update
            $query = "UPDATE blog_posts SET title='$title', description='$description' WHERE id='$id' AND user_id='$user_id'";
            $stmt = $conn->prepare($query);
    
            if ($stmt->execute()) {
                $query = "SELECT id, user_id, title, description FROM blog_posts WHERE user_id='$user_id'";
            $stmt = $conn->prepare($query);
            $stmt->execute();
    
           $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
           echo json_encode($result);

            } else {
                http_response_code(400);
                echo json_encode(["postNotUpdated" => "Unable to update post."]);
            }
        } else {
            // If the post does not belong to the user, return an error message
            http_response_code(401);
            echo json_encode(["cantUpdate" => "Unauthorized action. You can only update your own posts."]);
        }
    }
    

    public static function delete($id, $user_id, $conn) {
        // First, check if the post belongs to the user
        $checkQuery = "SELECT * FROM blog_posts WHERE id='$id' AND user_id='$user_id'";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->execute();
    
        if ($checkStmt->rowCount() > 0) {
            // If the post belongs to the user, proceed with the delete
            $query = "DELETE FROM blog_posts WHERE id='$id' AND user_id='$user_id'";
            $stmt = $conn->prepare($query);
    
            if ($stmt->execute()) {
                echo json_encode(["postDeleted" => "Post was deleted."]);
            } else {
                http_response_code(400);
                echo json_encode(["postNotDeleted" => "Unable to delete post."]);
            }
        } else {
            // If the post does not belong to the user, return an error message
            http_response_code(401);
            echo json_encode(["cantDelete" => "Unauthorized action. You can only delete your own posts."]);
        }
    }
    
}
?>
