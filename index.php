<!-- Powered by Check URL :-) -->
<?php
    if (!isset($_REQUEST['site'])) {
        $_REQUEST['site'] = 'domain';
    }
    $endpoints = explode('/', $_REQUEST['site']);
    $endpoit_lvl = count($endpoints);

    if (isset($endpoit_lvl)) {
        if ($endpoit_lvl <= 1) {
            // Keine Topic gewählt; ggf aber ein Channel
            // FIXME: per meta an index.php weiterleiten
            include 'topics.php';
        }

        elseif ($endpoit_lvl = 2) {
            // Repo Start
            // TODO: Kann vorerst direkt an Issues weiterleiten
            include 'issues.php';
        }

        elseif ($endpoit_lvl = 3) {
            // Issue Detail Page
    
        }

        // elseif ($endpoit_lvl = 4) {
        //     // Müsste hinzugefügt werden, wenn es weitere Topic Pages geben soll!
        // }
    }
?>