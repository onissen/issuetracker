<?php 
    $siteTitle = 'Settings | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php
    include 'components/topic-header.php';

    $channels = $db->query("SELECT chid, channel FROM channels ORDER BY channel")->fetchAll();

    if (isset($_POST['general_rename'])) {
        $id = $info['tpid'];
        $topicName = $_POST['topic'];
        $rename = $db->prepare("UPDATE topics SET topic = '$topicName' WHERE tpid = $id");
        if ($rename->execute()) {
            echo '<script>location="'.$SiteURL.$info['channel'].'/'.$topicName.'/settings'.'"</script>';
        }
    }

    if (isset($_POST['submitFeatures'])) {
        $id = $info['tpid'];

        if (isset($_POST['feature_issues']) AND $_POST['feature_issues'] == true) 
                {$feature_issues = 'true';}
        else    {$feature_issues = 'false';}

        if (isset($_POST['feature_projects']) AND $_POST['feature_projects'] == true) 
                {$feature_projects = 'true';}
        else    {$feature_projects = 'false';}

        $sql_features = "UPDATE topics SET feature_issues = $feature_issues, feature_projects = $feature_projects WHERE tpid = $id";
        $edit_features = $db->prepare($sql_features);
        if ($edit_features->execute()) {
            echo '<script>location ="'.$SiteURL.$info['channel'].'/'.$info['topic'].'/settings'.'"</script>';
        }        
    }


    if (isset($_POST['submitVisibility'])) {
        $confirm = $_POST['confirm'];
        $testText = $info['channel'].'/'.$info['topic'];

        if ($confirm == $testText) {
            $id = $_POST['id'];
            $visibility = $_POST['visibility'];

            $changeVisibility = $db->prepare("UPDATE topics SET visibility = '$visibility' WHERE tpid = $id");
            if ($changeVisibility->execute()) {
                echo '<script>location ="'.$SiteURL.$info['channel'].'/'.$info['topic'].'/settings'.'"</script>';
            }
        }
    }

    if (isset($_POST['submitChannel'])) {
        $confirm = $_POST['confirm'];
        $testText = $info['channel'].'/'.$info['topic'];
        
        if ($confirm == $testText) {
            $id = $info['tpid'];
            if ($_POST['channel'] != 'New') {
                $channelText = explode(',,', $_POST['channel']);
                $chid = $channelText[0];
                $newChannel = $channelText[1];
            } elseif($_POST['channel'] == 'New') {
                $channel = $_POST['newChannel'];
                $newChannelEntry = $db->prepare("INSERT INTO channels (channel) VALUES('$channel')");
                $newChannelEntry->execute();
                
                $channelText = $db->query("SELECT chid, channel FROM channels WHERE channel = '$channel'")->fetch();
                $chid = $channelText[0];
                $newChannel = $channelText[1];
            }

            $changeChannel = $db->prepare("UPDATE topics SET chid = $chid WHERE tpid = $id");
            if ($changeChannel->execute()) {
                echo '<script>location ="'.$SiteURL.$newChannel.'/'.$info['topic'].'/settings'.'"</script>';
            }

        }
    }

    if (isset($_POST['submitDelete'])) {
        $confirm = $_POST['confirm'];
        $testText = $info['channel'].'/'.$info['topic'];

        if ($confirm == $testText) {
            $id = $info['tpid'];
            $sql_delete = "DELETE FROM issues WHERE tpid = $id; DELETE FROM topics WHERE tpid = $id";
            $deleteTopic = $db->prepare($sql_delete);
            if ($deleteTopic->execute()) {
                echo '<script>location ="'.$SiteURL.'"</script>';
            }
        }
    }


?>

