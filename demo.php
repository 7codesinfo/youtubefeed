<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>PHP Youtube Gallery With Bootstrap</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <style type="text/css">
            .thumbnail {
                margin-bottom:14px;
            }
            .mygrid{
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>PHP Youtube Gallery With Bootstrap</h1>
            <hr>
            <h2></h2>
            <p>To request a feed of all videos uploaded by another user, send a GET request to the following URL. This request does not require authentication.</p>
            <p><code>https://gdata.youtube.com/feeds/api/users/userId/uploads</code></p>
            <p>In the URL above, you should replace the text userId with the user's YouTube user ID.</p>
            
            <div class="row">
                <?php
                $feedURL = 'https://gdata.youtube.com/feeds/api/users/yahoo/uploads';
                $sxml = simplexml_load_file($feedURL);

                $feedURLI = 0;
                foreach ($sxml->entry as $entry) {
                    $feedURLI++;
                    if ($feedURLI == 15) {
                        break;
                    }
                    $media = $entry->children('http://search.yahoo.com/mrss/');

                    // get video player URL
                    $attrs = $media->group->player->attributes();
                    $watch = $attrs['url'];

                    // get video thumbnail
                    $attrs = $media->group->thumbnail[0]->attributes();
                    $thumbnail = $attrs['url'];

                    // get video length
                    $yt = $media->children('http://gdata.youtube.com/schemas/2007');
                    $attrs = $yt->duration->attributes();
                    $length = $attrs['seconds'];

                    // get viewer statistics
                    $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
                    $attrs = $yt->statistics->attributes();
                    $viewCount = $attrs['viewCount'];

                    // get video ratings
                    $gd = $entry->children('http://schemas.google.com/g/2005');
                    if ($gd->rating) {
                        $attrs = $gd->rating->attributes();
                        $rating = $attrs['average'];
                    } else {
                        $rating = 0;
                    }
                    ?>
                    <div class="col-lg-4 col-sm-6 col-xs-12 mygrid">
                        <a href="<?php echo $watch; ?>" target="_blank">
                            <img src="<?php echo $thumbnail; ?>" class="thumbnail img-responsive" alt="<?php echo $media->group->title; ?>">
                        </a>
                        <a href="<?php echo $watch; ?>" target="_blank"><?php echo $media->group->title; ?></a><br/>
                        <span>Views: <?php echo $viewCount; ?></span> 
                        <span class="attr" style="float:right;">Duration: <?php printf('%0.2f', $length / 60); ?> min.</span>
                    </div>
                <?php } ?>                
            </div>
            <hr>
        </div>
        <script type='text/javascript' src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type='text/javascript' src="bootstrap/bootstrap.min.js"></script>
    </body>
</html>