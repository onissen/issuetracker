<?php
            
    if (isset($_REQUEST['filter'])) {
        if ($_REQUEST['filter'] == '' OR $_REQUEST['filter'] == 'open') {$sql_param = "AND status='open' ";}
        if ($_REQUEST['filter'] == 'closed') {$sql_param =  "AND status='closed' ";}
        if ($_REQUEST['filter'] == 'all') {$sql_param = "";}
    } else {
        $sql_param = "AND status='open'";
    }

    if (isset($_REQUEST['search'])) {
        $raw_search = explode(' ', $_REQUEST['search']);
        
        foreach ($raw_search as $key => $value) {
            if (strpos($value, ':') != false) {
                $subs = explode(':', $value);
                $queries[$key]['type'] = $subs[0];
                $queries[$key]['q'] = $subs[1];

            } else {
                if (!isset($queries[$key-1]['type'])) {
                    $queries[$key]['type'] = 'query';
                    $queries[$key]['q'] = $value;
                } else {
                    $queries[$key-1]['q'] .= ' '.$value;
                }
                print_r($queries);
        }   }

        foreach ($queries as $key => $value) {
            $SearchRow = $queries[$key]['type'];
            $SearchTerm = $queries[$key]['q'];
            if ($SearchRow == 'query') {
                $sql_param .= "AND(title LIKE '%$SearchTerm%' OR author LIKE '%$SearchTerm%' OR issue_id LIKE '%$SearchTerm%' OR status LIKE '%$SearchTerm%')";
            }
            else {$sql_param .= " AND $SearchRow LIKE '%$SearchTerm%'";}
        }
    }

    $sql_issues = "SELECT issues.* FROM issues WHERE tpid = $topicid $sql_param ORDER BY issue_id DESC";
    echo $sql_issues;
    $result_issues = $db->query($sql_issues)->fetchAll();
?>