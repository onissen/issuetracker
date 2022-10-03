<?php 
    $siteTitle = 'Labels | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php include 'components/topic-header.php'; ?>

<?php 
    $topicid = $info['tpid'];
    $sql_list = "SELECT * FROM labels WHERE topicid = $topicid ORDER BY name";
    $result_list = $db->query($sql_list)->fetchAll();
    $labelAverage = $db->query($sql_list)->rowCount();

    function IssueAmount() {
        // Kann erst ausgewertet werden, wenn die Labels vergeben sind...
    }

    if (isset($_REQUEST['deleted'])) {
        $labelid = $_REQUEST['deleted'];
        $sql_delete = "DELETE FROM labels WHERE $labelid";
        $stmt_delete = $db->prepare($sql_delete);
        $stmt_delete->execute();
        if ($stmt_delete->execute()) {
            echo '<script type="text/JavaScript"> location.search = "";</script>';
        }
    }
?>

<div class="container container-xl mt-4">
    <!-- TODO: Suche -->
    <div class="searchbar row">
        <form id="label-searchForm" class="querybox col-md-6 col-12" method="get">
            <div class="input-group">
                <span class="input-group-text form-control-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M11.5 7a4.499 4.499 0 11-8.998 0A4.499 4.499 0 0111.5 7zm-.82 4.74a6 6 0 111.06-1.06l3.04 3.04a.75.75 0 11-1.06 1.06l-3.04-3.04z"></path>
                    </svg>
                </span>
                <input type="text" name="search" id="search-labels" class="form-control form-control-sm" placeholder="Label suchen" autocomplete="off">
            </div>
        </form>
        <div class="col text-end">
            <a href="#" id="new-label" class="btn btn-primary btn-sm">
                <!-- TODO: Collapse erscheint --> 
                Neues Label
            </a>
        </div>
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
                <!-- TODO: Suche anwenden -->
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
                    <div class="issuelist-item d-flex p-3" id="<?php echo $list['labelid']; ?>">
                        <div class="issuelist-content w-100">
                            <div class="label-badge badge rounded-pill" style="background-color: <?php echo $list['color'] ?>"><?php echo $list['name'] ?></div>
                        </div>
                        <div class="labellist-description pe-2 w-100"><?php echo $list['description'] ?></div>
                        <?php IssueAmount() ?>
                        <div class="labellist-issues w-100">x offene Issues</div>
                        <div class="labellist-actions text-end d-lg-flex d-none">
                            <button class="ms-3 btn btn-link">Bearbeiten</button>
                                <!-- Dann Collapse -->
                            <button class="ms-3 btn btn-link" name="deleteLabel" type="submit" onclick="toggleDelete(<?php echo $list['labelid'] ?>)">Löschen</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div id="issuelist-noresults" class="container-md">
                    <div id="noresult-message">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path d="M7.75 6.5a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z"></path><path fill-rule="evenodd" d="M2.5 1A1.5 1.5 0 001 2.5v8.44c0 .397.158.779.44 1.06l10.25 10.25a1.5 1.5 0 002.12 0l8.44-8.44a1.5 1.5 0 000-2.12L12 1.44A1.5 1.5 0 0010.94 1H2.5zm0 1.5h8.44l10.25 10.25-8.44 8.44L2.5 10.94V2.5z"></path>
                        </svg>
                        <h3>Keine Labels</h3>
                        <p>Es gibt keine Labels. Erstelle doch eins.</p>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>

<?php require 'components/footer.php' ?>