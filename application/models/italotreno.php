<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItaloTreno extends Scanner {
    
        public $_italoUrl = "https://biglietti.italotreno.it/Booking_Acquisto_Ricerca.aspx";
        public $_fascePrezzo = array("S" => array("20","30","40","52"), "P" => array("42","52","73"), "C" => array("73","80"));
        public $_classi = array("S","P","C"); // Smart, Prima, Prima
        public $_quotazioni = array();
        
        
        function __construct() {
            
        }
        

        
        public function getPreventivoResult($idPreventivo) {
            $query = $this->db->query("SELECT * FROM preventivi_result 
                AS a, italo_classi AS b WHERE a.id_preventivo = {$idPreventivo} AND a.id_classe = b.codice_classe
                ORDER BY a.prezzo ASC");
            $result = $query->result_array();
            return $result;
        }
        
        public function getQuotazioniRaw($idPreventivo) {
            
            foreach($this->_classi as $classe){
                $this->setClasse($classe);
                $this->setSessionId();
                    foreach($this->_fascePrezzo[$classe] as $prezzo) {
                        $this->setPrezzo($prezzo);
                        $json = json_decode($this->getJsonItalo(),true);
                        $json = $json['extendedJourneys'];
                        if(!empty($json))
                            foreach($json as $quotazione)  {
                                $dati = array(
                                    'id_preventivo' => $idPreventivo,
                                    'codice_treno' => $quotazione['trainNumber'],
                                    'id_classe' => $classe
                                );  
                                $check = $this->db->get_where('preventivi_result',$dati)->num_rows();
                                if(!$check) {
                                    $sql = array(
                                        'id_preventivo' => $idPreventivo,
                                        'codice_treno' => $quotazione['trainNumber'],
                                        'partenza' => date('H:i:s', strtotime($quotazione['departureTime'])),
                                        'arrivo' => date('H:i:s', strtotime($quotazione['arrivalTime'])),
                                        'id_classe' => $classe,
                                        'prezzo' => $prezzo,
                                        'id_operatore' => 'I'
                                        );
                                    $this->db->insert('preventivi_result', $sql); 
                                
                                }
                            }
                }
            }
            
        }
        
        public function getQuotazioni() {
            
            $datiPreventivo = array(
                'id_origine' => $this->stazioneHelper($this->_stazioneOrigine),
                'id_destinazione' => $this->stazioneHelper($this->_stazioneDestinazione),
                'data' => $this->dataHelper('year-month-day')
                );            
            $idPreventivo = $this->db->select('id')->from('preventivi')->where($datiPreventivo)->where('data_generazione >  DATE_SUB(now(), INTERVAL 30 MINUTE)')->get()->result_array();
           
            if(!$idPreventivo) {
                $datiPreventivo = array(
                    'id_origine' => $this->stazioneHelper($this->_stazioneOrigine),
                    'id_destinazione' => $this->stazioneHelper($this->_stazioneDestinazione),
                    'data' => $this->dataHelper('year-month-day'),
                    'indirizzo_ip' => $_SERVER['REMOTE_ADDR']
                );
                $this->db->insert('preventivi',$datiPreventivo);
                $idPreventivo = $this->db->insert_id();
                $this->getQuotazioniRaw($idPreventivo);
            } else {
                $idPreventivo = $idPreventivo[0]['id'];
               
            }

            $quotazioni = $this->getPreventivoResult($idPreventivo);
            //print_r($this->_debug);
            return $quotazioni;
            
            
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
	public function getJsonItalo()
	{       
            
            /* La genialità di NTV ci obbliga a mandare una finta richiesta alla pagina stessa per ottenre
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
        
        public function stazioneHelper($stazione) {
            
            switch ($stazione) {
                case "Milano P.G.":
                    return "MPG";
                    break;
                case "Firenze SMN":
                    return "SMN";
                    break;
                case "Bologna":
                    return "BC_";
                    break;
                case "Milano Rog.":
                    return "MG_";
                    break;
                case "Napoli C.":
                    return "NAC";
                    break;
                case "Roma Ost.":
                    return "OST";
                    break;
                case "Roma Tib.":
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