<?php 
    $siteTitle = 'Issue Detail | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>
<?php include 'components/topic-header.php'; ?>

<?php
    $topicid = $info['tpid'];
    $issue_id = $endpoints[3];
    $sql = "SELECT issues.* FROM issues WHERE id = $issue_id AND tpid = $topicid";
    $issues = $db->query("SELECT issues.* FROM issues WHERE id = $issue_id AND tpid = $topicid")->fetch();
    $coment_average = $db->query("SELECT COUNT(sql_id)-1 AS average FROM comments WHERE issue_id = $issue_id; ")->fetchColumn();
    $comments = $db->query("SELECT * FROM comments WHERE issue_id = $issue_id ORDER BY date, issue_id")->fetchAll();
?>

<?php
    if (isset($_GET['newcomment'])) {
        $text = $_POST['text'];
        $date = date('Y-m-d');
        $sql_newComment = "INSERT INTO comments (issue_id, date, author, text) VALUES ($issue_id, '$date', 'user', '$text')";
        $stmt_new = $db->prepare($sql_newComment);
        if ($stmt_new->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }
?>

<div class="container container-xl my-4">
    <div class="issue-header">
        <div class="row">
            <h1 class="issue-headline col">
                <span class="title"><?php echo $issues['title'] ?></span>
                <span class="wght-light id">#<?php echo $issues['id'] ?></span>
            </h1>
            <div class="col text-end">
                <button class="btn btn-gh btn-sm">Bearbeiten</button>
                <!-- Onclick Bearbeiten... -->
                <a href="<?php echo $SiteURL.$endpoints[0].'/'.$endpoints[1].'/isssues'.'/new' ?>" class="btn btn-success btn-sm">Neuer Issue</a>
            </div>
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
            <?php } else { ?>
                <span class="badge label-badge rounded-pill text-bg-dark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill-rule="evenodd" d="M10.157 1.154a11.07 11.07 0 013.686 0 .75.75 0 01-.25 1.479 9.568 9.568 0 00-3.186 0 .75.75 0 01-.25-1.48zM6.68 3.205a.75.75 0 01-.177 1.046A9.558 9.558 0 004.25 6.503a.75.75 0 01-1.223-.87 11.058 11.058 0 012.606-2.605.75.75 0 011.046.177zm10.64 0a.75.75 0 011.046-.177 11.058 11.058 0 012.605 2.606.75.75 0 11-1.222.869 9.558 9.558 0 00-2.252-2.252.75.75 0 01-.177-1.046zM2.018 9.543a.75.75 0 01.615.864 9.568 9.568 0 000 3.186.75.75 0 01-1.48.25 11.07 11.07 0 010-3.686.75.75 0 01.865-.614zm19.964 0a.75.75 0 01.864.614 11.066 11.066 0 010 3.686.75.75 0 01-1.479-.25 9.56 9.56 0 000-3.186.75.75 0 01.615-.864zM3.205 17.32a.75.75 0 011.046.177 9.558 9.558 0 002.252 2.252.75.75 0 11-.87 1.223 11.058 11.058 0 01-2.605-2.606.75.75 0 01.177-1.046zm17.59 0a.75.75 0 01.176 1.046 11.057 11.057 0 01-2.605 2.605.75.75 0 11-.869-1.222 9.558 9.558 0 002.252-2.252.75.75 0 011.046-.177zM9.543 21.982a.75.75 0 01.864-.615 9.56 9.56 0 003.186 0 .75.75 0 01.25 1.48 11.066 11.066 0 01-3.686 0 .75.75 0 01-.614-.865z"></path>
                    </svg>
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
        <div class="col-9 activity">
            <?php foreach ($comments as $comment) { ?>
                <div class="card discussion-card">
                    <div class="card-header row">
                        <div class="col-11">
                            <b><?php echo $comment['author'] ?></b> <span>schrieb am <?php echo $comment['date'] ?></span>
                        </div>
                        <div class="actions col-1 text-end">
                            <!-- Per "Color Dropdown" -->
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo $comment['text']?>
                    </div>
                </div>
                <div class="path"></div>
            <?php } ?>

            <div id="discussion-contribute" class="mb-5">
                <div class="card discussion-card" id="new-comment">
                    <div class="card-header">
                        <b>Neuer Kommentar</b>
                    </div>
                    <div class="card-body">
                        <form action="?newcomment" method="post">
                            <textarea name="text" id="" class="form-control" placeholder="Lasse einen Kommentar da" required></textarea>
                            <div class="buttons text-end my-3">
                                <?php if ($issues['status'] == 'open') { ?>
                                    <div class="btn-group">
                                        <button class="btn btn-gh" type="button">
                                            <svg class="text-closed me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path>
                                                <path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                                            </svg>
                                            Als erledigt abschließen
                                        </button>
                                        <button class="btn btn-gh dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Options</span>
                                        </button>
                                        <ul class="dropdown-menu" id="close-dropdown">
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-closed me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path>
                                                    <path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                                                </svg>
                                                Als erledigt abschließen
                                            </button></li>
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-muted me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                                                </svg>
                                                Als nicht geplant abschließen
                                            </button></li>
                                        </ul>
                                    </div>
                                <?php } elseif ($issues['status'] = 'closed') { ?>
                                    <div class="btn-group">
                                        <button class="btn btn-gh" type="button">
                                            <svg class="text-open me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path>
                                                <path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path>
                                            </svg>
                                            Reaktivieren
                                        </button>
                                        <button class="btn btn-gh dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Options</span>
                                        </button>
                                        <ul class="dropdown-menu" id="close-dropdown">
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-open me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path>
                                                    <path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path>
                                                </svg>
                                                Issue reaktivieren
                                            </button></li>
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-muted me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                                                </svg>
                                                Als nicht geplant abschließen
                                            </button></li>
                                        </ul>
                                    </div>
                                <?php } elseif ($issues['status'] = 'notplanned') { ?>
                                    <div class="btn-group">
                                        <button class="btn btn-gh" type="button">
                                            <svg class="text-open me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path>
                                                <path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path>
                                            </svg>
                                            Reaktivieren
                                        </button>
                                        <button class="btn btn-gh dropdown-toggle dropdown-toggle-split" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Options</span>
                                        </button>
                                        <ul class="dropdown-menu" id="close-dropdown">
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-open me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path>
                                                    <path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path>
                                                </svg>
                                                Issue reaktivieren
                                            </button></li>
                                            <li><button class="dropdown-item d-flex flex-items-center py-2">
                                                <svg class="text-closed me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path>
                                                    <path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                                                </svg>
                                                Als erledigt abschließen
                                            </button></li>
                                        </ul>
                                    </div>
                                <?php } ?>
                                <button class="btn btn-success" type="submit">Kommentieren</button><!-- FIXME: disabled wenn keine Inhalte im Textfeld -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 sidebar">Test</div>
    </div>
</div>

<?php require 'components/footer.php' ?>