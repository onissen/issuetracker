<?php
    $sqltopic = $endpoints[1];
    $sql = "SELECT topics.*, channels.* FROM topics NATURAL JOIN channels WHERE topics.topic = '$sqltopic'";
    $result = $db->prepare($sql);
    $result->execute();
    $info = $result->fetch();

    $tpid = $info['tpid'];

    $navAmounts = $db->query("SELECT COUNT(id) AS issues_nav FROM issues WHERE status = 'open' AND tpid = $tpid")->fetch();
?>

<?php if (isset($info)) {?>
    
    <div class="topic-header container-fluid pt-3">
        <div class="topic-headline px-3 px-md-4 px-lg-5">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                <path fill-rule="evenodd" d="M6.368 1.01a.75.75 0 01.623.859L6.57 4.5h3.98l.46-2.868a.75.75 0 011.48.237L12.07 4.5h2.18a.75.75 0 010 1.5h-2.42l-.64 4h2.56a.75.75 0 010 1.5h-2.8l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H5.45l-.46 2.869a.75.75 0 01-1.48-.237l.42-2.632H1.75a.75.75 0 010-1.5h2.42l.64-4H2.25a.75.75 0 010-1.5h2.8l.46-2.868a.75.75 0 01.858-.622zM9.67 10l.64-4H6.33l-.64 4h3.98z"></path>
            </svg>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $SiteURL.$info['channel'] ?>"><?php echo $info['channel'] ?></a></li>
                <li class="breadcrumb-item active" area-current="page"><a href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'] ?>"><?php echo $info['topic'] ?></a></li>
            </ol>
            <span class="badge rounded-pill label-muted-outline">
                <?php
                    if ($info['visibility'] == 'public') {echo 'Öffentliches Thema';}
                    elseif ($info['visibility'] == 'public-archive') {echo 'Öffentliches Archiv-Thema';}
                    elseif ($info['visibility'] == 'authenticated') {echo 'Internes Thema';}
                    elseif ($info['visibility'] == 'authenticated-archive') {echo 'Internes Archiv-Thema';}
                    else {echo $info['visibility'];}
                ?>    
            </span>
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
                        <?php if($navAmounts['issues_nav'] >= 1) { ?>
                            <span class="badge rounded-pill"><?php echo $navAmounts['issues_nav'] ?></span>
                        <?php } ?>
                    </a>
                </li>
                <?php if ($_SESSION['role'] == 'admin' OR $_SESSION['role'] == 'manager') { ?>
                    <li class="nav-item">
                        <a id="settings_pill" class="nav-link" aria-current="page" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'].'/settings' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd" d="M7.429 1.525a6.593 6.593 0 011.142 0c.036.003.108.036.137.146l.289 1.105c.147.56.55.967.997 1.189.174.086.341.183.501.29.417.278.97.423 1.53.27l1.102-.303c.11-.03.175.016.195.046.219.31.41.641.573.989.014.031.022.11-.059.19l-.815.806c-.411.406-.562.957-.53 1.456a4.588 4.588 0 010 .582c-.032.499.119 1.05.53 1.456l.815.806c.08.08.073.159.059.19a6.494 6.494 0 01-.573.99c-.02.029-.086.074-.195.045l-1.103-.303c-.559-.153-1.112-.008-1.529.27-.16.107-.327.204-.5.29-.449.222-.851.628-.998 1.189l-.289 1.105c-.029.11-.101.143-.137.146a6.613 6.613 0 01-1.142 0c-.036-.003-.108-.037-.137-.146l-.289-1.105c-.147-.56-.55-.967-.997-1.189a4.502 4.502 0 01-.501-.29c-.417-.278-.97-.423-1.53-.27l-1.102.303c-.11.03-.175-.016-.195-.046a6.492 6.492 0 01-.573-.989c-.014-.031-.022-.11.059-.19l.815-.806c.411-.406.562-.957.53-1.456a4.587 4.587 0 010-.582c.032-.499-.119-1.05-.53-1.456l-.815-.806c-.08-.08-.073-.159-.059-.19a6.44 6.44 0 01.573-.99c.02-.029.086-.075.195-.045l1.103.303c.559.153 1.112.008 1.529-.27.16-.107.327-.204.5-.29.449-.222.851-.628.998-1.189l.289-1.105c.029-.11.101-.143.137-.146zM8 0c-.236 0-.47.01-.701.03-.743.065-1.29.615-1.458 1.261l-.29 1.106c-.017.066-.078.158-.211.224a5.994 5.994 0 00-.668.386c-.123.082-.233.09-.3.071L3.27 2.776c-.644-.177-1.392.02-1.82.63a7.977 7.977 0 00-.704 1.217c-.315.675-.111 1.422.363 1.891l.815.806c.05.048.098.147.088.294a6.084 6.084 0 000 .772c.01.147-.038.246-.088.294l-.815.806c-.474.469-.678 1.216-.363 1.891.2.428.436.835.704 1.218.428.609 1.176.806 1.82.63l1.103-.303c.066-.019.176-.011.299.071.213.143.436.272.668.386.133.066.194.158.212.224l.289 1.106c.169.646.715 1.196 1.458 1.26a8.094 8.094 0 001.402 0c.743-.064 1.29-.614 1.458-1.26l.29-1.106c.017-.066.078-.158.211-.224a5.98 5.98 0 00.668-.386c.123-.082.233-.09.3-.071l1.102.302c.644.177 1.392-.02 1.82-.63.268-.382.505-.789.704-1.217.315-.675.111-1.422-.364-1.891l-.814-.806c-.05-.048-.098-.147-.088-.294a6.1 6.1 0 000-.772c-.01-.147.039-.246.088-.294l.814-.806c.475-.469.679-1.216.364-1.891a7.992 7.992 0 00-.704-1.218c-.428-.609-1.176-.806-1.82-.63l-1.103.303c-.066.019-.176.011-.299-.071a5.991 5.991 0 00-.668-.386c-.133-.066-.194-.158-.212-.224L10.16 1.29C9.99.645 9.444.095 8.701.031A8.094 8.094 0 008 0zm1.5 8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM11 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                    </li>
                <?php } ?>
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