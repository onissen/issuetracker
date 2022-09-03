<?php if (isset($info)) { ?>
    <div class="topic-header container-fluid pt-3">
        <div class="topic-headline px-3 px-md-4 px-lg-5">
            <i class="text-muted octicon octicon-repo"></i>
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
                    <a class="nav-link active" aria-current="page" href="<?php echo $SiteURL.$info['channel'].'/'.$info['topic'] ?>">
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