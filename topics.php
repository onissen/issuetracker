<?php 
    $siteTitle = "Themenübersicht";
    require 'components/header.php'; 
?>

<?php

    $sql = "SELECT topics.*, channels.channel FROM topics LEFT JOIN channels ON topics.chid = channels.chid ";
    
    if (isset($_REQUEST['search']) AND !isset($_REQUEST['type'])) {
        $search = $_REQUEST['search'];
        $sql.= "WHERE (topic LIKE '%$search%' OR description LIKE '%$search%' OR channel LIKE '%$search%')";

    } elseif (isset($_REQUEST['type']) AND !isset($_REQUEST['search']) ) {
        $type = $_REQUEST['type'];
        $sql .= "WHERE (visibility LIKE '%$type%')";

    } elseif (isset($_REQUEST['search']) AND isset($_REQUEST['type']) ) {
        $search = $_REQUEST['search'];
        $type = $_REQUEST['type'];
        $sql .= "WHERE (topic LIKE '%$search%' OR description LIKE '%$search%' OR channel LIKE '%$search%')";
        $sql .= "AND (visibility LIKE '%$type%')";

    } else {
        // TODO: If authenticated show, else nur public #39
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
                <li><a href="?type=authenticated" class="dropdown-item">Interne Themen</a></li>
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
                    if ($_REQUEST['type'] == 'authenticated') {echo '<b>'.$results_average.' interne Themen'.'</b>';}
                } elseif (isset($_REQUEST['search']) AND isset($_REQUEST['type'])) { ?>
                    <b><?php echo $results_average ?></b> Ergebnisse für 
                    <b><?php if ($_REQUEST['type'] == 'public') {echo 'öffentliche';}
                    if ($_REQUEST['type'] == 'authenticated') {echo 'interne';} ?></b> 
                    Themen mit <b><?php echo $_REQUEST['search'] ?></b>
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

</div>
<?php require 'components/footer.php' ?>