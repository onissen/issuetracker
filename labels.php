<?php 
    $siteTitle = 'Labels | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php include 'components/topic-header.php'; ?>

<?php 
    $topicid = $info['tpid'];

    if (isset($_REQUEST['search'])) {
        $SearchTerm = $_REQUEST['search'];
        $sql_list = "SELECT * FROM labels WHERE topicid = $topicid AND (name LIKE '%$SearchTerm%' OR description LIKE '%$SearchTerm%') ORDER BY name";
    } else {
        $sql_list = "SELECT * FROM labels WHERE topicid = $topicid ORDER BY name";
    }
    
    $result_list = $db->query($sql_list)->fetchAll();
    $labelAverage = $db->query($sql_list)->rowCount();

    function IssueAmount($id) {
        global $db;

        $sql_IssueAmount = "SELECT COUNT(sql_id) AS average FROM issues WHERE (label LIKE '%..$id%' OR label LIKE '%$id..%') AND status = 'open'; ";
        $query_issues = $db->prepare($sql_IssueAmount);
        $query_issues->execute();
        global $IssueAmount;
        $IssueAmount = $query_issues->fetch();
    }

    if (isset($_REQUEST['deleted'])) {
        $labelid = $_REQUEST['deleted'];
        
        $sql_delete = "DELETE FROM labels WHERE labelid=$labelid";
        $stmt_delete = $db->prepare($sql_delete);
        if ($stmt_delete->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_GET['edited'])) {
        $labelid = $_GET['edited'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $color = $_POST['color'];
        
        $sql_edit = "UPDATE labels SET name='$name', description='$description', color='$color' WHERE labelid=$labelid";
        $stmt_edit = $db->prepare($sql_edit);
        if ($stmt_edit->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }

    if (isset($_REQUEST['new'])) {
        $topicid = $info['tpid'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $color = $_POST['color'];

        $sql_new = "INSERT INTO labels (topicid, name, description, color) VALUES ($topicid, '$name', '$description', '$color')";
        $stmt_new = $db->prepare($sql_new);
        if ($stmt_new->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }
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
                <input type="text" name="search" id="searchbox" class="form-control form-control-sm" value="<?php if (isset($_REQUEST['search'])) {echo $_REQUEST['search'];}?>" autocomplete="off" placeholder="Alle Labels durchsuchen" onkeyup="Search(event)">
            </div>
        </div>
        <div class="col-md-4 search-nav text-end">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#wrapper-new" role="button" aria-expanded="false" aria-controls="wrapper-new" onclick="randomBtnColor('New')">Neues Label</button>
        </div>
    </div>

    <div class="collapse my-3" id="wrapper-new">
        <div class="label-badge badge rounded-pill" id="badge-colorNew" style="background-color: red">Label Vorschau</div>
        <form action="?new" method="post" class="mt-3">
            <div class="row">
                <div class="col-md-3 col-12" id="col-name">
                    <label for="input-nameNew" class="form-label">Label Name</label>
                    <input type="text" name="name" id="input-nameNew" class="form-control-sm form-control" onkeyup="dynamicPreviewText('New')">
                </div>
                <div class="col-lg-4 col-md-3 col-12" id="col-description">
                    <label for="input-description" class="form-label">Beschreibung</label>
                    <input type="text" name="description" id="input-description" class="form-control-sm form-control">
                </div>
                <div class="col-md-2 col-12" id="col-color">
                    <label for="input-color" class="form-label">Color</label>
                    <div class="d-flex">
                        <button type="button" class="btn btn-sm btn-light text-light me-2" id="button-colorNew" style="background-color: 'red'" onclick="randomBtnColor('New')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M8 2.5a5.487 5.487 0 00-4.131 1.869l1.204 1.204A.25.25 0 014.896 6H1.25A.25.25 0 011 5.75V2.104a.25.25 0 01.427-.177l1.38 1.38A7.001 7.001 0 0114.95 7.16a.75.75 0 11-1.49.178A5.501 5.501 0 008 2.5zM1.705 8.005a.75.75 0 01.834.656 5.501 5.501 0 009.592 2.97l-1.204-1.204a.25.25 0 01.177-.427h3.646a.25.25 0 01.25.25v3.646a.25.25 0 01-.427.177l-1.38-1.38A7.001 7.001 0 011.05 8.84a.75.75 0 01.656-.834z"></path>
                            </svg>
                        </button>
                        <div class="position-relative flex-1">
                            <input type="text" name="color" id="input-colorNew" class="form-control-sm form-control" value="Fallback" onfocus="toggleColorPicker('New')" onkeyup="dynamicBtnColor('New')">
                            <div class="gh-popover color-popover" id="popover-colorNew">
                                <div class="popover-message popover-message-bottom-left p-2 mt-2 shadow-lg">
                                    <p>Aus Standardfarben wählen</p>
                                    <div class="d-flex justify-content-between mb-2">
                                        <button style="background-color: #b60205" onclick="chooseColor('#b60205', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #D93F0B" onclick="chooseColor('#D93F0B', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #FBCA04" onclick="chooseColor('#FBCA04', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #0E8A16" onclick="chooseColor('#0E8A16', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #006B75" onclick="chooseColor('#006B75', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #1D76DB" onclick="chooseColor('#1D76DB', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #0052CC" onclick="chooseColor('#0052CC', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #5319E7" onclick="chooseColor('#5319E7', 'New')" class="color-swatch btn-light" type="button"></button>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button style="background-color: #E99695" onclick="chooseColor('#E99695', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #F9D0C4" onclick="chooseColor('#F9D0C4', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #FEF2C0" onclick="chooseColor('#FEF2C0', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #C2E0C6" onclick="chooseColor('#C2E0C6', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #BFDADC" onclick="chooseColor('#BFDADC', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #C5DEF5" onclick="chooseColor('#C5DEF5', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #BFD4F2" onclick="chooseColor('#BFD4F2', 'New')" class="color-swatch btn-light" type="button"></button>
                                        <button style="background-color: #D4C5F9" onclick="chooseColor('#D4C5F9', 'New')" class="color-swatch btn-light" type="button"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-12 d-flex" id="col-buttons">
                    <div>
                        <button type="button" class="me-1 btn btn-sm btn-gh" data-bs-toggle="collapse" href="#wrapper-new" role="button" aria-expanded="true" aria-controls="wrapper-new">Schließen</button>
                        <button type="submit" class="btn btn-sm btn-success">Label erstellen</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="issuelist-wrapper mt-3">
        <div class="issuelist-header d-flex">
            <div class="listnav row flex-auto w-100">
                <div class="d-none d-lg-block label-states">
                    <span><?php echo $db->query($sql_list)->rowCount() ?> Labels</span>
                </div>
            </div>
            <div class="col-md-9 d-flex no-wrap justify-content-between justify-content-sm-start justify-content-lg-end action-menu">
            <div class="filterbar-item" id="sort">
                <!-- TODO: Suche anwenden #22 -->
                <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sortierung</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">A bis Z</a></li>
                    <li><a class="dropdown-item" href="#">Z bis A</a></li>
                    <li><a class="dropdown-item" href="#">Meiste Issues</a></li>
                    <li><a class="dropdown-item" href="#">Wenigste Issues</a></li>
                </ul>
            </div>

            </div>
        </div><!-- .issuelist-header -->
        <div class="issuelist-list" id="issuelist-list">
            <?php if ($labelAverage > 0) { ?>
                <?php foreach ($result_list as $list) { ?>
                    <div class="issuelist-item p-3" id="<?php echo $list['labelid']; ?>">
                        <div class="d-flex">
                            <div class="issuelist-content w-100">
                                <div class="label-badge badge rounded-pill" id="badge-color<?php echo $list['labelid'] ?>" style="background-color: <?php echo $list['color'] ?>"><?php echo $list['name'] ?></div>
                            </div>
                            <div class="labellist-description js-hide<?php echo $list['labelid'] ?> pe-2 w-100"><?php echo $list['description'] ?></div>
                            <div class="labellist-issues js-hide<?php echo $list['labelid'] ?> w-100">
                                <?php IssueAmount($list['labelid']);
                                if ($IssueAmount['average'] > 0) { ?>
                                    <a href="<?php echo $SiteURL.$endpoints[0].'/'.$endpoints[1].'?search=label:'.$list['labelid'] ?>" class="listlink">
                                        <?php echo $IssueAmount['average'] ?> offene Issues
                                    </a>
                                <?php } ?>
                                </div>
                            <div class="labellist-actions text-end d-lg-flex d-none">
                                <button class="js-hide<?php echo $list['labelid'] ?> ms-3 btn btn-link" data-bs-toggle="collapse" href="#wrapper-<?php echo $list['labelid']; ?>" role="button" aria-expanded="false" aria-controls="wrapper-<?php echo $list['labelid']; ?>" onclick="DisplayMeta('none', <?php echo $list['labelid']; ?>)">Bearbeiten</button>
                                <button class="ms-3 btn btn-link" name="deleteLabel" type="submit" onclick="toggleDelete(<?php echo $list['labelid'] ?>)">Löschen</button>
                            </div>
                        </div>
                        <div class="collapse mt-3" id="wrapper-<?php echo $list['labelid']; ?>">
                            <form action="?edited=<?php echo $list['labelid'] ?>" method="post">
                                <div class="row">
                                    <div class="col-md-3 col-12" id="col-name">
                                        <label for="input-name" class="form-label">Label Name</label>
                                        <input type="text" name="name" id="input-name<?php echo $list['labelid'] ?>" class="form-control-sm form-control" value="<?php echo $list['name'] ?>" onkeyup="dynamicPreviewText('<?php echo $list['labelid']; ?>')">
                                    </div>
                                    <div class="col-lg-4 col-md-3 col-12" id="col-description">
                                        <label for="input-description" class="form-label">Beschreibung</label>
                                        <input type="text" name="description" id="input-description" class="form-control-sm form-control" value="<?php echo $list['description'] ?>">
                                    </div>
                                    <div class="col-md-2 col-12" id="col-color">
                                        <label for="input-color" class="form-label">Farbe</label>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-sm btn-light text-light me-2" id="button-color<?php echo $list['labelid'] ?>" style="background-color: <?php echo $list['color'] ?>" onclick="randomBtnColor('<?php echo $list['labelid'] ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                                    <path fill-rule="evenodd" d="M8 2.5a5.487 5.487 0 00-4.131 1.869l1.204 1.204A.25.25 0 014.896 6H1.25A.25.25 0 011 5.75V2.104a.25.25 0 01.427-.177l1.38 1.38A7.001 7.001 0 0114.95 7.16a.75.75 0 11-1.49.178A5.501 5.501 0 008 2.5zM1.705 8.005a.75.75 0 01.834.656 5.501 5.501 0 009.592 2.97l-1.204-1.204a.25.25 0 01.177-.427h3.646a.25.25 0 01.25.25v3.646a.25.25 0 01-.427.177l-1.38-1.38A7.001 7.001 0 011.05 8.84a.75.75 0 01.656-.834z"></path>
                                                </svg>
                                            </button>
                                            <div class="position-relative flex-1">
                                                <input type="text" name="color" id="input-color<?php echo $list['labelid'] ?>" class="form-control-sm form-control" value="<?php echo $list['color'] ?>" onfocus="toggleColorPicker('<?php echo $list['labelid'] ?>')" onkeyup="dynamicBtnColor('<?php echo $list['labelid'] ?>')">
                                                <div class="gh-popover color-popover" id="popover-color<?php echo $list['labelid'] ?>">
                                                    <div class="popover-message popover-message-bottom-left p-2 mt-2 shadow-lg">
                                                        <p>Aus Standardfarben wählen</p>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <button style="background-color: #b60205" onclick="chooseColor('#b60205', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #D93F0B" onclick="chooseColor('#D93F0B', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #FBCA04" onclick="chooseColor('#FBCA04', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #0E8A16" onclick="chooseColor('#0E8A16', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #006B75" onclick="chooseColor('#006B75', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #1D76DB" onclick="chooseColor('#1D76DB', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #0052CC" onclick="chooseColor('#0052CC', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #5319E7" onclick="chooseColor('#5319E7', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <button style="background-color: #E99695" onclick="chooseColor('#E99695', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #F9D0C4" onclick="chooseColor('#F9D0C4', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #FEF2C0" onclick="chooseColor('#FEF2C0', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #C2E0C6" onclick="chooseColor('#C2E0C6', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #BFDADC" onclick="chooseColor('#BFDADC', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #C5DEF5" onclick="chooseColor('#C5DEF5', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #BFD4F2" onclick="chooseColor('#BFD4F2', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                            <button style="background-color: #D4C5F9" onclick="chooseColor('#D4C5F9', '<?php echo $list['labelid'] ?>')" class="color-swatch btn-light" type="button"></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-12 d-flex" id="col-buttons">
                                        <div>
                                            <button type="button" class="me-1 btn btn-sm btn-gh" data-bs-toggle="collapse" href="#wrapper-<?php echo $list['labelid']; ?>" role="button" aria-expanded="true" aria-controls="wrapper-<?php echo $list['labelid']; ?>" onclick="DisplayMeta('block', '<?php echo $list['labelid']; ?>')">Schließen</button>
                                            <button type="submit" class="btn btn-sm btn-success">Änderungen speichern</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div id="issuelist-noresults" class="container-md">
                    <div id="noresult-message">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M7.75 6.5a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z"></path><path fill-rule="evenodd" d="M2.5 1A1.5 1.5 0 001 2.5v8.44c0 .397.158.779.44 1.06l10.25 10.25a1.5 1.5 0 002.12 0l8.44-8.44a1.5 1.5 0 000-2.12L12 1.44A1.5 1.5 0 0010.94 1H2.5zm0 1.5h8.44l10.25 10.25-8.44 8.44L2.5 10.94V2.5z"></path>
                        </svg>
                        <?php if (isset($_REQUEST['search'])) { ?>
                            <h3>Keine passenden Labels</h3>
                            <p>Zu dem Suchbegriff konnten in diesem Thema keine Labels gefunden werden.</p>
                        <?php } else {?>
                            <h3>Keine Labels</h3>                        
                            <p>Es gibt keine Labels. Erstelle doch eins.</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<?php require 'components/footer.php' ?>