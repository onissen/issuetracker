<!-- Powered by Check URL :-) -->
<?php
    if (!isset($_REQUEST['site'])) {
        $_REQUEST['site'] = 'domain';
    }
    $endpoints = explode('/', $_REQUEST['site']);
    if (end($endpoints) == '') {
        array_splice($endpoints, -1, 1);
    }
    $endpoit_lvl = count($endpoints);

    if (isset($endpoit_lvl)) {
        if ($endpoit_lvl <= 1) {
            // Keine Topic gewählt; ggf aber Channel oder New 
            // FIXME #44
            if ($endpoints[0] == 'newTopic') {
                // Neues Thema
                include 'new_topic.php';
            // } elseif ($endpoints[0] == 'newChannel') {
            //     // Neues Thema
            //     include 'new_channel.php';
            } elseif ($endpoints[0] == 'login') {
                include 'login.php';
            }
            else {
                include 'topics.php';
            }
            
        }

        elseif ($endpoit_lvl == 2) {
            // Repo Start
            // TODO: Kann vorerst direkt an Issues weiterleiten
            include 'issues.php';
        }

        elseif ($endpoit_lvl == 3) {
            // Unterseiten der Repo
            if (end($endpoints) == 'issues') {
                include 'issues.php';
            }

            if (end($endpoints) == 'labels') {
                include 'labels.php';
            }

            if (end($endpoints) == 'settings') {
                include 'topic_settings.php';
            }
            
        }

        elseif ($endpoit_lvl == 4) {
            if ($endpoints[2] == 'issues') {
                // Issues Detail
                if (end($endpoints) == 'new') {
                    // New Issue
                    include 'new_issue.php';
                } else {
                    // Issue Detail
                    include 'issue_details.php';
                }
            }
        }
    }

?>