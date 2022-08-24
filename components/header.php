<?php require 'config.php' ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/favicon.svg" type="image/x-icon">
    <title>Issue Tracker</title>
    <link rel="stylesheet" href="<?php echo $SiteURL ?>/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $SiteURL ?>/fonts/octicons/octicons.css">
    <link rel="stylesheet" href="<?php echo $SiteURL ?>/css/main.css">
</head>
<body>
    <i class="myoctions myocticons-issue-closed"></i>
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="myoct myoct-tasklist"></i> Issue Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link disabled">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="topic-header container-fluid pt-3">
        <div class="topic-headline px-3 px-md-4 px-lg-5">
            <i class="text-muted octicon octicon-repo"></i>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="http://">onissen</a></li>
                <li class="breadcrumb-item active" area-current="page"><a href="http://">issuetracker</a></li>
            </ol>
            <span class="badge rounded-pill label-muted-outline">Visibility Label</span>
        </div>
        <div class="clearfix"></div>
        <nav class="topic-nav px-3 px-md-4 px-lg-5">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled">Disabled</a>
                </li>
            </ul>
        </nav>
    </div>