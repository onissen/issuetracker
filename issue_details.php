<?php
    $siteTitle = 'Issue Detail | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>
<?php include 'components/topic-header.php'; ?>

<?php
    $topicid = $info['tpid'];
    $issue_id = $endpoints[3];
    $issues = $db->query("SELECT issues.* FROM issues WHERE id = $issue_id AND tpid = $topicid")->fetch();
    $sql_id = $issues['sql_id'];
    $coment_average = $db->query("SELECT COUNT(sql_id)-1 AS average FROM comments WHERE issue_id = $sql_id; ")->fetchColumn();
    $comments = $db->query("SELECT * FROM comments WHERE issue_id = $sql_id ORDER BY date, issue_id")->fetchAll();
    $labels = $db->query("SELECT * FROM labels WHERE topicid = $topicid ORDER BY labelid")->fetchAll();

    $currentLabels = explode('..', $issues['label']);
    $sql_authors = "SELECT author FROM comments WHERE issue_id = $sql_id GROUP BY author";
    $comment_authors = $db->query("SELECT author FROM comments WHERE issue_id = $sql_id GROUP BY author")->fetchAll();


    if (isset($_POST['editTitle'])) {
        $id = $issues['sql_id'];
        $title = $_POST['editTitle'];
        $edit = $db->prepare("UPDATE issues SET title = '$title' WHERE sql_id = $id");
        if ($edit->execute()) {
            header("Refresh:0");
        }
    }

    if (isset($_POST['verify_delete']) AND $_POST['verify_delete'] == 1) {
        $id = $_POST['deleteID'];
        $sql_delete = "DELETE FROM comments WHERE issue_id = $id; DELETE FROM issues WHERE sql_id = $id";
        $delete_IssueData = $db->prepare($sql_delete);
        if ($delete_IssueData->execute()) {
            echo '<script type="text/JavaScript"> location = "'.$SiteURL.$endpoints[0].'/'.$endpoints[1].'/'.$endpoints[2].'/'.'";</script>';
        }
    }

    if (isset($_GET['setLabel'])) {
        $label = '';
        $array_keys = array_keys($_POST);
        $last_key = end($array_keys);
        foreach ($_POST as $key) {
            $label.= $key;
            if ($last_key != 'label'.$key) {
                $label.= '..';
            }
        }
        $setLabel = $db->prepare("UPDATE issues SET label = '$label' WHERE sql_id = $sql_id");
        if ($setLabel->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

?>


<div class="container container-xl my-4">
    <div class="issue-header">
        <div class="row issue-title-show">
            <h1 class="issue-headline col">
                <span class="title"><?php echo $issues['title'] ?></span>
                <span class="wght-light id">#<?php echo $issues['id'] ?></span>
            </h1>
            <div class="col text-end">
                <?php if (isset($_SESSION['role']) AND ($_SESSION['username'] == $issues['author'] OR $_SESSION['role'] == 'admin')) { ?>
                    <button class="btn btn-gh btn-sm" onclick="toggleIssueHeader('edit')">Bearbeiten</button>
                <?php } ?>
                <?php if (isset($_SESSION['role'])) { ?>
                    <a href="<?php echo $SiteURL.$endpoints[0].'/'.$endpoints[1].'/issues'.'/new' ?>" class="btn btn-success btn-sm">Neuer Issue</a>
                <?php } else { ?>
                    <a tabindex="0" class="btn btn-success btn-sm" role="button" data-bs-toggle="popover" data-bs-trigger="focus" 
                    data-bs-content="Bitte melde dich an, um einen neuen Issue zu eröffnen.">
                        Neuer Issue
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="issue-title-edit pb-3 row">
            <form action="" method="post" class="row">
                <div class="col-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control" value="<?php echo $issues['title'] ?>" autofocus="autofocus" autocomplete="off" name="editTitle">
                </div>
                <div class="col-2 col-md-2 col-sm-12 text-end">
                    <button type="submit" class="btn btn-gh me-2">Speichern</button>
                    <button type="button" class="btn-link btn" onclick="toggleIssueHeader('show')">Cancel</button>
                </div>
            </form>
        </div>
        <div class="header-meta">
            <?php if ($issues['status'] == 'open') { ?>
                <span class="badge label-badge rounded-pill badge-open">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill-rule="evenodd" d="M2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0zM12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zm0 13a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    Open
                </span>
            <?php } elseif ($issues['status'] == 'closed') { ?>
                <span class="badge label-badge rounded-pill badge-closed">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path d="M17.28 9.28a.75.75 0 00-1.06-1.06l-5.97 5.97-2.47-2.47a.75.75 0 00-1.06 1.06l3 3a.75.75 0 001.06 0l6.5-6.5z"></path>
                        <path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path>
                    </svg>
                    Closed
                </span>
            <?php } elseif ($issues['status'] == 'notplanned') { ?>
                <span class="badge label-badge rounded-pill badge-notplanned">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                    </svg>
                    Closed
                </span>
            <?php } else { ?>
                <span class="badge label-badge rounded-pill text-bg-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M6.749.097a8.054 8.054 0 012.502 0 .75.75 0 11-.233 1.482 6.554 6.554 0 00-2.036 0A.75.75 0 016.749.097zM4.345 1.693A.75.75 0 014.18 2.74a6.542 6.542 0 00-1.44 1.44.75.75 0 01-1.212-.883 8.042 8.042 0 011.769-1.77.75.75 0 011.048.166zm7.31 0a.75.75 0 011.048-.165 8.04 8.04 0 011.77 1.769.75.75 0 11-1.214.883 6.542 6.542 0 00-1.439-1.44.75.75 0 01-.165-1.047zM.955 6.125a.75.75 0 01.624.857 6.554 6.554 0 000 2.036.75.75 0 01-1.482.233 8.054 8.054 0 010-2.502.75.75 0 01.858-.624zm14.09 0a.75.75 0 01.858.624 8.057 8.057 0 010 2.502.75.75 0 01-1.482-.233 6.55 6.55 0 000-2.036.75.75 0 01.624-.857zm-13.352 5.53a.75.75 0 011.048.165 6.542 6.542 0 001.439 1.44.75.75 0 01-.883 1.212 8.04 8.04 0 01-1.77-1.769.75.75 0 01.166-1.048zm12.614 0a.75.75 0 01.165 1.048 8.038 8.038 0 01-1.769 1.77.75.75 0 11-.883-1.214 6.543 6.543 0 001.44-1.439.75.75 0 011.047-.165zm-8.182 3.39a.75.75 0 01.857-.624 6.55 6.55 0 002.036 0 .75.75 0 01.233 1.482 8.057 8.057 0 01-2.502 0 .75.75 0 01-.624-.858z"></path>
                    </svg>
                    Unknown
                </span>
            <?php } ?>

            <span class="info">
                <?php if ($issues['status'] == 'closed') { ?>
                    Von <b><a href="" class="author"><?php echo $issues['author'] ?></a></b>, eröffnet am <?php echo $issues['date_opened']; ?>, geschlossen am <?php echo $issues['date_closed'] ?>
                <?php } else { ?>
                    Von <b><a href="" class="author"><?php echo $issues['author'] ?></a></b>, eröffnet am <?php echo $issues['date_opened']; ?> | <?php echo $coment_average ?> Beiträge
                <?php } ?>
            </span>
        </div>
    </div>

    <div class="discussion-bucket row">
        <?php include 'components/comment-activity.php' ?>
        
        <div class="col-3 sidebar">
            <div class="sidebar-item">
                <div class="position-relative">
                    <div class="sidebar-heading d-flex"  onclick="toggleSidebarPopover('label')">
                        <h6 class="flex-fill">Labels</h6>
                        <?php if (isset($_SESSION['role'])) { ?>
                            <div class="flex-fill text-end">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M7.429 1.525a6.593 6.593 0 011.142 0c.036.003.108.036.137.146l.289 1.105c.147.56.55.967.997 1.189.174.086.341.183.501.29.417.278.97.423 1.53.27l1.102-.303c.11-.03.175.016.195.046.219.31.41.641.573.989.014.031.022.11-.059.19l-.815.806c-.411.406-.562.957-.53 1.456a4.588 4.588 0 010 .582c-.032.499.119 1.05.53 1.456l.815.806c.08.08.073.159.059.19a6.494 6.494 0 01-.573.99c-.02.029-.086.074-.195.045l-1.103-.303c-.559-.153-1.112-.008-1.529.27-.16.107-.327.204-.5.29-.449.222-.851.628-.998 1.189l-.289 1.105c-.029.11-.101.143-.137.146a6.613 6.613 0 01-1.142 0c-.036-.003-.108-.037-.137-.146l-.289-1.105c-.147-.56-.55-.967-.997-1.189a4.502 4.502 0 01-.501-.29c-.417-.278-.97-.423-1.53-.27l-1.102.303c-.11.03-.175-.016-.195-.046a6.492 6.492 0 01-.573-.989c-.014-.031-.022-.11.059-.19l.815-.806c.411-.406.562-.957.53-1.456a4.587 4.587 0 010-.582c.032-.499-.119-1.05-.53-1.456l-.815-.806c-.08-.08-.073-.159-.059-.19a6.44 6.44 0 01.573-.99c.02-.029.086-.075.195-.045l1.103.303c.559.153 1.112.008 1.529-.27.16-.107.327-.204.5-.29.449-.222.851-.628.998-1.189l.289-1.105c.029-.11.101-.143.137-.146zM8 0c-.236 0-.47.01-.701.03-.743.065-1.29.615-1.458 1.261l-.29 1.106c-.017.066-.078.158-.211.224a5.994 5.994 0 00-.668.386c-.123.082-.233.09-.3.071L3.27 2.776c-.644-.177-1.392.02-1.82.63a7.977 7.977 0 00-.704 1.217c-.315.675-.111 1.422.363 1.891l.815.806c.05.048.098.147.088.294a6.084 6.084 0 000 .772c.01.147-.038.246-.088.294l-.815.806c-.474.469-.678 1.216-.363 1.891.2.428.436.835.704 1.218.428.609 1.176.806 1.82.63l1.103-.303c.066-.019.176-.011.299.071.213.143.436.272.668.386.133.066.194.158.212.224l.289 1.106c.169.646.715 1.196 1.458 1.26a8.094 8.094 0 001.402 0c.743-.064 1.29-.614 1.458-1.26l.29-1.106c.017-.066.078-.158.211-.224a5.98 5.98 0 00.668-.386c.123-.082.233-.09.3-.071l1.102.302c.644.177 1.392-.02 1.82-.63.268-.382.505-.789.704-1.217.315-.675.111-1.422-.364-1.891l-.814-.806c-.05-.048-.098-.147-.088-.294a6.1 6.1 0 000-.772c-.01-.147.039-.246.088-.294l.814-.806c.475-.469.679-1.216.364-1.891a7.992 7.992 0 00-.704-1.218c-.428-.609-1.176-.806-1.82-.63l-1.103.303c-.066.019-.176.011-.299-.071a5.991 5.991 0 00-.668-.386c-.133-.066-.194-.158-.212-.224L10.16 1.29C9.99.645 9.444.095 8.701.031A8.094 8.094 0 008 0zm1.5 8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM11 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="menu-popover sidebarMenu-popover hideSidebarMenu" id="label-sidebarPopover">
                        <form class="popover-message shadow-lg" method="post" action="?setLabel">
                            <?php foreach($labels as $label) { ?>
                            <div class="menu-item d-flex" onclick="toggleCheck(<?php echo $label['labelid'] ?>)">
                                <input type="checkbox" name="label<?php echo $label['labelid'] ?>" value="<?php echo $label['labelid'] ?>" class="form-check-input" id="label<?php echo $label['labelid'] ?>" <?php if(in_array($label['labelid'], $currentLabels)) {echo 'checked';} ?> onclick="toggleCheck(<?php echo $label['labelid'] ?>)">
                                <div>
                                    <span class="badge label-badge rounded-pill" style="background-color: <?php echo $label['color'] ?>"><?php echo $label['name'] ?></span><br>
                                    <span class="text-muted text-small"><?php echo $label['description'] ?></span>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="text-center menu-footer">
                                <button type="submit" class="btn btn-sm btn-outline-success">Labels speichern</button>
                            </div>
                        </form>
                    </div>
                    <div class="issue-labels">
                        <?php 
                            if ($currentLabels[0] != '') {
                                foreach ($currentLabels as $key) {
                                    $label = $db->query("SELECT name, color FROM labels WHERE labelid = $key")->fetch();
                        ?>
                            <span class="badge label-badge rounded-pill me-1 mb-1" style="background-color: <?php echo $label['color'] ?>"><?php echo $label['name'] ?></span>
                        <?php }
                        } else { echo 'Bisher keine'; } ?>
                    </div>
                </div>
            </div>

            <div class="sidebar-item">
                <div class="position-relative">
                    <div class="sidebar-heading d-flex">
                        <h6 class="flex-fill">Personen</h6>
                    </div>
                    <div class="issue-persons d-flex">
                        <a class="person-chip" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'?search=author:'.$issues['author'] ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Issue-Autor">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M10.5 5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm.061 3.073a4 4 0 10-5.123 0 6.004 6.004 0 00-3.431 5.142.75.75 0 001.498.07 4.5 4.5 0 018.99 0 .75.75 0 101.498-.07 6.005 6.005 0 00-3.432-5.142z"></path>
                            </svg>
                            <span><?php echo $issues['author'] ?></span>
                        </a>
                        <?php foreach ($comment_authors as $author) {
                            if ($author['author'] != $issues['author']) { ?>
                            <a class="person-chip"  href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'?search=author:'.$author['author'] ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Comment-Autor">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M10.5 5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm.061 3.073a4 4 0 10-5.123 0 6.004 6.004 0 00-3.431 5.142.75.75 0 001.498.07 4.5 4.5 0 018.99 0 .75.75 0 101.498-.07 6.005 6.005 0 00-3.432-5.142z"></path>
                                </svg>
                                <span><?php echo $author['author'] ?></span>
                            </a>
                        <?php }} ?>
                    </div>
                </div>
            </div>

            <?php if ($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'manager' OR $_SESSION['username'] == $info['owner']) { ?>
                <div class="sidebar-item">
                    <button class="btn btn-link text-small" id="deleteIssue" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                            <path fill-rule="evenodd" d="M6.5 1.75a.25.25 0 01.25-.25h2.5a.25.25 0 01.25.25V3h-3V1.75zm4.5 0V3h2.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H5V1.75C5 .784 5.784 0 6.75 0h2.5C10.216 0 11 .784 11 1.75zM4.496 6.675a.75.75 0 10-1.492.15l.66 6.6A1.75 1.75 0 005.405 15h5.19c.9 0 1.652-.681 1.741-1.576l.66-6.6a.75.75 0 00-1.492-.149l-.66 6.6a.25.25 0 01-.249.225h-5.19a.25.25 0 01-.249-.225l-.66-6.6z"></path>
                        </svg>
                        Issue löschen
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered gh-modal">
        <div class="modal-content p-3 gh-modal">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="text-danger">
                    <path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12A9.5 9.5 0 0112 2.5c2.353 0 4.507.856 6.166 2.273L4.773 18.166A9.462 9.462 0 012.5 12zm3.334 7.227A9.462 9.462 0 0012 21.5a9.5 9.5 0 009.5-9.5 9.462 9.462 0 00-2.273-6.166L5.834 19.227z"></path>
                </svg>
                <div class="modal-message">
                    <h5 class="mt-4">Bist du sicher, dass du diesen Issue löschen möchtest?</h5>
                    <div class="mx-auto mt-1 mb-4">
                        <ul class="text-start">
                            <li>Dieser Schritt kann nicht rückgängig gemacht werden.</li>
                            <li>Nur Administratoren können Issues löschen.</li>
                            <li>Diese Aktion wird den Issue, die zugehörigen Commits und alle weiteren Daten überall entfernen.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <form action="<?php echo $SiteURL.$endpoints[0].'/'.$endpoints[1].'/'.$endpoints[2].'/'.$endpoints[3] ?>" method="post" class="delete-issue">
                <input type="hidden" name="deleteID" value="<?php echo $issues['sql_id'] ?>">
                <button type="submit" class="w-100 btn btn-danger" name="verify_delete" value="1">Diesen Issue <b>löschen</b></button>
            </form>
        </div>
    </div>
</div>
<?php require 'components/footer.php' ?>