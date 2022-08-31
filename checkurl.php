<!-- TODO: Diese Seite aus Weiterleitungsseite stylen -->
<h1>Check URL</h1>
<?php
    $endpoints = explode('/', $_REQUEST['q']);
    print_r($endpoints);
    
    $endpoit_lvl = count($endpoints);
    echo 'Länge $endpoints: '.$endpoit_lvl;

    if (isset($endpoit_lvl)) {
        if ($endpoit_lvl <= 1) {
            // Keine Topic gewählt; ggf aber ein Channel
            // FIXME: per meta an index.php weiterleiten
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