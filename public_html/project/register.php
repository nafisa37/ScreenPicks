<?php
require(__DIR__ . "/../../partials/nav.php");
reset_session();
$email = se($_POST, "email", "", false);
$username = se($_POST, "username", "", false);
?>
<!-- <form onsubmit="return validate(this)" method="POST">
    <div>
        <label for="email">Email</label>
        <input type="text" name="email" value="<?php se($email); ?>" required />
    </div>
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" value="<?php se($username); ?>" required />
    </div>
    <div>
        <label for="pw">Password</label>
        <input type="password" id="pw" name="password" required />
    </div>
    <div>
        <label for="confirm">Confirm</label>
        <input type="password" name="confirm" required />
    </div>
    <input type="submit" value="Register" />
</form> -->
<div class="container-fluid">
<form onsubmit="return validate(this)" method="POST">
    <?php render_input(["type"=>"email", "id"=>"email", "name"=>"email", "label"=>"Email", "rules"=>["required"=>true]]);?>
    <?php render_input(["type"=>"text", "id"=>"username", "name"=>"username", "label"=>"Username", "rules"=>["required"=>true, "maxlength"=>30]]);?>
    <?php render_input(["type"=>"password", "id"=>"password", "name"=>"password", "label"=>"Password", "rules"=>["required"=>true, "minlength"=>8]]);?>
    <?php render_input(["type"=>"password", "id"=>"confirm", "name"=>"confirm", "label"=>"Confirm Password", "rules"=>["required"=>true,"minlength"=>8]]);?>
    <?php render_button(["text"=>"Register", "type"=>"submit"]);?>
</form>
</div>
<script>
    //na569, 4/1/24
    function validate(form) {
        //TODO 1: implement JavaScript validation
        //ensure it returns false for an error and true for success

        let email = form.email.value;
        let username = form.username.value;
        let password = form.password.value;
        let confirmpassword = form.confirm.value;
        let isValid = true;

        let validEmail = /^([a-z0-9_\.\+-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
        let validUsername = /^[a-zA-Z0-9_-]{3,16}$/;

        if (email.length < 1) {
            flash("[Client] Email cannot be empty", "warning");
            isValid = false;
        }
        if (username.length < 1) {
            flash("[Client] Username cannot be empty", "warning");
            isValid = false;
        }
        if (password.length < 1) {
            flash("[Client] Password cannot be empty", "warning");
            isValid = false;
        }
        if (confirmpassword.length < 1) {
            flash("[Client] Confirm password cannot be empty", "warning");
            isValid = false;
        }
        if (!validEmail.test(email)) {
            flash("[Client] Invalid email address", "warning");
            isValid = false;
        }
        if (!validUsername.test(username)) {
            flash("[Client] Username must only contain 3-16 characters a-z, 0-9, _, or -", "warning");
            isValid = false;
        }
        if (password.length < 8) {
            flash("[Client] Password must be at least 8 characters", "warning");
            isValid = false;
        }
        if (password !== confirmpassword) {
            flash("[Client] Passwords must match", "warning");
            isValid = false;
        }
        return isValid;
        //na569,4/1/24

    }
</script> 
<?php
//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"]) && isset($_POST["username"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $confirm = se($_POST, "confirm", "", false);
    $username = se($_POST, "username", "", false);
    //TODO 3
    $hasError = false;
    if (empty($email)) {
        flash("Email must not be empty", "danger");
        $hasError = true;
    }
    //sanitize
    $email = sanitize_email($email);
    //validate, na569, 4/1/24
    if (!is_valid_email($email)) {
        flash("Invalid email address", "danger");
        $hasError = true;
    }
    if (!is_valid_username($username)) {
        flash("Username must only contain 3-16 characters a-z, 0-9, _, or -", "danger");
        $hasError = true;
    }
    if (empty($password)) {
        flash("password must not be empty", "danger");
        $hasError = true;
    }
    if (empty($confirm)) {
        flash("Confirm password must not be empty", "danger");
        $hasError = true;
    }
    if (!is_valid_password($password)) {
        flash("Password too short", "danger");
        $hasError = true;
    }
    if (
        strlen($password) > 0 && $password !== $confirm
    ) {
        flash("Passwords must match", "danger");
        $hasError = true;
    }
    if (!$hasError) {
        //TODO 4
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (email, password, username) VALUES(:email, :password, :username)");
        try {
            $stmt->execute([":email" => $email, ":password" => $hash, ":username" => $username]);
            flash("Successfully registered!", "success");
        } catch (PDOException $e) {
            users_check_duplicate($e->errorInfo);
        }
    } //na569, 4/1/24
}
?>
<?php
require(__DIR__ . "/../../partials/flash.php");
?>