<div class="container container-xl my-4">
    <div class="options-bucket">
        <div id="general">
            <h2 class="pb-2 mb-3 border-bottom-muted">Allgemeine Einstellungen</h2>
            <form method="post" class="d-flex">        
                <div class="form-group mb-0 me-1 col-sm-6">
                    <label for="inputTopicName" class="form-label form-label-sm">Name des Themas</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><?php echo $info['channel'] ?> /</span>
                        <input type="text" name="topic" id="inputTopicName" class="form-control" max-length="100" value="<?php echo $info['topic'] ?>">
                    </div>
                </div>
                <button type="submit" name="general_rename" class="align-self-end btn btn-gh btn-sm">Umbenennen</button>
            </form>
        </div>
        <div id="features">
            <h2 class="pb-2">Features</h2>
            <form method="post">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="form-check">
                            <input  name="feature_issues" <?php if ($info['feature_issues'] == true) {echo 'checked';} ?> id="checkIssues" class="form-check-input" type="checkbox" value="true">
                            <label class="form-check-label" for="checkIssues">Issues</label>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-check">
                            <input name="feature_projects" <?php if ($info['feature_projects'] == true) {echo 'checked';} ?> id="checkProjects" class="form-check-input" type="checkbox" value="true">
                            <label class="form-check-label" for="checkProjects">Projects</label>
                        </div>
                    </div>
                    <div class="list-group-item list-group-button">
                        <button type="submit" class="btn btn-link w-100" name="submitFeatures">Features aktualisieren</button>
                    </div>
                </div>
            </form>
        </div>
        <div id="danger-zone">
            <h2 class="pb-2">Danger Zone</h2>
            <div class="list-group" id="list-group-danger">
                <div class="list-group-item d-flex align-items-center">
                    <div class="me-auto mb-md-0 mb-2">
                        <b>Sichtbarkeit des Themas ??ndern</b>
                        <div class="mb-0">
                            <span>Dieses Thema ist im Moment</span>
                            <span>
                                <?php 
                                    if ($info['visibility'] == 'public' OR $info['visibility'] == 'public-archive') {echo '<b>??ffentlich</b> sichtbar';}
                                    else if ($info['visibility'] == 'authenticated' OR $info['visibility'] == 'authenticated-archive') {echo '<b>nur intern</b> sichtbar';}
                                    else {echo 'mit dem Sichtbarkeitsstatus '.'<b>'.$info['visibility'].'</b>'.' sichtbar.';}
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex-md-order-1 flex-order-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#visibilityModal">Sichtbarkeit ??ndern</button>
                        <div class="modal fade" id="visibilityModal" aria-labelledby="visibilityModalLabel" aria-hidden="true" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title h5" id="visibilityModalLabel">Sichtbarkeit des Themas ??ndern</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <span>
                                                Aktuell:
                                                <?php   
                                                    if ($info['visibility'] == 'public' OR $info['visibility'] == 'public-archive') {echo '??ffentlich';
                                                    } elseif ($info['visibility'] == 'authenticated' OR $info['visibility'] == 'authenticated-archive') {echo 'Nur Intern';
                                                    } else {echo $info['visibility'];}
                                                ?>
                                            </span>
                                            <div class="form-check">
                                                <input id="radioPublic" value="public" <?php if($info['visibility'] == 'public') {echo 'checked';} ?> type="radio" name="visibility" class="form-check-input">
                                                <label for="radioPublic" class="form-check-label">??ffentliches Thema</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="radioPublicArchive" value="public-archive" <?php if($info['visibility'] == 'public-archive') {echo 'checked';} ?> type="radio" name="visibility" class="form-check-input">
                                                <label for="radioPublicArchive" class="form-check-label">??ffentliches Archiv-Thema</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="radioAuthenticated" value="authenticated" <?php if($info['visibility'] == 'authenticated') {echo 'checked';} ?> type="radio" name="visibility" class="form-check-input">
                                                <label for="radioAuthenticated" class="form-check-label">Internes Thema</label>
                                            </div>
                                            <div class="form-check">
                                                <input id="radioAuthenticatedArchive" value="authenticated-archive" <?php if($info['visibility'] == 'authenticated-archive') {echo 'checked';} ?> type="radio" name="visibility" class="form-check-input">
                                                <label for="radioAuthenticatedArchive" class="form-check-label">Internes Archiv-Thema</label>
                                            </div>

                                            <input type="hidden" name="id" value="<?php echo $info['tpid'] ?>">
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <label for="confirmVisibility">Bitte schreibe <b><?php echo $info['channel'].'/'.$info['topic'] ?></b> zur Best??tigung</label>
                                            <br>
                                            <input type="text" name="confirm" id="confirmVisibility" class="form-control form-control-sm" onkeyup="confirmDangerZone('Visibility', '<?php echo $info['channel'].'/'.$info['topic'] ?>')" autocomplete="off">
                                            <button type="submit" name="submitVisibility" id="submitVisibility" class="btn btn-danger w-100" disabled>Ich wei?? was ich tue, ??ndere die Sichtbarkeit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                    <div class="me-auto mb-md-2">
                        <b>In anderen Kanal verschieben</b>
                        <div class="mb-0">
                            <span>Verschiebe das Thema in einen anderen Kanal. Der aktuelle Kanal ist <b><?php echo $info['channel'] ?></b>.</span>
                        </div>
                    </div>
                    <div class="flex-md-order-1 flex-order-2">
                        <button class="btn btn-outline-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#channelModal">Kanal ??ndern</button>
                        <div class="modal fade" id="channelModal" aria-labelledby="channelModalLabel" aria-hidden="true" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title h5" id="channelModalLabel">In anderen Kanal verschieben</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group" id="list-group-channels">
                                                <?php foreach ($channels as $channel) { ?>
                                                    <li class="list-group-item">
                                                        <input class="form-check-input me-1" id="<?php echo 'radio'.$channel['chid'] ?>" type="radio" name="channel" value="<?php echo $channel['chid'].',,'.$channel['channel'] ?>" <?php if($channel['chid'] == $info['chid']) {echo 'checked';} ?> onchange="untoggleNewChannelWrapper()">
                                                        <label class="form-check-label" for="<?php echo 'radio'.$channel['chid'] ?>"><?php echo $channel['channel'] ?></label>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                            <div class="list-group mt-3">
                                                <div class="list-group-item">
                                                    <div class="radioGroup-toggleNew">
                                                        <input class="form-check-input me-1" id="radioNew" type="radio" name="channel" value="New" onchange="toggleNewChannelWrapper()">
                                                        <label class="form-check-label" for="NewChannel">Neuen Kanal anlegen</label>
                                                    </div>
                                                    <div class="form-group" id="input-wrapper-newChannel">
                                                        <label for="inputNewChannelName" class="form-label h5">Neuer Kanalname</label>
                                                        <input type="text" class="form-control form-control-sm" id="inputNewChannelName" name="newChannel">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <label for="confirmChannel">Bitte schreibe <b><?php echo $info['channel'].'/'.$info['topic'] ?></b> zur Best??tigung</label>
                                            <br>
                                            <input type="text" name="confirm" id="confirmChannel" class="form-control form-control-sm" onkeyup="confirmDangerZone('Channel', '<?php echo $info['channel'].'/'.$info['topic'] ?>')" autocomplete="off">
                                            <button type="submit" name="submitChannel" id="submitChannel" class="btn btn-danger w-100" disabled>Ich wei?? was ich tue, verschiebe das Thema</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-group-item d-flex align-items-center">
                    <div class="me-auto mb-md-2">
                        <b>Dieses Thema l??schen</b>
                        <div class="mb-0">
                            <span>Wenn du das Thema l??scht, werden s??mtliche Daten dazu gel??scht. Bitte sei vorsichtig.</span>
                        </div>
                    </div>
                    <div class="flex-md-order-1 flex-order-2">
                        <button class="btn btn-outline-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#delteModal">Dieses Thema l??schen</button>
                        <div class="modal fade" id="delteModal" aria-labelledby="delteModalLabel" aria-hidden="true" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title h5" id="delteModalLabel">Bist du wirklich sicher?</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                                <b>Diese Aktion kann nicht r??ckg??ngig gemacht werden!</b><br>
                                                Durch diese Aktion werden alle Daten zu dem Thema <b><?php echo $info['channel'].'/'.$info['topic'] ?></b> gel??scht. 
                                                Das umfasst alle Issues, Labels, Commits, Projekte und ALLE weiteren Daten.
                                                Wenn diese Daten in Zukunft wieder interessant werden, w??ren sie weg. 
                                                Um dem entgegenzuwirken k??nntest du die Repo stattdessen als archiviert kennzeichnen.
                                            </p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <label for="confirmDelete">Bitte schreibe <b><?php echo $info['channel'].'/'.$info['topic'] ?></b> zur Best??tigung</label>
                                            <br>
                                            <input type="text" name="confirm" id="confirmDelete" class="form-control form-control-sm" onkeyup="confirmDangerZone('Delete', '<?php echo $info['channel'].'/'.$info['topic'] ?>')" autocomplete="off">
                                            <button type="submit" name="submitDelete" id="submitDelete" class="btn btn-danger w-100" disabled>Ich wei?? was ich tue, bitte l??sche das Repository</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'components/footer.php' ?>