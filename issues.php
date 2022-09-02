<?php 
    $siteTitle = 'Issues | '.$endpoints[0].'/'.$endpoints[1];
    require 'components/header.php'; 
?>

<?php
    $sqltopic = $endpoints[1];
    $sql = "SELECT topics.*, channels.* FROM topics NATURAL JOIN channels WHERE topics.topic = '$sqltopic'";
    $result = $db->prepare($sql);
    $result->execute();
    $info = $result->fetch();

    include 'components/topic-header.php';
?>

<div class="container container-xl mt-4">
    <div class="row searchbar">
        <div class="col-md-8 querybox">
            <div class="input-group">
                <span class="input-group-text form-control-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M11.5 7a4.499 4.499 0 11-8.998 0A4.499 4.499 0 0111.5 7zm-.82 4.74a6 6 0 111.06-1.06l3.04 3.04a.75.75 0 11-1.06 1.06l-3.04-3.04z"></path>
                    </svg>
                </span>
                <select name="filter-issues" class="form-select form-select-sm">
                    <option value="open">Offene Issues</option>
                    <option value="all">Alle anzeigen</option>
                    <option value="closed">Erledigte Issues</option>
                </select>
                <input type="text" name="q" id="search-issues" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-4 search-nav">
            <div class="btn-group">
                <a href="" id="labels" class="btn btn-sm btn-outline-primary d-flex" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M2.5 7.775V2.75a.25.25 0 01.25-.25h5.025a.25.25 0 01.177.073l6.25 6.25a.25.25 0 010 .354l-5.025 5.025a.25.25 0 01-.354 0l-6.25-6.25a.25.25 0 01-.073-.177zm-1.5 0V2.75C1 1.784 1.784 1 2.75 1h5.025c.464 0 .91.184 1.238.513l6.25 6.25a1.75 1.75 0 010 2.474l-5.026 5.026a1.75 1.75 0 01-2.474 0l-6.25-6.25A1.75 1.75 0 011 7.775zM6 5a1 1 0 100 2 1 1 0 000-2z"></path>
                    </svg>
                     Labels 
                    <span class="badge rounded-pill text-bg-primary">x</span>
                </a>
                <a href="" id="milestones" class="btn btn-sm btn-outline-primary d-flex" role="button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                        <path fill-rule="evenodd" d="M7.75 0a.75.75 0 01.75.75V3h3.634c.414 0 .814.147 1.13.414l2.07 1.75a1.75 1.75 0 010 2.672l-2.07 1.75a1.75 1.75 0 01-1.13.414H8.5v5.25a.75.75 0 11-1.5 0V10H2.75A1.75 1.75 0 011 8.25v-3.5C1 3.784 1.784 3 2.75 3H7V.75A.75.75 0 017.75 0zm0 8.5h4.384a.25.25 0 00.161-.06l2.07-1.75a.25.25 0 000-.38l-2.07-1.75a.25.25 0 00-.161-.06H2.75a.25.25 0 00-.25.25v3.5c0 .138.112.25.25.25h5z"></path>
                    </svg>
                     Meilensteine 
                    <span class="badge rounded-pill text-bg-primary">x</span>
                </a>
            </div>
            <a href="" class="btn btn-sm btn-primary" role="button" id="new-issue">Neuer Issue</a>
        </div>
    </div>
</div>