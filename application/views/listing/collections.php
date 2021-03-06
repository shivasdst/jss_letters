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

<?php 
    $hiddenData = $data["hidden"]; 
    unset($data["hidden"]);
?>  
<div id="grid" class="container-fluid">
    <div id="posts">
<?php foreach ($data as $row) { ?>
        <div class="post">
            <a href="<?=BASE_URL?>describe/collection/<?=$row['collectionID']?>" title="View Collection">
                <div class="fixOverlayDiv">
                    <img class="img-responsive" src="<?=$row['Randomimage']?>">
                    <div class="OverlayText"><?=$row['Albumcount']?>
                    <?php if(($row['Albumcount']) > 1) echo "ಸಂಗ್ರಹಣೆಗಳು"; else echo "ಸಂಗ್ರಹ"; ?><br /><small><?=$row['name']?></small> <span class="link"><i class="fa fa-link"></i></span></div>
                </div>
            </a>
        </div>
<?php } ?>
    </div>
</div>
<div id="hidden-data">
    <?php echo $hiddenData; ?>
</div>
<div id="loader-icon"><img src="<?=STOCK_IMAGE_URL?>loading.gif" /><div>



<script>
$(document).ready(function(){

    var processing = false;

    function getresult(url) {
        processing = true;
        $.ajax({
            url: url,
            type: "GET",
            beforeSend: function(){
                $('#loader-icon').show();
            },
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

                    displayString = displayString + '<div class="post">';    
                    displayString = displayString + '<a href="' + <?php echo '"' . BASE_URL . '"'; ?> + 'describe/collection/'+ obj[i].collectionID + '" title="View Collection">';
                    displayString = displayString + '<div class="fixOverlayDiv">';
                    displayString = displayString + '<img class="img-responsive" src="' + obj[i].Randomimage + '">';
                    displayString = displayString + '<div class="OverlayText">' + obj[i].Albumcount; 
                    if(obj[i].Albumcount > 1){
                        displayString = displayString + " ಸಂಗ್ರಹಣೆಗಳು";
                    }    
                    else{
                      displayString = displayString + " ಸಂಗ್ರಹ";  
                    }
                    displayString = displayString + '<br /><small>' + obj[i].name + '</small> <span class="link"><i class="fa fa-link"></i></span></div>';
                    displayString = displayString + '</div>';
                    displayString = displayString + '</a>'; 
                    displayString = displayString + '</div>';
                }   

                var $content = $(displayString); 
                $content.css('display','none');

                $grid.append($content).imagesLoaded(
                    function(){
                        $content.fadeIn(250);
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
        if ($(window).scrollTop() >= ($(document).height() - $(window).height()) * 0.65){
            if($(".lastpage").length == 0){
                var pagenum = parseInt($(".pagenum:last").val()) + 1;
                if(!processing)
                {
                    getresult(base_url+'listing/collections/?page='+pagenum);
                }   
            }
        }
    });
});     
</script>
