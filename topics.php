<?php 
    $siteTitle = "Themenübersicht";
    require 'components/header.php'; 
?>

<?php

    $sql = "SELECT topics.*, channels.channel FROM topics LEFT JOIN channels ON topics.chid = channels.chid";
    $result = $db->query($sql)->fetchAll();

?>

<div class="container-lg container-md mt-5">
    <h2>Themenübersicht</h2>

    <div class="topic-filter d-flex align-items-start">
        <!-- TODO: Suche nach allen Themen #43 ----->
        <div class="d-flex flex-wrap text-end" id="topic-filter-dropdown">
            <button type="button" class="btn btn-gh me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Typ
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" type="button">Öffentlich</button></li>
                <li><button class="dropdown-item" type="button">Angemeldet</button></li>
                <!-- TODO: und weitere #39  -->
            </ul>
            <button type="button" class="btn btn-gh dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Sortierung
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" type="button">Zuletzt aktualisiert</button></li>
                <li><button class="dropdown-item" type="button">Name</button></li>
            </ul>
        </div>
        <div class="d-md-flex flex-md-items-center flex-md-justify-end">
            <a href="<?php echo $SiteURL ?>newTopic" class="btn btn-primary ms-3">
                Neues Thema
            </a>
            <!-- FIXME: If Higher Authenticated or Admin #33 -->
            <a href="<?php echo $SiteURL ?>newChannel" class="btn btn-primary ms-1">
                Neuer Channel
            </a>
        </div>
    </div>

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
                        <span class="badge rounded-pill label-muted-outline"><?php echo $topic['visibility'] ?></span>
                    </h3>
                </div>
                <div class="clearfix"></div>
                <div class="topic-description">
                    <p><?php echo $topic['description'] ?></p>
                </div>
                <!-- TODO: Tags hinzufügen #37 -->
                <!-- TODO: "Codesprache"-Tag hinzufügen #37 ?-->
            </div>
        <?php } ?>
    </div>

</div>
<?php require 'components/footer.php' ?>