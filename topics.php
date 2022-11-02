<?php 
    $siteTitle = "Themenübersicht";
    require 'components/header.php'; 
?>

<?php

    $sql = "SELECT topics.*, channels.channel FROM topics LEFT JOIN channels ON topics.chid = channels.chid ";
    
    if (isset($_REQUEST['search']) AND !isset($_REQUEST['type'])) {
        $search = $_REQUEST['search'];
        $sql.= "WHERE (topic LIKE '%$search%' OR description LIKE '%$search%' OR channel LIKE '%$search%')";

        if (!isset($_SESSION['role'])) {
            $sql .= "AND (visibility = 'public' OR visibility = 'public-archive')";
        }

    } elseif (isset($_REQUEST['type']) AND !isset($_REQUEST['search']) ) {
        $type = $_REQUEST['type'];
        $sql .= "WHERE (visibility LIKE '$type')";

    } elseif (isset($_REQUEST['search']) AND isset($_REQUEST['type']) ) {
        $search = $_REQUEST['search'];
        $type = $_REQUEST['type'];
        $sql .= "WHERE (topic LIKE '%$search%' OR description LIKE '%$search%' OR channel LIKE '%$search%')";
        $sql .= "AND (visibility LIKE '$type')";

    } else {
        if (isset($_SESSION['role'])) { $sql .= "WHERE (visibility LIKE 'public' OR visibility LIKE 'authenticated')"; } 
        else { $sql .= "WHERE (visibility LIKE 'public')"; }
    }
    $result = $db->query($sql)->fetchAll();
    $results_average = count($result);
?>

