<!-- Powered by Check URL :-) -->
<!-- TODO: Diese Seite aus Weiterleitungsseite stylen??? -->
<?php
    $endpoints = explode('/', $_REQUEST['site']);
    $endpoit_lvl = count($endpoints);

    if (isset($endpoit_lvl)) {
        if ($endpoit_lvl <= 1) {
            // Keine Topic gewählt; ggf aber ein Channel
            // FIXME: per meta an index.php weiterleiten
            include 'components/header.php';
            echo '<meta http-equiv="refresh" content="0; URL='.$SiteURL.'">';
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