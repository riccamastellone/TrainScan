<?php 

function nl2li($str) {
    $str = "<ul style=\"list-style:none\"><li>" . $str ."</li></ul>"; 
    $str = str_replace("\n","</li>\n<li>",$str);
    return $str;
}

?>

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
<?php if($quotazione["descrizione_offerta"]) { ?>
<p><i class="icon-chevron-down"></i> <a data-toggle="collapse" href="javascript:void(0)"  data-target="#descrizioneOfferta"><strong>Tariffa</strong>: <?php echo $quotazione["nome_offerta"]; ?></a></p>
<div id="descrizioneOfferta" class="collapse ">
    <?php echo nl2li($quotazione["descrizione_offerta"]); ?>
</div>
<?php } else { ?>
<p><strong>Tariffa</strong>: <?php echo $quotazione["nome_offerta"]; ?></p>
<?php } ?>
<p><i class="icon-chevron-down"></i> <a data-toggle="collapse" href="javascript:void(0)"  data-target="#descrizioneClasse"><strong>Classe</strong>: <?php echo $quotazione["nome_classe"]; ?></a></p>
<div id="descrizioneClasse" class="collapse ">
    <?php echo nl2li($quotazione["descrizione"]); ?>
</div>




