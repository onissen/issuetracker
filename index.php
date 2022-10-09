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
            // Keine Topic gewählt; ggf aber ein Channel
            include 'topics.php';
        }

        elseif ($endpoit_lvl == 2) {
            // Repo Start
            // TODO: Kann vorerst direkt an Issues weiterleiten
            include 'issues.php';
        }

        elseif ($endpoit_lvl == 3) {
            // Unterseiten der Repo
            if (end($endpoints) == 'labels') {
                include 'labels.php';
            }
        }

        elseif ($endpoit_lvl == 4) {
            if ($endpoints[2] == 'issues') {
                // Issues Detail
                include 'issue_details.php';
            }
        }
    }
?>