<div class="container-lg container-md mt-5">
    <h2>Themenübersicht</h2>

    <div class="topic-filter d-flex flex-row align-items-start">
        <div class="flex-grow-1 me-2">
            <input type="text" name="search" id="searchbox" class="form-control" value="<?php if (isset($_REQUEST['search'])) {echo $_REQUEST['search'];} ?>" autocomplete="off" onkeyup="Search(event)" placeholder="Finde ein Thema">
        </div>
        <div class="d-flex flex-wrap text-end" id="topic-filter-dropdown">
            <button type="button" class="btn btn-gh me-1 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Typ
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="?type=public" class="dropdown-item">Öffentliche Themen</a></li>
                <li><a href="?type=public-archive" class="dropdown-item">Öffentliche Archiv-Themen</a></li>
                <?php if (isset($_SESSION['role'])) { ?>
                    <li><a href="?type=authenticated" class="dropdown-item">Interne Themen</a></li>
                    <li><a href="?type=authenticated-archive" class="dropdown-item">Interne Archive-Themen</a></li>
                <?php } ?>
                <!-- TODO: und weitere #39  -->
            </ul>
        </div>
        <div class="d-md-flex flex-md-items-center flex-md-justify-end">
            <a href="<?php echo $SiteURL ?>newTopic" class="btn btn-primary ms-3">
                Neues Thema
            </a>
            <!-- Wird ggf nichtmehr hier verlinkt
                 FIXME: If Higher Authenticated or Admin #33
            <a href="<?php echo $SiteURL ?>newChannel" class="btn btn-primary ms-1">
                Neuer Channel
            </a> -->
        </div>
    </div>

    <?php if (isset($_REQUEST['search']) OR isset($_REQUEST['type'])) { ?>
        <div class="search-summary row border-bottom py-3 m-0">
            <div class="col-md" id="result-summary">
                <?php if (isset($_REQUEST['search']) AND !isset($_REQUEST['type'])) { ?>
                    <b><?php echo $results_average ?></b> Ergebnisse für Themen mit <b><?php echo $_REQUEST['search'] ?></b>
                <?php } elseif (isset($_REQUEST['type']) AND !isset($_REQUEST['search'])) {
                    if ($_REQUEST['type'] == 'public') {echo '<b>'.$results_average.' öffentliche Themen'.'</b>';}
                    if ($_REQUEST['type'] == 'public-archive') {echo '<b>'.$results_average.' öffentliche Archiv-Themen'.'</b>';}
                    if ($_REQUEST['type'] == 'authenticated') {echo '<b>'.$results_average.' interne Themen'.'</b>';}
                    if ($_REQUEST['type'] == 'authenticated-archive') {echo '<b>'.$results_average.' interne Archiv-Themen'.'</b>';}
                } elseif (isset($_REQUEST['search']) AND isset($_REQUEST['type'])) { ?>
                    <b><?php echo $results_average ?></b> Ergebnisse für 
                    <b><?php if ($_REQUEST['type'] == 'public') {echo 'öffentliche Themen';}
                    if ($_REQUEST['type'] == 'public-archive') {echo 'öffentliche Archiv-Themen';}
                    if ($_REQUEST['type'] == 'authenticated') {echo 'interne Themen';}
                    if ($_REQUEST['type'] == 'authenticated-archive') {echo 'interne Archiv-Themen';} ?></b> 
                    mit <b><?php echo $_REQUEST['search'] ?></b>
                <?php }?>
            </div>
            <div class="col-md-2 d-md-block d-sm-none reset-searchquery">
                <a href="<?php echo $SiteURL ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" id="resetQueryIcon">
                        <path fill-rule="evenodd" d="M3.72 3.72a.75.75 0 011.06 0L8 6.94l3.22-3.22a.75.75 0 111.06 1.06L9.06 8l3.22 3.22a.75.75 0 11-1.06 1.06L8 9.06l-3.22 3.22a.75.75 0 01-1.06-1.06L6.94 8 3.72 4.78a.75.75 0 010-1.06z"></path>
                    </svg>
                    Filter zurücksetzen
                </a>
            </div>
        </div>
    <?php } ?>
    
    <?php if($results_average > 0) { ?>
        <div class="topic-ul">
            <?php foreach ($result as $topic) { ?>
                <div class="topic-li">
                    <div class="topic-title">
                        <h3>
                            <a href="<?php echo $topic['channel'].'/'.$topic['topic'] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                    <path fill-rule="evenodd" d="M6.368 1.01a.75.75 0 01.623.859L6.57 4.5h3.98l.46-2.868a.75.75 0 011.48.237L12.07 4.5h2.18a.75.75 0 010 1.5h-2.42l-.64 4h2.56a.75.75 0 010 1.5h-2.8l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H5.45l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H1.75a.75.75 0 010-1.5h2.42l.64-4H2.25a.75.75 0 010-1.5h2.8l.46-2.868a.75.75 0 01.858-.622zM9.67 10l.64-4H6.33l-.64 4h3.98z"></path>
                                </svg>
                                <?php echo $topic['channel'].'/'.$topic['topic'] ?>
                            </a>
                            <span class="badge rounded-pill label-muted-outline">
                                <?php
                                    if ($topic['visibility'] == 'public') {echo 'Öffentliches Thema';}
                                    elseif ($topic['visibility'] == 'public-archive') {echo 'Öffentliches Archiv-Thema';}
                                    elseif ($topic['visibility'] == 'authenticated') {echo 'Internes Thema';}
                                    elseif ($topic['visibility'] == 'authenticated-archive') {echo 'Internes Archiv-Thema';}
                                    else {echo $topic['visibility'];}
                                ?>    
                            </span>
                        </h3>
                    </div>
                    <div class="clearfix"></div>
                    <div class="topic-description">
                        <p><?php echo $topic['description'] ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if(!isset($_SESSION['role'])) { ?>
            <div class="result-banner" id="moretopics">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill-rule="evenodd" d="M12.077 2.563a.25.25 0 00-.154 0L3.673 5.24a.249.249 0 00-.173.237V10.5c0 5.461 3.28 9.483 8.43 11.426a.2.2 0 00.14 0c5.15-1.943 8.43-5.965 8.43-11.426V5.476a.25.25 0 00-.173-.237l-8.25-2.676zm-.617-1.426a1.75 1.75 0 011.08 0l8.25 2.675A1.75 1.75 0 0122 5.476V10.5c0 6.19-3.77 10.705-9.401 12.83a1.699 1.699 0 01-1.198 0C5.771 21.204 2 16.69 2 10.5V5.476c0-.76.49-1.43 1.21-1.664l8.25-2.675zM13 12.232A2 2 0 0012 8.5a2 2 0 00-1 3.732V15a1 1 0 102 0v-2.768z"></path>
                </svg>
                <h3>Finde noch mehr Themen</h3>
                <p>Du siehst gerade nur die Öffentlichen Themen. <br>
                Wenn du dich <a href="<?php echo $SiteURL.'login' ?>">anmeldest</a>, kannst du noch mehr Themen sehen.</p>
            </div>
        <?php } /*if not logged in but results */ ?>
    <?php } /*if results*/ else {
        if (!isset($_SESSION['role'])) { ?>
            <div class="result-banner" id="no-public-topics">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill-rule="evenodd" d="M10.25 2a8.25 8.25 0 105.28 14.59l5.69 5.69a.75.75 0 101.06-1.06l-5.69-5.69A8.25 8.25 0 0010.25 2zM3.5 10.25a6.75 6.75 0 1113.5 0 6.75 6.75 0 01-13.5 0z"></path>
                </svg>
                <h3>Keine Treffer</h3>
                <p>Zu deiner Suche konnten keine Ergebnisse gefunden werden. <br>
                Wenn du dich <a href="<?php echo $SiteURL.'login' ?>">anmeldest</a>, kannst du in allen Themen suchen.</p>
            </div>
        <?php } else { ?>            
            <div class="result-banner" id="no-topics">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill-rule="evenodd" d="M10.25 2a8.25 8.25 0 105.28 14.59l5.69 5.69a.75.75 0 101.06-1.06l-5.69-5.69A8.25 8.25 0 0010.25 2zM3.5 10.25a6.75 6.75 0 1113.5 0 6.75 6.75 0 01-13.5 0z"></path>
                </svg>
                <h3>Keine Treffer</h3>
                <p>Zu deiner Suche konnten keine Ergebnisse gefunden werden. <br>
                Versuche einen anderen Suchbegriff.</p>
            </div>

    <?php }} ?>
</div>
<div class="footer-notice mt-3 text-muted text-center">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
        <path fill-rule="evenodd" d="M8 1.5c-2.363 0-4 1.69-4 3.75 0 .984.424 1.625.984 2.304l.214.253c.223.264.47.556.673.848.284.411.537.896.621 1.49a.75.75 0 01-1.484.211c-.04-.282-.163-.547-.37-.847a8.695 8.695 0 00-.542-.68c-.084-.1-.173-.205-.268-.32C3.201 7.75 2.5 6.766 2.5 5.25 2.5 2.31 4.863 0 8 0s5.5 2.31 5.5 5.25c0 1.516-.701 2.5-1.328 3.259-.095.115-.184.22-.268.319-.207.245-.383.453-.541.681-.208.3-.33.565-.37.847a.75.75 0 01-1.485-.212c.084-.593.337-1.078.621-1.489.203-.292.45-.584.673-.848.075-.088.147-.173.213-.253.561-.679.985-1.32.985-2.304 0-2.06-1.637-3.75-4-3.75zM6 15.25a.75.75 0 01.75-.75h2.5a.75.75 0 010 1.5h-2.5a.75.75 0 01-.75-.75zM5.75 12a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z"></path>
    </svg>
    <span>Du kannst dir alle archiverten Themen ansehen, wenn du sie mit dem Typ-Filter auswählst.</span>
</div>

<?php require 'components/footer.php' ?>