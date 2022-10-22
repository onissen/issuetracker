<?php require 'config.php' ?>
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
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo $SiteURL ?>">
                <i class="myoct myoct-tasklist"></i> Issue Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="<?php echo $SiteURL ?>">Themenübersicht</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>