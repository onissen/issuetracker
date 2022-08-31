<?php 
    $siteTitle = 'Issues | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php
    $sqltopic = $endpoints[1];
    $sql = "SELECT * FROM topics NATURAL JOIN channels WHERE topics.topic = $sqltopic";
    $result = $db->prepare($sql);
    $result->execute();
    $info = $result->fetch();
    
    print_r($info);
?>