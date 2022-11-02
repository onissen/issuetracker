<?php
    if (isset($_GET['newComment'])) {
        $text = $_POST['text'];
        $action = $_POST['action'];
        $date = date('Y-m-d');
        $user = $_SESSION['username'];
        $sql_newComment = "INSERT INTO comments (issue_id, date, author, text, action) VALUES ($sql_id, '$date', '$user', '$text', '$action')";
        $stmt_new = $db->prepare($sql_newComment);
        if ($stmt_new->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_GET['commentAction'])) {
        $sql_id = $issues['sql_id'];
        $text = $_POST['text'];
        $action = $_POST['action'];
        $date = date('Y-m-d');
        $user = $_SESSION['username'];
        $sql_commentAction = "INSERT INTO comments (issue_id, date, author, text, action) VALUES ($sql_id, '$date', '$user', '$text', '$action');";
        if ($_GET['commentAction'] == 'closed') {
            $sql_commentAction.= "UPDATE issues SET status = 'closed', date_closed = '$date' WHERE sql_id = $sql_id;";
        } elseif ($_GET['commentAction'] == 'reopened') {
            $sql_commentAction.= "UPDATE issues SET status = 'open', date_closed = NULL WHERE sql_id = $sql_id;";
        }
        elseif ($_GET['commentAction'] == 'notplanned') {
            $sql_commentAction.= "UPDATE issues SET status = 'notplanned', date_closed = '$date' WHERE sql_id = $sql_id;";
        }
        $stmt_action = $db->prepare($sql_commentAction);
        if ($stmt_action->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_GET['deleted'])) {
        $id = $_GET['deleted'];
        $action = $_GET['action'];

        if ($action == 'comment') {
            $sql = "DELETE FROM comments WHERE sql_id = $id";
        } else{ $sql = "UPDATE comments SET text = '' WHERE sql_id = $id"; };

        $stmt_delete = $db->prepare($sql);
        if ($stmt_delete->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_GET['deleteAction'])) {
        $id = $_GET['deleteAction'];
        $deleteAction = $db->prepare("DELETE FROM comments WHERE sql_id = $id");
        if ($deleteAction->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_GET['editComment'])) {
        $id = $_GET['editComment'];
        $text = $_POST['text'];
        $editComment = $db->prepare("UPDATE comments SET text = '$text' WHERE sql_id = $id");
        if ($editComment->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }
?>


<div class="col-9 activity">
    <?php foreach ($comments as $comment) { ?>
        <div class="card discussion-card">
            <?php if($comment['text'] != '') { ?>
            <div class="card-header row">
                <div class="col-11">
                    <b><?php echo $comment['author'] ?></b> <span>schrieb am <?php echo $comment['date'] ?></span>
                </div>
                <?php if (isset($_SESSION['username'])) {
                    if ($_SESSION['role'] == 'admin' OR $comment['author'] == $_SESSION['username'] OR $issues['author'] == $_SESSION['username']) { ?>
                        <div class="actions col-1 text-end">
                            <div class="position-relative flex-1">
                                <a class="toggleBtn toggleCommentMenu" id="toggleCommentMenu<?php echo $comment['sql_id'] ?>" onclick="toggleCommentMenu(<?php echo $comment['sql_id'] ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                        <path d="M8 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM1.5 9a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm13 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                    </svg>
                                </a>
                                <div class="menu-popover hideCommentMenu" id="commentMenu<?php echo $comment['sql_id'] ?>">
                                    <div class="popover-message shadow-lg">
                                        <div class="commentDropdown text-start">
                                            <div class="menuItem" onclick="toggleEditCommit(<?php echo $comment['sql_id'] ?>, 'edit')"><a>Bearbeiten</a></div>
                                            <?php if ($comment['action'] != 'comment-intro') { ?>
                                                <div class="menuItem menuItem-danger" onclick="toggleDeleteCommit(<?php echo $comment['sql_id'] ?>, '<?php echo $comment['action'] ?>')"><a>Löschen</a></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php }} ?>
            </div>
            <div class="card-body">
                <div id="show-card<?php echo $comment['sql_id'] ?>">
                    <?php echo $comment['text']?>
                </div>
                <form method="post" action="?editComment=<?php echo $comment['sql_id'] ?>" class="editCommentForm" id="edit-card<?php echo $comment['sql_id'] ?>">
                    <textarea name="text" class="text-editComment form-control" placeholder="Lasse einen Kommentar da"><?php echo $comment['text'] ?></textarea>
                    <div class="buttons text-end my-3">
                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="toggleEditCommit(<?php echo $comment['sql_id'] ?>, 'cancel')">Verwerfen</button>
                        <button class="btn btn-success btn-sm" type="submit">Kommentar aktualisieren</button>
                    </div>
                </form>
            </div>
            <?php } ?>
            <?php if ($comment['action'] != 'comment' AND $comment['action'] != 'comment-intro') { ?>
                <div class="card-footer d-flex">
                    <div class="commit-action-icon <?php echo $comment['action'] ?>">
                        <?php if ($comment['action'] == 'closed') {?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path><path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path></svg>
                        <?php } elseif ($comment['action'] == 'notplanned') {?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path></svg>
                        <?php } elseif ($comment['action'] == 'reopened') {?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16"><path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path><path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path></svg>
                        <?php } ?>
                    </div>
                    <div class="col-10">
                        <?php
                            if ($comment['action'] == 'closed') {echo $comment['author'].' schoss diesen Issue am '.$comment['date'].' ab.';}
                            elseif ($comment['action'] == 'notplanned') {echo $comment['author'].' schoss diesen Issue am '.$comment['date'].' als nicht geplant ab.';}
                            elseif ($comment['action'] == 'reopened') {echo $comment['author'].' reaktiverte diesen Issue am '.$comment['date'].'.';}
                            else {echo $comment['action'].' by '.$comment['author'].' on '.$comment['date'];}
                        ?>
                    </div>
                    <div class="actions col-1 text-end">
                        <?php if(isset($_SESSION['role']) AND $_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'manager') { ?>
                            <a class="text-danger" onclick="confirmDeleteAction(<?php echo $comment['sql_id'] ?>)" id="deleteActionBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M6.5 1.75a.25.25 0 01.25-.25h2.5a.25.25 0 01.25.25V3h-3V1.75zm4.5 0V3h2.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H5V1.75C5 .784 5.784 0 6.75 0h2.5C10.216 0 11 .784 11 1.75zM4.496 6.675a.75.75 0 10-1.492.15l.66 6.6A1.75 1.75 0 005.405 15h5.19c.9 0 1.652-.681 1.741-1.576l.66-6.6a.75.75 0 00-1.492-.149l-.66 6.6a.25.25 0 01-.249.225h-5.19a.25.25 0 01-.249-.225l-.66-6.6z"></path>
                                </svg>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="path"></div>
    <?php } ?>
    
    <?php if(isset($_SESSION['role'])) { ?>
        <div id="discussion-contribute" class="mb-5">
            <div class="card discussion-card" id="new-comment">
                <div class="card-header">
                    <b>Neuer Kommentar</b>
                </div>
                <div class="card-body">
                    <form method="post" action="?newComment" id="newcommentForm">
                        <textarea name="text" id="text-newComment" class="form-control" placeholder="Lasse einen Kommentar da" onkeyup="enableSubmit()"></textarea>
                        <input type="hidden" id="input-action" name="action" value="comment">

                        <div class="buttons text-end my-3">
                            <?php if ($issues['status'] == 'open') { ?>
                                <div class="btn-group">
                                    <button class="btn btn-gh" type="button" onclick="changeStatus('closed')">
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
                                        <li><button class="dropdown-item d-flex flex-items-center py-2" onclick="changeStatus('closed')">
                                            <svg class="text-closed me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path d="M11.28 6.78a.75.75 0 00-1.06-1.06L7.25 8.69 5.78 7.22a.75.75 0 00-1.06 1.06l2 2a.75.75 0 001.06 0l3.5-3.5z"></path>
                                                <path fill-rule="evenodd" d="M16 8A8 8 0 110 8a8 8 0 0116 0zm-1.5 0a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                                            </svg>
                                            Als erledigt abschließen
                                        </button></li>
                                        <li><button class="dropdown-item d-flex flex-items-center py-2" onclick="changeStatus('notplanned')">
                                            <svg class="text-muted me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path fill-rule="evenodd" d="M1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0zM8 0a8 8 0 100 16A8 8 0 008 0zm3.28 5.78a.75.75 0 00-1.06-1.06l-5.5 5.5a.75.75 0 101.06 1.06l5.5-5.5z"></path>
                                            </svg>
                                            Als nicht geplant abschließen
                                        </button></li>
                                    </ul>
                                </div>
                            <?php } elseif ($issues['status'] = 'closed') { ?>
                                <div class="btn-group">
                                    <button class="btn btn-gh" type="button" onclick="changeStatus('reopened')">
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
                                        <li><button class="dropdown-item d-flex flex-items-center py-2" onclick="changeStatus('reopened')">
                                            <svg class="text-open me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                <path d="M5.029 2.217a6.5 6.5 0 019.437 5.11.75.75 0 101.492-.154 8 8 0 00-14.315-4.03L.427 1.927A.25.25 0 000 2.104V5.75A.25.25 0 00.25 6h3.646a.25.25 0 00.177-.427L2.715 4.215a6.491 6.491 0 012.314-1.998zM1.262 8.169a.75.75 0 00-1.22.658 8.001 8.001 0 0014.315 4.03l1.216 1.216a.25.25 0 00.427-.177V10.25a.25.25 0 00-.25-.25h-3.646a.25.25 0 00-.177.427l1.358 1.358a6.501 6.501 0 01-11.751-3.11.75.75 0 00-.272-.506z"></path>
                                                <path d="M9.06 9.06a1.5 1.5 0 11-2.12-2.12 1.5 1.5 0 012.12 2.12z"></path>
                                            </svg>
                                            Issue reaktivieren
                                        </button></li>
                                        <li><button class="dropdown-item d-flex flex-items-center py-2" onclick="changeStatus('closed')">
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
                            <button class="btn btn-success" type="submit" id="submitNewComment" disabled>Kommentieren</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            <a href="<?php echo $SiteURL.'login' ?>">Melde dich an</a>, um an dieser Diskussion teilzuhaben.
        </div>
    <?php } ?>
</div>