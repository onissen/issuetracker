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


<div class="container container-xl my-4">
    <div class="issue-header">
        <div class="row">
            <h1 class="issue-headline col">
                <span class="title"><?php echo $issues['title'] ?></span>
                <span class="wght-light id">#<?php echo $issues['id'] ?></span>
            </h1>
            <div class="col text-end">
                <button class="btn btn-gh btn-sm">Bearbeiten</button>
                <!-- TODO: Onclick Bearbeiten... -->
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
            <?php } elseif ($issues['status'] == 'notplanned') { ?>
                <span class="badge label-badge rounded-pill badge-notplanned">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                    </svg>
                    Closed
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
        <div class="col-3 sidebar">Test</div>
    </div>
</div>

<?php require 'components/footer.php' ?>