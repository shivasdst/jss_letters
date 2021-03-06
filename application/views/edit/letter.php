<div class="container">
    <div class="row first-row">
        <!-- Column 1 -->
        <div class="col-md-12 text-center">
            <ul class="list-inline sub-nav">
                <li><a href="<?=BASE_URL?>listing/collections">Albums</a></li>
                <li><a>·</a></li>
                <li><a href="<?=BASE_URL?>../jss_letters">Letters</a></li>
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
<div class="container">
    <div class="row gap-above-med">
        <div class="col-md-5">
            <?php $actualID = $data->id; ?>
            <div class="image-reduced-size">
                <?php $viewHelper->displayThumbs($data->albumID . '__' . $data->id);?>
            </div>
        </div>            
        <div class="col-md-7">
            <div class="image-desc-full">
                <form  method="POST" class="form-horizontal" role="form" id="updateData" action="<?=BASE_URL?>data/updateLetterJson/<?=$data->albumID?>" onsubmit="return validate()">
                    <?=$viewHelper->displayDataInForm(json_encode($data))?>
                </form>    
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=PUBLIC_URL?>js/addnewfields.js"></script>
<script type="text/javascript" src="<?=PUBLIC_URL?>js/validate.js"></script>
<script type="text/javascript" src="<?=PUBLIC_URL?>js/viewer.js"></script>
