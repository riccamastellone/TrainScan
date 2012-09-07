<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItaloTreno extends Scanner {
    
        public $_italoUrl = "https://biglietti.italotreno.it/Booking_Acquisto_Ricerca.aspx";
        public $_fascePrezzo = array("S" => array("20","30","40","52"), "C" => array("42","52","73"), "P" => array("73","80"));
        public $_classi = array("S","C","P"); // Smart, Club, Prima
        public $_quotazioni = array();
        
        
        function __construct() {
             $this->load->library('curl'); 
        }
        
        
        public function getQuotazioni() {
            foreach($this->_classi as $classe){
                $this->_quotazioni[$this->classeHelper($classe)] = array();
                $this->setClasse($classe);
                $this->setSessionId();
                    foreach($this->_fascePrezzo[$classe] as $prezzo) {
                        $this->_quotazioni[$this->classeHelper($classe)][$prezzo] = array();
                        $this->setPrezzo($prezzo);
                        $json = json_decode($this->getJsonItalo(),true);
                        $json = $json['extendedJourneys'];
                        if(!empty($json))
                            foreach($json as $quotazione)  {
                                //if(!$this->in_array_r($quotazione['trainNumber'], $this->_quotazioni[$classe]))
                                    $this->_quotazioni[$this->classeHelper($classe)][$prezzo][] = $quotazione;
                            }
                }
            }
            
            
            return $this->_quotazioni;
        }
        
        /*
         * Recursive multidimensional in_array function
         */
        public function in_array_r($needle, $haystack, $strict = true) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }

            return false;
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
            $this->curl->option(CURLOPT_RETURNTRANSFER, false);
            $this->curl->option(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:15.0) Gecko/20100101 Firefox/15.0');
            $this->curl->option(CURLOPT_REFERER, $this->_italoUrl);
            $this->curl->option(CURLOPT_POSTFIELDS,$this->generateItaloPost());
            $this->curl->option(CURLOPT_TIMEOUT,1);
            return $this->curl->execute();
            
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
                $this->_debug['cookie'] = file_get_contents($this->ckfile);
                $this->_debug['curl_info2'] = $this->curl->info;
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
            $ret['ControlGroupBookingAcquistoCalendarView$AvailabilitySearchInputBookingAcquistoCalendarView%24DropDownListFarePreference'] = $this->_classe;
            
            // Adulti
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListPassengerType_ADT'] = $this->_adulti;
            // 0-3 anni senza posto
            $ret['BookingRicercaBookingAcquistoRicercaView$InfantTextBox'] = $this->_infanti;
            // Bambini 0-14 con posto
            $ret['BookingRicercaBookingAcquistoRicercaView$DropDownListPassengerType_CHD'] = $this->_bambini;
            
            $ret = http_build_query($ret);
            $ret .= '&__EVENTTARGET=&__EVENTARGUMENT=&__VIEWSTATE=%2FwEPDwUBMGQYAQUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgEFpgFNYXN0ZXJIZWFkZXJCb29raW5nQWNxdWlzdG9SaWNlcmNhVmlldyRNYXN0ZXJIZWFkZXJHbG9iYWxNZW51Qm9va2luZ0FjcXVpc3RvUmljZXJjYVZpZXckTWFzdGVySGVhZGVyR2xvYmFsTWVudU1lbWJlckxvZ2luQm9va2luZ0FjcXVpc3RvUmljZXJjYVZpZXckQ2hlY2tCb3hSZW1lbWJlck1lSNjx0e%2BH1XkWwQmW4oDfIpK0mVg%3D&pageToken=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24PAXFIRSTNAME1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24PAXLASTNAME1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingRetrieveInputBookingAcquistoRicercaView%24CONFIRMATIONNUMBER1=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuMemberLoginBookingAcquistoRicercaView%24TextBoxUserID=&MasterHeaderBookingAcquistoRicercaView%24MasterHeaderGlobalMenuBookingAcquistoRicercaView%24MasterHeaderGlobalMenuMemberLoginBookingAcquistoRicercaView%24PasswordFieldPassword=&BookingRicercaBookingAcquistoRicercaView%24DropDownListFareTypes=ST&BookingRicercaBookingAcquistoRicercaView%24RadioButtonMarketStructure=OneWay&date_picker=&date_picker=&BookingRicercaBookingAcquistoRicercaView%24PromoCodeBookingAcquistoRicercaView%24TextBoxPromoCode=&BookingRicercaBookingAcquistoRicercaView%24ButtonSubmit=Continua&BookingRicercaBookingAcquistoRicercaView%24DropDownListSearchBy=columnView';
            $this->_debug['post'] = $ret;
            return $ret;
        }
        
        public function generateUrl() {
            $url = "https://biglietti.italotreno.it/Booking_Calendar_Ajax.aspx?dateMarketId=market_0_date_2012_8_6&marketIndex=0&year={$this->dataHelper('year')}&month={$this->dataHelper('month')}&day={$this->dataHelper('day')}&price={$this->_prezzo}%2C00";
            $this->_debug['ajax_url'] = $url;
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