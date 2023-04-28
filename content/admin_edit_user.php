<?php include("../includes/init.php");?>
<?php
    // if (isset($_GET['id'])) {
    //     $user_id = $_GET['id'];
    //     if (count_field_val($pdo, "users", "id", $user_id)>0){
    //         $row=return_field_data($pdo, "users", "id", $user_id);
    //         $fname = $row['firstname'];
    //         $lname = $row['lastname'];
    //         $comments = $row['comments'];
    //     } else {
    //         redirect('admin.php');
    //     }
    // } else {
    //     redirect('admin.php');
    // }
?>
<?php 
    if (logged_in()) {
        $username=$_SESSION['username'];
        if (!verify_user_group($pdo, $username, "Admin")) {
            if ($username!=$row['username']) {
                set_msg("User '{$username}' does not have permission to view this page");
                redirect('../index.php');
            }
        }
    } else {
        set_msg("Please log-in and try again");
//        redirect('../index.php');
    } 
?>
<?php
    if ($_SERVER['REQUEST_METHOD']=="POST") {
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $comments = $_POST['comments'];
        
        if (strlen($lname)<3) {
            $error[] = "Last name must be at least 3 characters long";
        }
        
        if (!isset($error)) {
            try {
                $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, comments=:comments WHERE id=:id";
                $stmnt = $pdo->prepare($sql);
                $user_data = [':firstname'=>$fname, ':lastname'=>$lname, ':comments'=>$comments, ':id'=>$user_id];
                $stmnt->execute($user_data);
                redirect('admin.php');
            } catch(PDOException $e) {
                echo "Error: ".$e->getMessage();
            }
        }
        
    } 
?>
<!DOCTYPE html>
<html lang="en">
    <?php include "../includes/header.php" ?>
    <body>
        <?php include "../includes/nav.php" ?>

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3">
                    <?php 
                        show_msg();
                        if (isset($error)) {
                            foreach ($error as $msg) {
                                echo "<h4 class='bg-danger text-center'>{$msg}</h4>";
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="register-form" method="post" role="form" >
                                        <div class="form-group">
                                            <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="First Name" value="<?php echo $fname ?>" required >
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="lastname" id="lastname" tabindex="2" class="form-control" placeholder="Last Name" value="<?php echo $lname ?>" required >
                                        </div>
                                        <div class="form-group">
                                            <textarea name="comments" id="comments" tabindex="7" class="form-control"  placeholder="Comments"><?php echo $comments ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="update-submit" id="update-submit" tabindex="4" class="form-control btn btn-custom" value="Update User">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../includes/footer.php" ?>
    </body>
</html>