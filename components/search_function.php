<?php
    //  ... wenn nach Status gefiltert, sonst alle
    if (isset($_REQUEST['filter'])) {
        if ($_REQUEST['filter'] == '' OR $_REQUEST['filter'] == 'open') {$sql_param = "AND status='open' ";}
        if ($_REQUEST['filter'] == 'closed') {$sql_param =  "AND status='closed' ";}
        if ($_REQUEST['filter'] == 'all') {$sql_param = "";}
    } else {
        $sql_param = "AND status='open'";
    }

    // Wenn Suchbegriffe eingegeben sind
    if (isset($_REQUEST['search'])) {
        // Suchbegriffe an den Leerzeichen teilen
        $raw_search = explode(' ', $_REQUEST['search']);

        foreach ($raw_search as $key => $value) {
            // Wenn der einzelne Begriff einen bestimmten typ: hat, nimm ihn auseinander und übergebe ihn in PHP Array
            if (strpos($value, ':') != false) {
                $subs = explode(':', $value);
                $queries[$key]['type'] = $subs[0];
                $queries[$key]['q'] = $subs[1];

            } else {
                // Wenn keine anderen Begriffe mit Typ angeben sind, vergebe query
                if (!isset($queries[$key-1]['type'])) {
                    $queries[$key]['type'] = 'query';
                    $queries[$key]['q'] = $value;
                } else {
                    // Füge das abgetrennte, aber zum Begriff gehörende Wort mit Leerzeichen wieder hinzu
                    $queries[$key-1]['q'] .= ' '.$value;
                }
            }
        }

        foreach ($queries as $key => $value) {
            $SearchRow = $queries[$key]['type'];
            $SearchTerm = $queries[$key]['q'];
            // Wenn kein Typ oder der allgemeine query angegeben ist wird alles durchsucht
            if ($SearchRow == 'query') {
                $sql_param .= "AND(title LIKE '%$SearchTerm%' OR author LIKE '%$SearchTerm%' OR id LIKE '%$SearchTerm%' OR status LIKE '%$SearchTerm%')";
            }
            // Sonst wird jeder Parameter automatisch einzeln hinzugefügt
            else {$sql_param .= " AND $SearchRow LIKE '%$SearchTerm%'";}
        }
    }

    // Anweisung zusammen schustern
    $sql_issues = "SELECT issues.* FROM issues WHERE tpid = $topicid $sql_param ORDER BY id DESC";
    $result_issues = $db->query($sql_issues)->fetchAll();
?>