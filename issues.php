<?php 
    $siteTitle = 'Issues | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php
    include 'components/topic-header.php';
?>

<?php
    $topicid = $info['tpid'];

    $sql_averages = "SELECT (SELECT COUNT(id) FROM issues WHERE status = 'open' AND tpid = $topicid) as issues_open, 
    (SELECT COUNT(id) FROM issues WHERE (status = 'closed' OR status = 'notplanned') AND tid = $topicid) as issues_closed, 
    (SELECT COUNT(labelid) FROM labels WHERE topicid = $topicid) as average_labels";

    $query_averages = $db->prepare($sql_averages);
    $query_averages->execute();
    $averages = $query_averages->fetch();

    function CommentsAmmount ($issue) {
        global $SiteURL;
        global $info;
        global $db;
        $current_issue = $issue['sql_id'];
        $sql_comment_average = "SELECT COUNT(sql_id) AS average FROM comments WHERE issue_id=$current_issue";
        $query_comments = $db->prepare($sql_comment_average);
        $query_comments->execute();
        $comment_average = $query_comments->fetch();

        if($comment_average['average'] > 1) { ?>
            <span class="comments-average">
                <a href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'/issues/'.$issue['id'] ?>" class="link-muted text-decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M2.75 2.5a.25.25 0 00-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 01.75.75v2.19l2.72-2.72a.75.75 0 01.53-.22h4.5a.25.25 0 00.25-.25v-7.5a.25.25 0 00-.25-.25H2.75zM1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0113.25 12H9.06l-2.573 2.573A1.457 1.457 0 014 13.543V12H2.75A1.75 1.75 0 011 10.25v-7.5z"></path>
                    </svg>
                    <?php echo $comment_average['average'] ?>
                </a>
            </span>
        <?php }
    }

    require 'components/search_function.php';

    $sql_labels = "SELECT labelid, name, color, description FROM labels WHERE topicid = $topicid";
    $result_labels = $db->query($sql_labels)->fetchAll();
    foreach ($result_labels as $key) {
        $lableData[$key['labelid']] = $key;
    }

    $authors = $db->query("SELECT author FROM issues WHERE tpid=$topicid GROUP BY author")->fetchAll();
?>

