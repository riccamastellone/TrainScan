<div class="alert alert-info alertRisultati">
   Totale treni trovati: <?php echo $risultati; ?> - Ultimo aggiornamento: <?php echo $lastUpdate; ?> 
   <a id="newLoaderCall" class="icon-refresh" href="javascript:getQuotazioni(1);"></a>
   <span id="newLoader" class="hidden"><img src="<?php echo base_url(); ?>assets/img/loader2.gif"><span>
               
</div>
<table class="table table-striped" id="resultsTable">
    <thead>
        <tr>
            <th>Operatore</th>
            <th>Numero Treno</th>
            <th>Partenza</th>
            <th>Arrivo</th>
            <th>Classe</th>
            <th>Prezzo</th>
            <th style="width:90px"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($quotazioni as $quotazione) { ?>
            <tr>
                <td><img src="<?php echo base_url().$quotazione["path_logo"]; ?>" title="<?php echo $quotazione["nome_operatore"]; ?>"></td>
                <td><?php echo $quotazione["codice_treno"]; ?></td>
                <td><?php echo substr($quotazione["partenza"], 0, -3); ?></td>
                <td><?php echo substr($quotazione["arrivo"], 0, -3); ?></td>
                <td><span dettagli="classe_<?php echo $quotazione["id_classe"]; ?>" class="dettagliClasse">
                    <?php echo $quotazione["nome_classe"]; ?></span>
                </td>
                <td><?php echo $quotazione["prezzo"]; ?> €</td>
                <td><button type="button" onclick="javascript:showDettagli(<?php echo $quotazione["result_id"]; ?>)" class="btn btn-inverse">Acquista</button></td>
            </tr>
       <?php } ?>
    </tbody>
</table>
