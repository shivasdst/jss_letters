<div class="container">
    <div class="row first-row">
        <!-- Column 1 -->
        <div class="col-md-12 text-center">
            <ul class="list-inline sub-nav">
                <li><a href="<?=BASE_URL?>listing/albums">Letters</a></li>
                <li><a>·</a></li>
                <li><a href="#">Publications</a></li>
                <li><a>·</a></li>
                <li><a>Search</a></li>
                <li id="searchForm">
                    <form class="navbar-form" role="search" action="<?=BASE_URL?>search/field/" method="get">
                        <div class="input-group add-on">
                            <input type="text" class="form-control" placeholder="Keywords" name="description" id="description">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="grid" class="container-fluid">
    <div id="posts">
<?php foreach ($data as $row) { ?>
        <div class="post">
            <a href="<?=BASE_URL?>describe/collection/<?=$row['collectionID']?>" title="View Collection">
                <div class="fixOverlayDiv">
                    <img class="img-responsive" src="<?=$viewHelper->includeRandomThumbnail($row['albumList'][array_rand($row['albumList'])])?>">
                    <div class="OverlayText"><?=sizeof($row['albumList'])?>
                    <?php if(($row['albumList']) > 1) echo "Albums"; else echo "Album"; ?><br /><small><?=$row['name']?></small> <span class="link"><i class="fa fa-link"></i></span></div>
                </div>
            </a>
        </div>
<?php } ?>
    </div>
</div>



