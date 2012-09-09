<?php //var_dump($quotazione); ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Operatore</th>
            <th>Numero Treno</th>
            <th>Partenza</th>
            <th>Arrivo</th>
            <th>Classe</th>
            <th>Prezzo</th>
        </tr>
    </thead>
    <tbody>
        <tr>
                <td><img src="<?php echo base_url().$quotazione["path_logo"]; ?>" title="<?php echo $quotazione["nome_operatore"]; ?>"></td>
                <td><?php echo $quotazione["codice_treno"]; ?></td>
                <td><?php echo substr($quotazione["partenza"], 0, -3); ?></td>
                <td><?php echo substr($quotazione["arrivo"], 0, -3); ?></td>
                <td><?php echo $quotazione["nome_classe"]; ?></td>
                <td><?php echo $quotazione["prezzo"]; ?> €</td>
        </tr>
    </tbody>
</table>
<p>Partenza da <strong><?php echo $quotazione["id_origine"]; ?></strong> alle <strong><?php echo substr($quotazione["partenza"], 0, -3); ?></strong>
    e arrivo a <strong><?php echo $quotazione["id_destinazione"]; ?></strong> alle <strong><?php echo substr($quotazione["arrivo"], 0, -3); ?></strong>. 
    Durata totale del viaggio <strong><?php echo substr($quotazione["durata"], 0, -3); ?></strong></p>
<p><i class="icon-chevron-down"></i> <a data-toggle="collapse" href="javascript:void(0)"  data-target="#descrizioneClasse">Classe: <?php echo $quotazione["nome_classe"]; ?></a></p>
<div id="descrizioneClasse" class="collapse ">
    <p class="well">
        <?php echo nl2br($quotazione["descrizione"]); ?>
    </p>
</div>


