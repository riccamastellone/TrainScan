<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TrainScan</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        
<?php echo $css_js; ?>
    </head>
    <body>
        <!--[if lt IE 7]>
<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
<![endif]-->
    <div class="container">
        <h2>TrainScanner</h2>
            <form method="post">
                <div class="span4">
                    <label>Stazione di partenza</label>
                    <input type="text" placeholder="Milano P.G." name="stazionePartenza">
                    <span class="help-block">Inserisci qui la stazione da dove vuoi partire</span>
                    <label>Data di partenza</label>
                    <input type="text" placeholder="2012-10-12" name="dataPartenza">
                    <span class="help-block">Inserisci la data (yyyy-mm-gg)</span>
                </div>
                <div class="span4">
                    <label>Stazione di arrivo</label>
                    <input type="text" placeholder="Firenze SMN" name="stazioneArrivo">
                    <span class="help-block">Inserisci qui la stazione dove vuoi arrivare</span>
                </div>
                <div>
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
    </div>
        <?php echo print_r($content); ?>

        <script src="js/main.js"></script>

        <!--<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->
    </body>
</html>