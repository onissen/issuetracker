<?php 
    $siteTitle = "Neues Thema";
    require 'components/header.php'; 
?>

<?php
    $sql_channels = $db->query("SELECT chid, channel FROM channels")->fetchAll();

    if (isset($_POST['chid'])) {
        // Neues Thema
        if ($_POST['chid'] == 'newChannel') {
            // Neuen Channel anlegen
            $channel = $_POST['newChannelName'];
            $sql_newChannel = $db->prepare("INSERT INTO channels (channel) VALUES ('$channel')");
            if ($sql_newChannel->execute()) {
                $channelID = $db->query("SELECT chid FROM channels WHERE channel = '$channel'")->fetch();
                $chid = $channelID['chid'];
            }
        } else {
            // Channel besteht bereits
            $chid = $_POST['chid'];
        }

        $topic = $_POST['topic'];
        $description = $_POST['description'];
        $visibility = $_POST['visibility'];
        $owner = $_SESSION['username'];
        $feature_issues = $_POST['feature_issues'];
        $defaultLabels = $_POST['defaultLabels'];

        $sql_newTopic = "INSERT INTO topics(chid, topic, visibility, description, owner, feature_issues) VALUES ($chid, '$topic', '$visibility', '$description', '$owner', $feature_issues)";
        $stmt_newTopic = $db->prepare($sql_newTopic);
        $stmt_newTopic->execute();
        if ($defaultLabels = 1) {
            $topicid = $db->query("SELECT tpid FROM topics WHERE topic = '$topic' AND chid = $chid")->fetch();
            $topic_id = $topicid['tpid'];

            $sqlLabel = "INSERT INTO labels(topicid, name, description, color) 
            VALUES($topic_id, 'bug', 'Etwas funktioniert nicht', '#d73a4a'), ($topic_id, 'documentation', 'Dieser Issue dokumentiert etwas', '#0075ca'), ($topic_id, 'duplicate', 'Dieser Issue existiert bereits', '#cfd3d7'),
            ($topic_id, 'enhancement', 'Neues Feature oder Feature-Request', '#a2eeef'), ($topic_id, 'invalid', 'Das scheint falsch zu sein', '#e4e669'), ($topic_id, 'wontfix', 'Dieser Issue wird nicht weiter bearbeitet', '#000')";
            
            $insert_label = $db->prepare($sqlLabel);
            if ($insert_label->execute()) {
                echo '<script type="text/JavaScript"> location="'.$SiteURL.'"</script>';
            }
        } else {
            echo '<script type="text/JavaScript"> location="'.$SiteURL.'"</script>';
        }

        function DefaultLabel($topic_id) {
            $sqlLabel = "INSERT INTO labels(topicid, name, description, color) VALUES($topic_id, 'test', 'Test App', '#000')";
            $insert_label = $db->prepare($sqlLabel);
            if ($insert_label->execute()) {
                echo '<script type="text/JavaScript"> location="'.$SiteURL.'"</script>';
            }
        }
    }
?>

<div class="container-lg container-md mt-5">
    <div class="headline pb-2 mb-3 border-bottom-muted">
        <h2>Neues Thema erstellen</h2>
    </div>
    <form method="post" autocomplete="off">
        <div class="topic-name mb-2 pb-2 border-bottom-muted">
            <div class="d-flex">
                <div class="form-group">
                    <label for="selectChannel" class="form-label">Kanal</label>
                    <select name="chid" id="selectChannel" class="form-select form-select-sm" onchange="toggleNewChannel(value)">
                        <option value="">Kanal wählen</option>
                        <?php foreach ($sql_channels as $channel) { ?>
                            <option value="<?php echo $channel['chid'] ?>"><?php echo $channel['channel'] ?></option>
                        <?php } ?>
                        <option value="newChannel" class="toggleNewChannel">Neuer Kanal</option>
                    </select>
                </div>
                <span class="mx-2 topic-devider">/</span>
                <div class="form-group col-sm-6">
                    <label for="inputTopicName" class="form-label form-label-sm">Thema</label>
                    <input type="text" name="topic" id="inputTopicName" class="form-control form-control-sm" max-length="100">
                </div>
            </div>
            <div class="form-group mt-3 col-5" id="newChannelName">
                <label for="inputNewChannel" class="form-label">Neuer Kanalname</label>
                <input type="text" name="newChannelName" id="inputNewChannel" class="form-control form-control-sm">
            </div>
            <div class="form-group mt-4">
                <label for="inputDescription" class="form-label">Beschreibung (optional)</label>
                <input type="text" class="form-control form-control-sm" id="inputDescription" name="description">
            </div>
        </div>
        <div class="topic-visibility border-bottom-muted">
            <h5>Sichtbarkeit</h5>
            <div class="form-check">
                <input id="radioPublic" value="public" type="radio" name="visibility" class="form-check-input" checked>
                <label for="radioPublic" class="form-check-label">Öffentliches Thema</label>
            </div>
            <div class="form-check">
                <input id="radioAuthenticated" value="authenticated" type="radio" name="visibility" class="form-check-input">
                <label for="radioAuthenticated" class="form-check-label">Internes Thema</label>
            </div>
            <!-- TODO: ggf weitere Stati und Beschreibungen, Symbole... hinzufügen #39 -->
        </div>
        <div class="py-3 topic-features border-bottom-muted">
            <h5>Das Thema mit Features ausstatten</h5>
            <div class="form-check">
                <input id="check-issues" type="checkbox" name="feature_issues" class="form-check-input" value="true" checked onchange="UncheckNestedBox('issues')">
                <label for="check-issues" class="form-check-label">Issues</label>
            </div>
            <div class="nested-form-check form-check">
                <input id="checkdefaultLabels" type="checkbox" name="default_labels" class="nested-checkbox-issues form-check-input" value="true" checked>
                <label for="checkdefaultLabels" class="form-check-label">Thema mit Standard-Labels ausstatten</label>
            </div>
            <!-- <div class="form-check">
                <input id="checkProjects" type="checkbox" name="features" class="form-check-input" value="true">
                <label for="checkProjects" class="form-check-label">Projects</label>
            </div> -->
            <!-- TODO: Einblenden wenn Projekte/Idea-Boards eingebaut sind; ggf. weitere hinzufügen -->
        </div>
        <div class="topic-submit my-3">
            <button type="submit" class="btn btn-primary">Thema erstellen</button>
        </div>
    </form>
</div>
<?php require 'components/footer.php' ?>