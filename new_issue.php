
<?php 
    $siteTitle = 'Neuer Issue | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>
<?php include 'components/topic-header.php'; ?>

<?php
    $topicid = $info['tpid'];

    if (isset($_GET['newIssue'])) {
        $title = $_POST['title'];
        $status = 'open';
        $date_opened = date('Y-m-d');
        $author = 'user';
        $text = $_POST['text'];
        $action = $_POST['action'];
        $lastid = $db->query("SELECT MAX(id) AS lastid FROM issues WHERE tpid = $topicid")->fetch();
        $innerid = $lastid['lastid']+1;
        echo 'Elefant';
        print_r($_POST);
        echo $topicid;
        echo $innerid;
        echo $date_opened;

        $newIssue = $db->prepare("INSERT INTO issues(tpid, id, title, status, date_opened, author) VALUES ($topicid, $innerid, '$title', '$status', '$date_opened', '$author')");
        $newIssue->execute();
        $sql_id = $db->query("SELECT sql_id FROM issues WHERE id = $innerid AND tpid = $topicid")->fetch();
        $issue_id = $sql_id['sql_id'];

        $insertComment = $db->prepare("INSERT INTO comments(issue_id, date, author, text, action) VALUES($issue_id, '$date_opened', '$author', '$text', '$action')");
        if ($insertComment->execute()) {
            echo '<script type="text/JavaScript"> location="'.$SiteURL.$info['channel'].'/'.$info['topic'].'/issues/'.$innerid.'"</script>';
        }
    }
?>


<div class="container container-xl my-4">
    <div class="issue-header">
        <h1 class="issue-headline">
            <span class="title">Neuer Issue</span>
        </h1>
    </div>

    <div class="discussion-bucket row">
        <div id="discussion-contribute" class="col-9 mb-5">
            <div class="card discussion-card" id="new-comment">
                <div class="card-body">
                    <form method="post" action="?newIssue" id="newIssueForm">
                        <input type="text" name="title" id="title-NewIssue" class="form-control mb-3" placeholder="Titel" onkeyup="enableNewSubmit()">
                        <textarea name="text" id="text-newComment" class="form-control" placeholder="Lasse einen Kommentar da"></textarea>
                        <input type="hidden" id="input-action" name="action" value="comment-intro">

                        <div class="buttons text-end my-3">
                            <button class="btn btn-success" type="submit" id="submitNewIssue" disabled>Kommentieren</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   
        <div class="col-3 sidebar">
            <div class="sidebar-item">
                <div class="position-relative">
                    <div class="sidebar-heading disabled d-flex" data-bs-toggle="tooltip" data-bs-title="Die Labels können erst später vergeben werden." data-bs-placement="bottom">
                        <h6 class="flex-fill">Labels</h6>
                        <div class="flex-fill text-end">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M7.429 1.525a6.593 6.593 0 011.142 0c.036.003.108.036.137.146l.289 1.105c.147.56.55.967.997 1.189.174.086.341.183.501.29.417.278.97.423 1.53.27l1.102-.303c.11-.03.175.016.195.046.219.31.41.641.573.989.014.031.022.11-.059.19l-.815.806c-.411.406-.562.957-.53 1.456a4.588 4.588 0 010 .582c-.032.499.119 1.05.53 1.456l.815.806c.08.08.073.159.059.19a6.494 6.494 0 01-.573.99c-.02.029-.086.074-.195.045l-1.103-.303c-.559-.153-1.112-.008-1.529.27-.16.107-.327.204-.5.29-.449.222-.851.628-.998 1.189l-.289 1.105c-.029.11-.101.143-.137.146a6.613 6.613 0 01-1.142 0c-.036-.003-.108-.037-.137-.146l-.289-1.105c-.147-.56-.55-.967-.997-1.189a4.502 4.502 0 01-.501-.29c-.417-.278-.97-.423-1.53-.27l-1.102.303c-.11.03-.175-.016-.195-.046a6.492 6.492 0 01-.573-.989c-.014-.031-.022-.11.059-.19l.815-.806c.411-.406.562-.957.53-1.456a4.587 4.587 0 010-.582c.032-.499-.119-1.05-.53-1.456l-.815-.806c-.08-.08-.073-.159-.059-.19a6.44 6.44 0 01.573-.99c.02-.029.086-.075.195-.045l1.103.303c.559.153 1.112.008 1.529-.27.16-.107.327-.204.5-.29.449-.222.851-.628.998-1.189l.289-1.105c.029-.11.101-.143.137-.146zM8 0c-.236 0-.47.01-.701.03-.743.065-1.29.615-1.458 1.261l-.29 1.106c-.017.066-.078.158-.211.224a5.994 5.994 0 00-.668.386c-.123.082-.233.09-.3.071L3.27 2.776c-.644-.177-1.392.02-1.82.63a7.977 7.977 0 00-.704 1.217c-.315.675-.111 1.422.363 1.891l.815.806c.05.048.098.147.088.294a6.084 6.084 0 000 .772c.01.147-.038.246-.088.294l-.815.806c-.474.469-.678 1.216-.363 1.891.2.428.436.835.704 1.218.428.609 1.176.806 1.82.63l1.103-.303c.066-.019.176-.011.299.071.213.143.436.272.668.386.133.066.194.158.212.224l.289 1.106c.169.646.715 1.196 1.458 1.26a8.094 8.094 0 001.402 0c.743-.064 1.29-.614 1.458-1.26l.29-1.106c.017-.066.078-.158.211-.224a5.98 5.98 0 00.668-.386c.123-.082.233-.09.3-.071l1.102.302c.644.177 1.392-.02 1.82-.63.268-.382.505-.789.704-1.217.315-.675.111-1.422-.364-1.891l-.814-.806c-.05-.048-.098-.147-.088-.294a6.1 6.1 0 000-.772c-.01-.147.039-.246.088-.294l.814-.806c.475-.469.679-1.216.364-1.891a7.992 7.992 0 00-.704-1.218c-.428-.609-1.176-.806-1.82-.63l-1.103.303c-.066.019-.176.011-.299-.071a5.991 5.991 0 00-.668-.386c-.133-.066-.194-.158-.212-.224L10.16 1.29C9.99.645 9.444.095 8.701.031A8.094 8.094 0 008 0zm1.5 8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM11 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="issue-labels">
                        Bisher keine
                    </div>
                </div>
            </div>

            <div class="sidebar-item">
                <div class="position-relative">
                    <div class="sidebar-heading d-flex">
                        <h6 class="flex-fill">Personen</h6>
                    </div>
                    <div class="issue-persons d-flex">
                        <a class="person-chip" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'?search=author:'.'user' ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Issue-Autor">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M10.5 5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm.061 3.073a4 4 0 10-5.123 0 6.004 6.004 0 00-3.431 5.142.75.75 0 001.498.07 4.5 4.5 0 018.99 0 .75.75 0 101.498-.07 6.005 6.005 0 00-3.432-5.142z"></path>
                            </svg>
                            <span><?php echo 'user' ?></span>
                        </a>
                        <!-- FIXME: Den Session User anzeigen #17 -->
                        <!-- FIXME:Link anpassen wenn Filter geändert sind #16, #22 -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'components/footer.php' ?>