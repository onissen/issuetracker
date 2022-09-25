<?php
    $sqltopic = $endpoints[1];
    $sql = "SELECT topics.*, channels.* FROM topics NATURAL JOIN channels WHERE topics.topic = '$sqltopic'";
    $result = $db->prepare($sql);
    $result->execute();
    $info = $result->fetch();
?>

<?php if (isset($info)) { ?>
    <div class="topic-header container-fluid pt-3">
        <div class="topic-headline px-3 px-md-4 px-lg-5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                <path fill-rule="evenodd" d="M6.368 1.01a.75.75 0 01.623.859L6.57 4.5h3.98l.46-2.868a.75.75 0 011.48.237L12.07 4.5h2.18a.75.75 0 010 1.5h-2.42l-.64 4h2.56a.75.75 0 010 1.5h-2.8l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H5.45l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H1.75a.75.75 0 010-1.5h2.42l.64-4H2.25a.75.75 0 010-1.5h2.8l.46-2.868a.75.75 0 01.858-.622zM9.67 10l.64-4H6.33l-.64 4h3.98z"></path>
            </svg>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $SiteURL.$info['channel'] ?>"><?php echo $info['channel'] ?></a></li>
                <li class="breadcrumb-item active" area-current="page"><a href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'] ?>"><?php echo $info['topic'] ?></a></li>
            </ol>
            <span class="badge rounded-pill label-muted-outline"><?php echo $info['visibility'] ?></span>
        </div>
        <div class="clearfix"></div>
        <nav class="topic-nav px-3 px-md-4 px-lg-5">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a id="issues_pill" class="nav-link" aria-current="page" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                            <path d="M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                            <path fill-rule="evenodd" d="M8 0a8 8 0 100 16A8 8 0 008 0zM1.5 8a6.5 6.5 0 1113 0 6.5 6.5 0 01-13 0z"></path>
                        </svg>
                        Issues
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php } else { ?>
    <div class="alert alert-danger m-3" role="alert">
        Achtung! Die Datenbankinhalte fehlen!
    </div>
<?php } ?>
<script>
    activeEndpoint = "<?php echo end($endpoints); ?>";
    activePill = activeEndpoint+'_pill';
    pill = document.getElementById(activePill);
    if (pill != null) {pill.classList.add('active');}
    else {document.getElementById('issues_pill').classList.add('active')}
</script>