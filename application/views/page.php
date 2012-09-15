<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TrainScan | <?php echo $title; ?></title>
        <meta name="description" content="<?php echo $description; ?>">
        <meta name="viewport" content="width=940">
        
<?php echo $css_js; ?>
    </head>
    <body>
        <!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->
    <div class="container">
    <div class="row-fluid">
        
        <div class="navbar navbar-inverse">
             <div class="navbar-inner">
                <div class="container">
                   <!--  <a class="brand" href="#">TrainScan</a> -->

                    <ul class="nav">
                        <li><a href="/">Comparatore</a></li>
                        <li <?php echo $active1; ?>><a href="/perche-noi">Perchè noi</a></li>
                        <li <?php echo $active2; ?>><a href="/come-funziona">Come funziona</a></li>
                        <li <?php echo $active3; ?>><a href="/contatti">Contatti</a></li>
                        <li <?php echo $active4; ?>><a href="/feedback">Feedback</a></li>
                    </ul>

                </div>
            </div>
        </div>
        <?php echo $content; ?>
    </div>
    </div><!-- fine container fluid -->


        <script>
            var _gaq=[['_setAccount','UA-20963057-20'],['_setDomainName', 'trainscan.it'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>