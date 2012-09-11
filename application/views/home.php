<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>TrainScan | Il primo comparatore dell'alta velocità</title>
        <meta name="description" content="Scopri qual è il modo più economico di viaggiare attraverso l'Italia">
        <meta name="viewport" content="width=845">
        
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
                        <li class="active"><a href="#">Comparatore</a></li>
                        <li><a href="#">Perchè noi?</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="#">Contatti</a></li>
                    </ul>

                </div>
            </div>
        </div>
        
        <div class="page-header">
            <h1>TrainScan</h1>
            <p>Compara in maniera semplice e veloce le tariffe dell'alta velocità italiana</p>
        </div>
        
            <form id="formPost" method="post">
                <div class="span4">
                    <label>Stazione di partenza</label>
                    <input type="text" class="stazioni" placeholder="Milano" value="<?php echo $this->session->userdata('stazionePartenza'); ?>" data-provide="typeahead" data-items="4" autocomplete="off" name="stazionePartenza" data-source='["<?php echo $stazioni; ?>"]'>
                    <span class="help-block">Inserisci qui la stazione da dove vuoi partire</span>
                </div>
                <div class="span4">
                    <label>Stazione di arrivo</label>
                    <input type="text" class="stazioni" placeholder="Firenze" value="<?php echo $this->session->userdata('stazioneArrivo'); ?>"  data-provide="typeahead" data-items="4" autocomplete="off" name="stazioneArrivo" data-source='["<?php echo $stazioni; ?>"]'>
                    <span class="help-block">Inserisci qui la stazione dove vuoi arrivare</span>
                </div>
                <div class="span4">
                    <label>Data di partenza</label>
                    <input type="text" class="datapicker" placeholder="2012-10-12"  value="<?php echo $this->session->userdata('dataPartenza'); ?>"  name="dataPartenza">
                    <span class="help-block">Inserisci la data (yyyy-mm-gg)</span>
                </div>
                <div class="span12">
                    <button autocomplete="off" type="button" id="submitBtn" onclick="javascript:getQuotazioni();" class="btn btn-large btn-block">Cerca</button>
                </div>
            </form>
        <div class="clearfix"></div>
        <div id="loader"><img src="<?php echo base_url(); ?>assets/img/loader.gif"></div>
        <div id="results"></div>
        <div class="invisible">
            <?php 
            foreach($classi as $classiDoppia) {
                foreach($classiDoppia as $classe) {
                    echo "<div class='classe_{$classe['codice_classe']}'>";
                    echo '<span class="title"><strong>'.$classe['nome_classe'].'</strong></span>';
                    echo '<span class="content">'.nl2br($classe['descrizione']).'</span>';
                    echo '</div>';

                } 
            }?>
        </div>
    </div>
    </div><!-- fine container fluid -->
    <div class="modal hide" id="modalRiepilogo">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Dettagli del viaggio</h3>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn">Chiudi</a>
            <a href="javascript:alter('ciao minga')" class="btn btn-primary">Acquista</a>
          </div>
        </div>


        <!--<script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->
    </body>
</html>