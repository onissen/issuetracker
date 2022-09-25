<?php 
    $siteTitle = 'Labels | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php include 'components/topic-header.php'; ?>
<div class="container container-xl mt-4">
    <div class="searchbar row">
        <form id="label-searchForm" class="querybox col-md-6 col-12" method="get">
            <div class="input-group">
                <span class="input-group-text form-control-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M11.5 7a4.499 4.499 0 11-8.998 0A4.499 4.499 0 0111.5 7zm-.82 4.74a6 6 0 111.06-1.06l3.04 3.04a.75.75 0 11-1.06 1.06l-3.04-3.04z"></path>
                    </svg>
                </span>
                <input type="text" name="search" id="search-labels" class="form-control form-control-sm" placeholder="Label suchen" autocomplete="off">
            </div>
        </form>
        <div class="col text-end">
            <a href="#" id="new-label" class="btn btn-primary btn-sm">
                Neues Label
            </a>
        </div>
    </div>

    <div class="issuelist-wrapper mt-3">
        <div class="issuelist-header d-flex">
            <div class="listnav row flex-auto w-100">
                <div class="d-none d-lg-block label-states col-md-3">
                    <span>x Labels</span>
                </div>
            </div>
            <div class="col-md-9 d-flex no-wrap justify-content-between justify-content-sm-start justify-content-lg-end action-menu">
            <div class="filterbar-item" id="sort">                       
                <a class="nav-link dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sortierung</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">A bis Z</a></li>
                    <li><a class="dropdown-item" href="#">Z bis A</a></li>
                    <li><a class="dropdown-item" href="#">Meiste Issues</a></li>
                    <li><a class="dropdown-item" href="#">Wenigste Issues</a></li>
                </ul>
            </div>

            </div>
        </div><!-- .issuelist-header -->
    </div>
</div>

<?php require 'components/footer.php' ?>