<div class="container container-xl mt-4">
    <div class="row searchbar">
        <div class="col-md-8 querybox">
            <div class="input-group">
                <span class="input-group-text form-control-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M11.5 7a4.499 4.499 0 11-8.998 0A4.499 4.499 0 0111.5 7zm-.82 4.74a6 6 0 111.06-1.06l3.04 3.04a.75.75 0 11-1.06 1.06l-3.04-3.04z"></path>
                    </svg>
                </span>
                <button class="btn btn-sm  btn-inputpart dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php 
                        if (isset($_REQUEST['filter'])) {
                            if ($_REQUEST['filter'] == '' OR $_REQUEST['filter'] == 'open') {echo 'Offene Issues';}
                            if ($_REQUEST['filter'] == 'closed') {echo 'Erledigte Issues';}
                            if ($_REQUEST['filter'] == 'all') {echo 'Alle Issues';}
                        } else {echo 'Offene Issues';}
                    ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?filter=all">Alle Issues</a></li>
                    <li><a class="dropdown-item" href="?filter=open">Offene Issues</a></li>
                    <li><a class="dropdown-item" href="?filter=closed">Erledigte Issues</a></li>
                </ul>
                <input type="text" name="search" id="searchbox" class="form-control form-control-sm" value="<?php if (isset($_REQUEST['search'])) {echo $_REQUEST['search'];} ?>" autocomplete="off" onkeyup="Search(event); SuggestType()" list="suggestType">
                <datalist id="suggestType">
                    <option class="suggest-option" id="query-option"><!-- von JS bef??llt --></option>
                    <option class="suggest-option" id="title-option"><!-- von JS bef??llt --></option>
                    <option class="suggest-option" id="status-option"><!-- von JS bef??llt --></option>
                    <option class="suggest-option" id="author-option"><!-- von JS bef??llt --></option>
                    <option class="suggest-option" id="id-option"><!-- von JS bef??llt --></option>
                </datalist>
            </div>
        </div>
        <div class="col-md-4 search-nav text-end">
            <div class="btn-group">
                <a href="<?php echo $SiteURL.$endpoints[0].'/'.$endpoints[1].'/labels' ?>" id="labels" class="btn btn-sm btn-outline-gh" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M2.5 7.775V2.75a.25.25 0 01.25-.25h5.025a.25.25 0 01.177.073l6.25 6.25a.25.25 0 010 .354l-5.025 5.025a.25.25 0 01-.354 0l-6.25-6.25a.25.25 0 01-.073-.177zm-1.5 0V2.75C1 1.784 1.784 1 2.75 1h5.025c.464 0 .91.184 1.238.513l6.25 6.25a1.75 1.75 0 010 2.474l-5.026 5.026a1.75 1.75 0 01-2.474 0l-6.25-6.25A1.75 1.75 0 011 7.775zM6 5a1 1 0 100 2 1 1 0 000-2z"></path>
                    </svg>
                     Labels 
                    <span class="badge badge-gh-nav rounded-pill"><?php echo $averages['average_labels'] ?></span>
                </a>
                <!-- <a href="<?php echo $_SERVER['REQUEST_URI'].'/milestones' ?>" id="milestones" class="btn btn-sm btn-outline-gh" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M7.75 0a.75.75 0 01.75.75V3h3.634c.414 0 .814.147 1.13.414l2.07 1.75a1.75 1.75 0 010 2.672l-2.07 1.75a1.75 1.75 0 01-1.13.414H8.5v5.25a.75.75 0 11-1.5 0V10H2.75A1.75 1.75 0 011 8.25v-3.5C1 3.784 1.784 3 2.75 3H7V.75A.75.75 0 017.75 0zm0 8.5h4.384a.25.25 0 00.161-.06l2.07-1.75a.25.25 0 000-.38l-2.07-1.75a.25.25 0 00-.161-.06H2.75a.25.25 0 00-.25.25v3.5c0 .138.112.25.25.25h5z"></path>
                    </svg>
                     Meilensteine 
                    <span class="badge badge-gh-nav rounded-pill">x</span>
                </a> -->
            </div>
            <?php if (isset($_SESSION['role'])) { ?>
            <a href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'/issues/new' ?>" class="btn btn-sm btn-success" role="button" id="new-issue">
                Neuer Issue
            </a>
            <?php } else { ?>
            <a tabindex="0" class="btn btn-success btn-sm" role="button" data-bs-toggle="popover" data-bs-trigger="focus" 
                data-bs-content="Bitte melde dich an, um einen neuen Issue zu er??ffnen.">
                    Neuer Issue
            </a>
            <?php } ?>
        </div>
    </div>
    <?php if (isset($_GET['search']) OR isset($_GET['filter'])) { ?>
        <div class="reset-searchquery mt-3">
            <a href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'] ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" id="resetQueryIcon">
                    <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path>
                </svg>
                Aktuelle Suchbegriffe und Filter zur??cksetzen
            </a>
        </div>
    <?php } ?>
    <div class="issuelist-wrapper mt-3">
        <div class="issuelist-header d-flex">
            <div class="me-3 d-none d-md-block flex-auto">                
                <input type="checkbox" name="select-all" id="select-all">
            </div>
            <div class="listnav row flex-auto w-100">
                <div class="d-none d-lg-block states col-md-3">
                    <a href="?filter=open" class="btn-link <?php if (isset($_REQUEST['filter'])) {if ($_REQUEST['filter'] == '' OR $_REQUEST['filter'] == 'open') {echo 'selected';}} else {echo 'selected';} ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path><path fill-rule="evenodd" d="M8 0a8 8 0 100 16A8 8 0 008 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z"></path>
                        </svg>
                        <?php if ($averages['issues_open'] > 0) {echo $averages['issues_open'].' Offen';} else {echo '0 Offen';} ?>
                    </a>
                    <a href="?filter=closed" class="btn-link <?php if($_REQUEST['filter'] == 'closed'){echo 'selected';} ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                            <path fill-rule="evenodd" d="M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 01-1.06 0L2.22 9.28a.75.75 0 011.06-1.06L6 10.94l6.72-6.72a.75.75 0 011.06 0z"></path>
                        </svg>
                        <?php if ($averages['issues_closed'] > 0) {echo $averages['issues_closed'].' Erledigt';} else {echo '0 Erledigt';} ?>
                    </a>
                </div>
                <div class="col-md-9 d-flex no-wrap justify-content-between justify-content-sm-start justify-content-lg-end action-menu">
                    <div class="filterbar-item" id="author">                       
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Autor</a>
                        <ul class="dropdown-menu">
                            <?php foreach ($authors as $author) { ?>
                                <li>
                                    <a class="dropdown-item" href="?search=author:<?php echo $author['author'] ?>">
                                        <?php echo $author['author'] ?>
                                    </a>
                                </li>
                                <?php } ?>
                        </ul>
                    </div>
                    <div class="filterbar-item" id="label">                       
                            <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Label</a>
                            <ul class="dropdown-menu">
                                <?php foreach ($result_labels as $label) { ?>
                                    <li>
                                        <a class="dropdown-item" href="?search=label:<?php echo $label['labelid'] ?>">
                                            <span class="badge label-badge rounded-pill" style="background-color: <?php echo $label['color'] ?>"><?php echo $label['name'] ?></span><br>
                                            <span class="text-muted text-small"><?php echo $label['description'] ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                    </div>
                    <!-- <div class="filterbar-item" id="project">                       
                        <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Projekte</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Foreach Project</a></li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div> <!-- .issuelist-header -->
        <div class="issuelist-list" id="issuelist-list">
            <?php if ($searchResults > 0) { ?>
                <?php foreach ($result_issues as $issue) { ?>
                    <div id="<?php echo $issue['sql_id'] ?>" class="issuelist-item d-flex">
                        <label for="check-item" class="checklabel-item py-2 ps-3">
                            <input type="checkbox" name="select-<?php echo $issue['sql_id'] ?>" class="check-item" value="<?php echo $issue['sql_id'] ?>">
                        </label>
                        <div class="issue-status pt-2 ps-3">
                            <?php if ($issue['status'] == 'open') { ?>
                                <svg class="text-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                    <path fill-rule="evenodd" d="M8 0a8 8 0 100 16A8 8 0 008 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z"></path>
                                </svg>
                            <?php } elseif ($issue['status'] == 'closed') { ?>
                                <svg class="text-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path>
                                    <path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                                </svg>
                            <?php } elseif ($issue['status'] == 'notplanned') { ?>
                                <svg class="text-notplanned" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                                </svg>
                            <?php } else { ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M6.749.097a8.054 8.054 0 012.502 0 .75.75 0 11-.233 1.482 6.554 6.554 0 00-2.036 0A.75.75 0 016.749.097zM4.345 1.693A.75.75 0 014.18 2.74a6.542 6.542 0 00-1.44 1.44.75.75 0 01-1.212-.883 8.042 8.042 0 011.769-1.77.75.75 0 011.048.166zm7.31 0a.75.75 0 011.048-.165 8.04 8.04 0 011.77 1.769.75.75 0 11-1.214.883 6.542 6.542 0 00-1.439-1.44.75.75 0 01-.165-1.047zM.955 6.125a.75.75 0 01.624.857 6.554 6.554 0 000 2.036.75.75 0 01-1.482.233 8.054 8.054 0 010-2.502.75.75 0 01.858-.624zm14.09 0a.75.75 0 01.858.624 8.057 8.057 0 010 2.502.75.75 0 01-1.482-.233 6.55 6.55 0 000-2.036.75.75 0 01.624-.857zm-13.352 5.53a.75.75 0 011.048.165 6.542 6.542 0 001.439 1.44.75.75 0 01-.883 1.212 8.04 8.04 0 01-1.77-1.769.75.75 0 01.166-1.048zm12.614 0a.75.75 0 01.165 1.048 8.038 8.038 0 01-1.769 1.77.75.75 0 11-.883-1.214 6.543 6.543 0 001.44-1.439.75.75 0 011.047-.165zm-8.182 3.39a.75.75 0 01.857-.624 6.55 6.55 0 002.036 0 .75.75 0 01.233 1.482 8.057 8.057 0 01-2.502 0 .75.75 0 01-.624-.858z"></path>
                                </svg>
                            <?php } ?>
                        </div>
                        <div class="issuelist-content p-2 pe-3 pe-md-2 w-100">
                            <a class="title-link" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'/issues/'.$issue['id'] ?>" 
                            id="issuelink_<?php echo $issue['sql_id'] ?>"><?php echo $issue['title'] ?></a>
                            <?php
                                $lableIDs = explode('..', $issue['label']);
                                if ($lableIDs[0] != '') {
                                    foreach ($lableIDs as $id) {
                                        if ($id == $lableData[$id]['labelid']) { ?>
                                            <a href="?search=label:<?php echo $lableData[$id]['labelid'] ?>" class="badge label-badge rounded-pill" style="background-color: <?php echo $lableData[$id]['color'] ?>"><?php echo $lableData[$id]['name'] ?></a>
                                        <?php }
                                    }
                                }
                            ?>
                            <br>
                            <span class="text-small issuelist-meta">
                                <?php if ($issue['status'] == 'closed' OR $issue['status'] == 'notplanned') { ?>
                                    <span class="id">#<?php echo $issue['id'] ?></span> von <a href="" class="link-muted author"><?php echo $issue['author'] ?></a> wurde am <?php echo $issue['date_closed'] ?> geschlossen
                                <?php } else { ?>
                                    <span class="id">#<?php echo $issue['id'] ?></span> am <?php echo $issue['date_opened']; ?> er??ffnet von <a href="" class="link-muted author"><?php echo $issue['author'] ?></a>
                                <?php } ?>
                            </span>
                        </div>
                        <div class="issuelist-stats col-4 col-md-3 pt-2 pe-3 text-end hide-sm">
                            <?php CommentsAmmount($issue); ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div id="issuelist-noresults" class="container-md">
                    <div id="noresult-message">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill-rule="evenodd" d="M2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0zM12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zm0 13a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        <?php if (isset($_REQUEST['search'])) { ?>
                            <h3>Keine passenden Issues</h3>
                            <p>Versuche einen anderen Suchbegriff.</p>
                        <?php } else { ?>
                            <h3>Hier landen die Issues</h3>
                            <p>Issues werden genutzt um Todos, Bugs, Feature-Anfragen und mehr zu dokumentieren.</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div> <!-- issuelist-list -->        
    </div> <!-- .issuelist-wrapper -->

    <div class="footer-notice mt-3 text-muted text-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
            <path fill-rule="evenodd" d="M8 1.5c-2.363 0-4 1.69-4 3.75 0 .984.424 1.625.984 2.304l.214.253c.223.264.47.556.673.848.284.411.537.896.621 1.49a.75.75 0 01-1.484.211c-.04-.282-.163-.547-.37-.847a8.695 8.695 0 00-.542-.68c-.084-.1-.173-.205-.268-.32C3.201 7.75 2.5 6.766 2.5 5.25 2.5 2.31 4.863 0 8 0s5.5 2.31 5.5 5.25c0 1.516-.701 2.5-1.328 3.259-.095.115-.184.22-.268.319-.207.245-.383.453-.541.681-.208.3-.33.565-.37.847a.75.75 0 01-1.485-.212c.084-.593.337-1.078.621-1.489.203-.292.45-.584.673-.848.075-.088.147-.173.213-.253.561-.679.985-1.32.985-2.304 0-2.06-1.637-3.75-4-3.75zM6 15.25a.75.75 0 01.75-.75h2.5a.75.75 0 010 1.5h-2.5a.75.75 0 01-.75-.75zM5.75 12a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z"></path>
        </svg>
        <span>Die Suche nach Lables funktioniert nicht ??ber den Namen, sondern nur ??ber die ID. W??hle am besten einfach ein Label aus der Filter-Liste.</span>
    </div>
</div>

<?php require 'components/footer.php' ?>