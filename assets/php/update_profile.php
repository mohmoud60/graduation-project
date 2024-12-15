<?php
session_start();
include 'connection.php';
include 'authenticator.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $conn->prepare('UPDATE employee SET Employee_FullName = :Employee_FullName, Employee_Email = :Employee_Email, Employee_Phone = :Employee_Phone, Employee_Address = :Employee_Address WHERE Employee_ID = :Employee_ID');
        $stmt->execute(array(
            ':Employee_FullName' => $_POST['PFullName'],
            ':Employee_Email' => $_POST['Pemail'],
            ':Employee_Phone' => $_POST['Pphone'],
            ':Employee_Address' => $_POST['Paddress'],
            ':Employee_ID' => $_SESSION['employee_id'] // assuming you have employee id in session
        ));

        // Check if the form was submitted and file data was sent
        if(isset($_FILES['avatar'])) {
            // Get the extension of the uploaded file
            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

            // Construct the new name for the image
            $newName = $_SESSION['employee_id'] . '.' . $extension;

            // Path to upload the image
            $target_dir = "../media/employee/";
            $target_file = $target_dir . $newName;
            $server_dir = "assets/media/employee/";
            $server_file = $server_dir . $newName;
            // Try to move the uploaded file to the target location
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                echo "The file has been uploaded.";

                // Update the database with the new avatar path
                $stmt = $conn->prepare('UPDATE employee SET avatar_path = :avatar_path WHERE Employee_ID = :Employee_ID');
                $stmt->execute(array(
                    
                    ':avatar_path' => $server_file,
                    ':Employee_ID' => $_SESSION['employee_id']
                ));
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        if(isset($_POST['avatar_remove']) && $_POST['avatar_remove'] == 'true') {
            // Get the current avatar path from the database
            $stmt = $conn->prepare('SELECT avatar_path FROM employee WHERE Employee_ID = :Employee_ID');
            $stmt->execute(array(':Employee_ID' => $_SESSION['employee_id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $avatar_path = $row['avatar_path'];
            $target_dir = "../media/employee/";
            $target_file = $target_dir . $_SESSION['employee_id'];
                    
            if (file_exists($target_file . '.jpg')) {
                unlink($target_file . '.jpg');
            } elseif (file_exists($target_file . '.png')) {
                unlink($target_file . '.png');
            } // Add as many conditions as you need to cover all file types


            // Update the database to remove the avatar path
            $stmt = $conn->prepare('UPDATE employee SET avatar_path = NULL WHERE Employee_ID = :Employee_ID');
            $stmt->execute(array(':Employee_ID' => $_SESSION['employee_id']));
        }
    } catch(PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
