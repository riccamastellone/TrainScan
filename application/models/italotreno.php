<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItaloTreno extends Scanner {
    
        public $_italoUrl = "https://biglietti.italotreno.it/Booking_Acquisto_Ricerca.aspx";
        
        public $_classi = array("S","P","C"); // Smart, Prima, Prima
        public $_stazioneOrigine = '';
        public $_stazioneDestinazione = '';
        public $_dataPartenza = '';
        public $_resultUrl = 'https://biglietti.italotreno.it/Booking_Acquisto_SelezioneTreno_A.aspx';
        
        function __construct() {
            
        }
        
        public function getQuotazioniRaw($idPreventivo) {
            
                $this->setClasse('');
                $this->setSessionId();
                $html = utf8_decode($this->getItaloResult());
                $this->load->library("html_dom");
                $this->html_dom->loadHTML($html,'utf-8');
                $tabella = $this->html_dom->find('#grigliaTreni',0);
                $treni = $tabella->find('h3');
                $dettaglioTreni = $tabella->find('div');
                foreach($treni as $id => $treno) {
                    
                    $treno = $treno->find('span.accordion_c',0);
                    
                    $orario = $treno->find('.accordion_c_one',0)->getInnerText();
                    list($trenoArray['orario']['partenza'],$trenoArray['orario']['arrivo']) = explode('<img src="images/NTV_Base/transparent.gif" class="arrowRightMedium" />', $orario);
                    
                    $trenoArray['durata'] = $treno->find('.accordion_c_two',0)->getInnerText();
                    $trenoArray['fermate'] = $treno->find('.accordion_c_three',0)->find('span',0)->getInnerText();
                    $trenoArray['numero'] = $treno->find('.accordion_c_four',0)->find('span',0)->getInnerText();
                    
                    
                    $tariffe = $dettaglioTreni[$id]->find('div.riga_tariffa');
                    
                    $tariffaMigliore['Smart']['valore'] = 9999;
                    $tariffaMigliore['Prima']['valore'] = 9999;
                    $tariffaMigliore['Club']['valore'] = 9999;
                    
                    foreach($tariffe as $id => $tariffa) {
                        $tariffaArray[$id]['nome'] = strip_tags($tariffa->find('.c_riga_tariffa',0)->find('div',0)->getInnerText());
                        
                        $tariffa->find('.c_riga_tariffa',0)->remove();
                        $costo = $tariffa->find('.c_riga_tariffa');
                        $tariffaArray[$id]['S'] = (int)strip_tags($costo[0]->getInnerText()); // Smart
                        $tariffaArray[$id]['P'] = (int)strip_tags($costo[1]->getInnerText()); // Prima
                        $tariffaArray[$id]['C'] = (int)strip_tags($costo[2]->getInnerText()); // Club
                        
                        // Ci interessa solo quella piu' conveniente
                        if($tariffaArray[$id]['S'] < $tariffaMigliore['Smart']['valore'] && $tariffaArray[$id]['S'] != 0) {
                            $tariffaMigliore['Smart']['valore'] =  $tariffaArray[$id]['S'];
                            $tariffaMigliore['Smart']['id_tariffa'] = $this->tariffeHelper($tariffaArray[$id]['nome']);
                        }
                        if($tariffaArray[$id]['S'] < $tariffaMigliore['Prima']['valore'] && $tariffaArray[$id]['P'] != 0) {
                            $tariffaMigliore['Prima']['valore'] =  $tariffaArray[$id]['P'];
                            $tariffaMigliore['Prima']['id_tariffa'] = $this->tariffeHelper($tariffaArray[$id]['nome']);
                        }
                        if($tariffaArray[$id]['S'] < $tariffaMigliore['Club']['valore'] && $tariffaArray[$id]['C'] != 0) {
                            $tariffaMigliore['Club']['valore'] =  $tariffaArray[$id]['C'];
                            $tariffaMigliore['Club']['id_tariffa'] = $this->tariffeHelper($tariffaArray[$id]['nome']);
                        }
                    }
                    $trenoArray['tariffe'] = $tariffaArray;
                    $trenoArray['tariffaMigliore'] = $tariffaMigliore;
                    
                    if($trenoArray['tariffaMigliore']['Smart']['valore'] != 9999) { // Esiste la Smart
                    $sql = array(
                        'id_preventivo' => $idPreventivo,
                        'codice_treno' => $trenoArray['numero'],
                        'partenza' => date('H:i:s', strtotime($trenoArray['orario']['partenza'])),
                        'arrivo' => date('H:i:s', strtotime($trenoArray['orario']['arrivo'])),
                        'durata' => $trenoArray['durata'],
                        'id_classe' => 'S',
                        'prezzo' => $trenoArray['tariffaMigliore']['Smart']['valore'],
                        'fermate' => $trenoArray['fermate'],
                        'id_operatore' => 'I',
                        'id_offerta' => $trenoArray['tariffaMigliore']['Smart']['id_tariffa']
                        );
                    $this->db->insert('preventivi_result', $sql); 
                    }
                    
                    if($trenoArray['tariffaMigliore']['Prima']['valore'] != 9999) { // Esiste la Prima
                    $sql = array(
                        'id_preventivo' => $idPreventivo,
                        'codice_treno' => $trenoArray['numero'],
                        'partenza' => date('H:i:s', strtotime($trenoArray['orario']['partenza'])),
                        'arrivo' => date('H:i:s', strtotime($trenoArray['orario']['arrivo'])),
                        'durata' => $trenoArray['durata'],
                        'id_classe' => 'P',
                        'prezzo' => $trenoArray['tariffaMigliore']['Prima']['valore'],
                        'fermate' => $trenoArray['fermate'],
                        'id_operatore' => 'I',
                        'id_offerta' => $trenoArray['tariffaMigliore']['Prima']['id_tariffa']
                        );
                    $this->db->insert('preventivi_result', $sql); 
                    }
                    
                    if($trenoArray['tariffaMigliore']['Club']['valore'] != 9999) { // Esiste la Club
                    $sql = array(
                        'id_preventivo' => $idPreventivo,
                        'codice_treno' => $trenoArray['numero'],
                        'partenza' => date('H:i:s', strtotime($trenoArray['orario']['partenza'])),
                        'arrivo' => date('H:i:s', strtotime($trenoArray['orario']['arrivo'])),
                        'durata' => $trenoArray['durata'],
                        'id_classe' => 'C',
                        'prezzo' => $trenoArray['tariffaMigliore']['Club']['valore'],
                        'fermate' => $trenoArray['fermate'],
                        'id_operatore' => 'I',
                        'id_offerta' => $trenoArray['tariffaMigliore']['Club']['id_tariffa']
                        );
                    $this->db->insert('preventivi_result', $sql); 
                    }
                    
                    
                }
            
        }
        public function getPreventivoResultItaloTreno($idPreventivo) {
            $query = $this->db->query("SELECT * FROM preventivi_result WHERE id_preventivo = {$idPreventivo} AND id_operatore = 'I'");
            $result = $query->result_array();
            return $result;
        }
        
        public function getQuotazioni($idPreventivo) {
            $quotazioni = $this->getPreventivoResultItaloTreno($idPreventivo);
            if(empty($quotazioni)) {
                    $this->getQuotazioniRaw($idPreventivo);
                }
        }
        
        
        public function setPrezzo($prezzo) {
            $this->_prezzo = $prezzo;
        }
        
        public function setClasse($classe) {
            $this->_classe = $classe;
        }
       
        public function setSessionId() {
            // set temp cookie file
            $this->ckfile = tempnam ("/tmp", "CURLCOOKIE");
            
            
            // todo: toglierelo
            $this->_classe = '';
            $this->curl->create($this->_italoUrl);
            $this->curl->option(CURLOPT_COOKIEJAR, $this->ckfile); 
            $this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->option(CURLOPT_RETURNTRANSFER, true);
            $this->curl->option(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0');
            $this->curl->option(CURLOPT_REFERER, $this->_italoUrl);
            $this->curl->option(CURLOPT_POSTFIELDS,$this->generateItaloPost());
            $this->curl->option(CURLOPT_TIMEOUT,1);
            $curl = $this->curl->execute();
            $this->_debug['cookie'][] = file_get_contents($this->ckfile);
            return $curl;
            
        }
        
        public function getTratta() {
            $iniziali = substr($this->_stazioneOrigine,0,1).substr($this->_stazioneDestinazione,0,1);
            return $iniziali;
        }
        
	public function getJsonItalo()
	{       
            
            /* La genialitÃ  di NTV ci obbliga a mandare una finta richiesta alla pagina stessa per ottenre
             * il cookie con ASP Session Id per poi fare una get su un'altra pagina per ricevere il JSON interessato
             * 
             * Non so se questo sia il modo migliore per ottenere le quotazioni ma l'unico che funziona per ora
             */
                
                
                $this->_debug['curl_info1'] = $this->curl->info;
                $this->curl->create($this->generateUrl());
                $this->curl->option(CURLOPT_COOKIEFILE,$this->ckfile); 
                $this->curl->option(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0');
                $this->curl->option(CURLOPT_RETURNTRANSFER, true);
                $this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
                $curl = $this->curl->execute();
                $this->_debug['curl_info2'][] = $this->curl->info;
                return $curl;
                
	}
        
        public function getItaloResult()
	{       
            
            /* La genialitÃ  di NTV ci obbliga a mandare una finta richiesta alla pagina stessa per ottenre
             * il cookie con ASP Session Id per poi fare una get su un'altra pagina per ricevere il JSON interessato
             * 
             * Non so se questo sia il modo migliore per ottenere le quotazioni ma l'unico che funziona per ora
             */
                
                
                $this->_debug['curl'][] = $this->curl->info;
                $this->curl->create($this->_resultUrl);
                $this->curl->option(CURLOPT_COOKIEFILE,$this->ckfile); 
                $this->curl->option(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0');
                $this->curl->option(CURLOPT_RETURNTRANSFER, true);
                $this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
                $curl = $this->curl->execute();
                $this->_debug['curl'][] = $this->curl->info;
                return $curl;
                
	}
        
        public function generateItaloPost() {
            
            
            $ret['originStation1'] = $this->stazioneHelper($this->_stazioneOrigine);
            $ret['BookingRicercaBookingAcquistoRicercaView$TextBoxMarketOrigin1'] = $this->stazioneHelper($this->_stazioneOrigine);
            $ret['destinationStation1'] = $this->stazioneHelper($this->_stazioneDestinazione);
            $ret['BookingRicercaBookingAcquistoRicercaView$TextBoxMarketDestination1'] = $this->stazioneHelper($this->_stazioneDestinazione);
            
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListMarketDay1'] = $this->dataHelper('day');
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListMarketMonth1'] =  $this->dataHelper('year-month');
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListMarketDay2'] = $this->dataHelper('day');
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListMarketMonth2'] =  $this->dataHelper('year-month');
            
            
            //Classe
            $ret['ControlGroupBookingAcquistoCalendarView$AvailabilitySearchInputBookingAcquistoCalendarView$DropDownListFarePreference'] = $this->_classe;
            
            // Adulti
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListPassengerType_ADT'] = $this->_adulti;
            // 0-3 anni senza posto
            $ret['BookingRicercaBookingAcquistoRicercaView$InfantTextBox'] = $this->_infanti;
            // Bambini 0-14 con posto
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListPassengerType_CHD'] = $this->_bambini;
            
            $ret = http_build_query($ret);
            $ret .= '&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFpgFNYXN0ZXJIZWFkZXJCb29raW5nQWNxdWlzdG9SaWNlcmNhVmlldyRNYXN0ZXJIZWFkZXJHbG9iYWxNZW51Qm9va2luZ0FjcXVpc3RvUmljZXJjYVZpZXckTWFzdGVySGVhZGVyR2xvYmFsTWVudU1lbWJlckxvZ2luQm9va2luZ0FjcXVpc3RvUmljZXJjYVZpZXckQ2hlY2tCb3hSZW1lbWJlck1lSNjx0e%2BH1XkWwQmW4oDfIpK0mVg%3D&pageToken=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24PAXFIRSTNAME1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24PAXLASTNAME1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24CONFIRMATIONNUMBER1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuMemberLoginBookingAcquistoRicercaView%24TextBoxUserID=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuMemberLoginBookingAcquistoRicercaView%24PasswordFieldPassword=&BookingRicercaBookingAcquistoRicercaView%24DropDownListFareTypes=ST&BookingRicercaBookingAcquistoRicercaView%24RadioButtonMarketStructure=OneWay&date_picker=&date_picker=&BookingRicercaBookingAcquistoRicercaView%24PromoCodeBookingAcquistoRicercaView%24TextBoxPromoCode=&BookingRicercaBookingAcquistoRicercaView%24ButtonSubmit=Continua&BookingRicercaBookingAcquistoRicercaView%24DropDownListSearchBy=columnView';
            $this->_debug['post'][] = $ret;
            return $ret;
        }
        
        public function generateUrl() {
            $url = "https://biglietti.italotreno.it/Booking_Calendar_Ajax.aspx?dateMarketId=market_0_date_2012_8_6&marketIndex=0&year={$this->dataHelper('year')}&month={$this->dataHelper('month')}&day={$this->dataHelper('day')}&price={$this->_prezzo}%2C00";
            $this->_debug['ajax_url'][] = $url;
            return $url;
        }
        
        public function tariffeHelper($nomeTariffa) {
            switch($nomeTariffa) {
                case 'Base':
                    $cod = 1;
                    break;
                case 'Economy': 
                    $cod = 2;
                    break;
                case 'Low Cost': 
                    $cod = 3;
                    break;
                default:
                    $cod = 4;
                    break;
            }
            return $cod;
            
        }
        
        public function stazioneHelper($stazione) {
            
            switch ($stazione) {
                case "Milano":
                    return "MPG";
                    break;
                case "Firenze":
                    return "SMN";
                    break;
                case "Bologna":
                    return "BC_";
                    break;
                case "Milano Rog.":
                    return "MG_";
                    break;
                case "Napoli":
                    return "NAC";
                    break;
                case "Roma Ost.":
                    return "OST";
                    break;
                case "Roma":
                    return "RTB";
                    break;
                case "Salerno":
                    return "SAL";
                    break;
            }
            
        }
        
        public function classeHelper($classe) {
            
            switch ($classe) {
                case "S":
                    return "Smart";
                    break;
                case "C":
                    return "Club";
                    break;
                case "P":
                    return "Prima";
                    break;
            }
            
        }
        
        public function dataHelper($case) {
            switch ($case) {
                case 'day':
                    return date("d",$this->_dataPartenza);
                    break;
                case 'year-month';
                    return date("Y-m",$this->_dataPartenza);
                    break;
                case 'year-month-day';
                    return date("Y-m-d",$this->_dataPartenza);
                    break;
                case 'month';
                    return date("m",$this->_dataPartenza);
                    break;
                case 'year';
                    return date("Y",$this->_dataPartenza);
                    break;
            }
        }
        
}
/*
 * 
 */
/* End of file italotreno.php */
/* Location: ./application/models/italotreno.php */