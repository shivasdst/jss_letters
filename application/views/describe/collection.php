<?php $collectionID = $data[0]["collectionID"];?>
<script>
$(document).ready(function(){

    var processing = false;

    var collectionID = <?php echo  '"' . $collectionID . '"';  ?>;

    function getresult(url) {
        processing = true;
        $.ajax({
            url: url,
            type: "GET",
            complete: function(){
                $('#loader-icon').hide();
            },
            success: function(data){
                processing = true;
                // console.log(data);
                var gutter = parseInt(jQuery('.post').css('marginBottom'));
                var $grid = $('#posts').masonry({
                    gutter: gutter,
                    // specify itemSelector so stamps do get laid out
                    itemSelector: '.post',
                    columnWidth: '.post',
                    fitWidth: true
                });
                var obj = JSON.parse(data);
                var displayString = "";
                for(i=0;i<Object.keys(obj).length-1;i++)
                {                    
                    // console.log(obj[i].Event);    
                    // console.log(JSON.parse(obj[i].description).Title);

                    displayString = displayString + '<div class="post">';    
                    displayString = displayString + '<a href="' + <?php echo '"' . BASE_URL . '"'; ?> + 'describe/collection/'+ collectionID + '" title="View Album">';
                    displayString = displayString + '<div class="fixOverlayDiv">';
                    displayString = displayString + '<img class="img-responsive" src="' + obj[i].Randomimage + '">';
                    displayString = displayString + '<div class="OverlayText">' + obj[i].Photocount + '<br /><small>' + obj[i].Event + '</small> <span class="link"><i class="fa fa-link"></i></span></div>';
                    displayString = displayString + '</div>';
                    displayString = displayString + '<p class="image-desc">';
                    displayString = displayString + '<strong>' + obj[i].Title + '</strong>';    
                    displayString = displayString + "</p>";    
                    displayString = displayString + '</a>'; 
                    displayString = displayString + '</div>';

                }

                var $content = $(displayString); 
                $content.css('display','none');

                $grid.append($content).imagesLoaded(
                    function(){
                        $content.fadeIn(500);
                        $grid.masonry('appended', $content);
                        processing = false;
                    }
                );                                     

                displayString = "";

                $("#hidden-data").append(obj.hidden);

            },
            error: function(){console.log("Fail");}             
      });
    }
    $(window).scroll(function(){
        if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.7 ){
            if($(".lastpage").length == 0){
                var pagenum = parseInt($(".pagenum:last").val()) + 1;
                console.log(base_url +"Suersh");
                // alert(base_url+'describe/collection/'+ collectionID + '?page='+pagenum);
                if(!processing)
                {
                    getresult(base_url+'describe/collection/'+ collectionID + '?page='+pagenum);
                }
            }
        }
        if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.95 ){
			
			document.getElementById("loader-icon").show();
		}
    });
});     
</script>
<div class="container">
    <div class="row first-row">
        <!-- Column 1 -->
        <div class="col-md-12 text-center">
            <ul class="list-inline sub-nav">
                <li><a href="<?=BASE_URL?>listing/collections">Letters</a></li>
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
<?php 
    $hiddenData = $data["hidden"]; 
    unset($data["hidden"]);
?>    
<?php foreach ($data as $row) { ?>
        <div class="post">
            <a href="<?=BASE_URL?>listing/letters/<?=$row['albumID'];?>" title="View Album">
                <div class="fixOverlayDiv">
                    <img class="img-responsive" src="<?=$row['Randomimage'];?>">
                    <div class="OverlayText"><?=$row['Lettercount'];?><br /><small><?=$row['Event'];?></small> <span class="link"><i class="fa fa-link"></i></span></div>
                </div>
                <p class="image-desc">
                    <strong><?=$row['Title'];?></strong>
                </p>
            </a>
        </div>
<?php } ?>
    </div>
</div>
<div id="hidden-data">
    <?php echo $hiddenData; ?>
</div>
<div id="loader-icon"><img src="<?=STOCK_IMAGE_URL?>loading.gif" /><div>
