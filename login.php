<?php
    $siteTitle = 'Login - IssueTracker'
?>
<?php require 'components/config.php' ?>

<?php



    if (isset($_GET['authenticate'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $db_pws->query("SELECT pws_users.username, pws_users.password, pws_users.name, pws_userroles.role_issues 
        FROM pws_users LEFT JOIN pws_userroles ON pws_users.id = pws_userroles.user_id WHERE pws_users.username = '$username'");
        $userExists = $stmt->fetch_assoc();

        $passwordDehashed = openssl_decrypt($userExists['password'], 'AES-128-ECB', SECRETKEY);
        if ($password != $passwordDehashed) {
            $Alert = 'Das Passwort ist falsch';
            $AlertTheme = 'warning';
        } else {
            $_SESSION['username'] = $userExists['username'];
            $_SESSION['name'] = $userExists['name'];
            $_SESSION['role'] = $userExists['role_issues'];
            echo '<script type="text/JavaScript"> location="'.$SiteURL.'"</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo $SiteURL ?>/assets/favicon.svg" type="image/x-icon">
    <title><?php if(isset($siteTitle)){echo $siteTitle;} else {echo 'Issue Tracker';} ?></title>
    <link rel="stylesheet" href="<?php echo $SiteURL ?>/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $SiteURL ?>/css/main.css">
</head>

<body class="text-center login-body">
    <main class="w-100 m-auto" id="signin-main">
        <form method="post" action="?authenticate">
            <img class="mb-4" src="<?php echo $SiteURL ?>/assets/favicon.svg" alt="Logo" width="72">
            <h1 class="mb-3 h2">Beim IssueTracker anmelden</h1>

            <?php if (isset($Alert)) { ?>
            <div class="mb-4 alert alert-<?php echo $AlertTheme ?>" role="alert">
                <?php echo $Alert ?>
            </div>
            <?php } ?>


            <div id="login-form">
                <div class="form-group">
                    <label for="InputUsername" class="form-label">Benutzername</label>
                    <input type="text" class="form-control" id="InputUsername" placeholder="Beutzername" name="username">
                </div>
                <div class="form-group">
                    <label for="InputPassword" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="InputPassword" placeholder="Passwort" name="password">
                </div>
                <button class="w-100 btn btn-lg btn-success btn-sm" type="submit" id="submit-login">Login</button>
            </div>
        </form>
        <p class="mt-3" id="backtoapp"><a href="<?php echo $SiteURL ?>">Zurück zum IssueTracker</a></p>
    </main>   
</body>