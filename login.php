<?php
    $siteTitle = 'Login - IssueTracker'
?>
<?php require 'components/config.php' ?>
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
        <form>
            <img class="mb-4" src="<?php echo $SiteURL ?>/assets/favicon.svg" alt="Logo" width="72">
            <h1 class="mb-3 h2">Beim IssueTracker anmelden</h1>

            <div id="login-form">
                <div class="form-group">
                    <label for="InputUsername" class="form-label">Benutzername</label>
                    <input type="text" class="form-control" id="InputUsername" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="InputPassword" class="form-label">Passwort</label>
                    <input type="password" class="form-control" id="InputPassword" placeholder="Passwort">
                </div>
                <button class="w-100 btn btn-lg btn-success btn-sm" type="submit">Login</button>
            </div>
        </form>
        <p class="mt-3" id="backtoapp"><a href="<?php echo $SiteURL ?>">Zurück zum IssueTracker</a></p>
    </main>   
</body>