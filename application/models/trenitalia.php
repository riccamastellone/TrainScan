<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trenitalia extends Scanner {
    
        protected $_trenitaliaUrl = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.svc";
        protected $_trenitaliaSoap = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.wsdl";
        protected $_stazioni = array("Milano", "Bologna", "Firenze", "Roma", "Napoli", "Salerno");
        protected $_stazioneOrigine = '';
        protected $_stazioneDestinazione = '';
        protected $_dataPartenza = '';
        protected $_timeout = '15';
        
        function __construct() {
             
        }
        
        static function comparaPrezzo($a, $b) {
            if ($a['prezzo'] == $b['prezzo']) {
                return 0;
            }
            return ($a['prezzo'] < $b['prezzo']) ? -1 : 1;
        }
                
        public function getQuotazioniRaw($idPreventivo) {
            
            $quotazioniArray = $this->postParametri($this->generateXml());
            if(!is_array($quotazioniArray))
                return null;
            
            foreach($quotazioniArray as $quotazione)  {
                
                if($quotazione['SolutionTrainMaxCategory'] == 'FR FRECCIAROSSA') {
                    $prima = array();
                    $seconda = array();
                    $executive = array();
                    $business = array();
                    $premium = array();
                    $standard = array();
                    foreach($quotazione['fareSolutionsMobile']['FareSolutionsMobile'] as $fare) {
                        
                        if(isset($fare['SolPriceClass1']) && (int)$fare['SolPriceClass1'] > 1) {
                            $prima[] = array(
                                'prezzo' => (int)substr($fare['SolPriceClass1'],0,-2), 
                                'codice' => (int)$fare['OfferCode']);
                        }
                        if(isset($fare['SolPriceClass2']) && (int)$fare['SolPriceClass2'] > 1) {
                             $seconda[] = array(
                                'prezzo' => (int)substr($fare['SolPriceClass2'],0,-2),
                                'codice' => (int)$fare['OfferCode']);
                        }
                        if(isset($fare['SolPriceExecutive']) && (int)$fare['SolPriceExecutive'] > 1) {
                            $executive[] = array(
                                'prezzo' => (int)substr($fare['SolPriceExecutive'],0,-2),
                                'codice' => (int)$fare['OfferCode']);
                        }
                        if(isset($fare['SolPriceBusiness']) && (int)$fare['SolPriceBusiness'] > 1) {
                             $business[] = array(
                                'prezzo' => (int)substr($fare['SolPriceBusiness'],0,-2),
                                'codice' => (int)$fare['OfferCode']);
                        }
                        if(isset($fare['SolPricePremium']) && (int)$fare['SolPricePremium'] > 1) {
                            $premium[] = array(
                                'prezzo' => (int)substr($fare['SolPricePremium'],0,-2),
                                'codice' => (int)$fare['OfferCode']);
                        }
                        if(isset($fare['SolPriceStandard']) && (int)$fare['SolPriceStandard'] > 1) {
                            $standard[] = array(
                                'prezzo' => (int)substr($fare['SolPriceStandard'],0,-2),
                                'codice' => (int)$fare['OfferCode']);
                        }
                    }
                    if(!empty($prima)) {
                        usort($prima, array('Trenitalia', 'comparaPrezzo'));
                        if($prima[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '1',
                                'prezzo' => $prima[0]['prezzo'],
                                'id_operatore' => 'T',
                                'id_offerta' => $prima[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                       
                    } 
                    if(!empty($seconda)) {
                        usort($seconda, array('Trenitalia', 'comparaPrezzo'));
                        if($seconda[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '2',
                                'prezzo' => ($seconda[0]['prezzo']),
                                'id_operatore' => 'T',
                                'id_offerta' => $seconda[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                    }
                    if(!empty($executive)) {
                        usort($executive, array('Trenitalia', 'comparaPrezzo'));
                        if($executive[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '3',
                                'prezzo' => ($executive[0]['prezzo']),
                                'id_operatore' => 'T',
                                'id_offerta' => $executive[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                    }
                    if(!empty($business)) {
                        usort($business, array('Trenitalia', 'comparaPrezzo'));
                        if($business[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '4',
                                'prezzo' => ($business[0]['prezzo']),
                                'id_operatore' => 'T',
                                'id_offerta' => $business[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                    }
                    if(!empty($premium)) {
                        usort($premium, array('Trenitalia', 'comparaPrezzo'));
                        if($premium[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '5',
                                'prezzo' => ($premium[0]['prezzo']),
                                'id_operatore' => 'T',
                                'id_offerta' => $premium[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                    }
                    if(!empty($standard)) {
                        usort($standard, array('Trenitalia', 'comparaPrezzo'));
                        if($standard[0]['prezzo'] > 1) {
                            $sql = array(
                                'id_preventivo' => $idPreventivo,
                                'codice_treno' => $quotazione['solutionDetail']['SolutionDetail']['TrainNumber'],
                                'partenza' => date('H:i:s', strtotime($quotazione['SolutionDepartureTime'])),
                                'arrivo' => date('H:i:s', strtotime($quotazione['SolutionArrivalTime'])),
                                'id_classe' => '6',
                                'prezzo' => ($standard[0]['prezzo']),
                                'id_operatore' => 'T',
                                'id_offerta' => $standard[0]['codice'],
                                'durata' => date('H:i:s', strtotime($quotazione['SolutionTotalJourneyTime'])),
                                'id_partenza' => $quotazione['SolutionBoardingStationCode'],
                                'id_arrivo' => $quotazione['SolutionArrivalStationCode']
                                );
                            $this->db->insert('preventivi_result', $sql);
                        }
                    }
                }
            }
        }
        
        public function getPreventivoResultTrenitalia($idPreventivo) {
            $query = $this->db->query("SELECT * FROM preventivi_result WHERE id_preventivo = {$idPreventivo} AND id_operatore = 'T'");
            $result = $query->result_array();
            return $result;
        }
        
        public function getQuotazioni($idPreventivo) {
            $quotazioni = $this->getPreventivoResultTrenitalia($idPreventivo);
            if(empty($quotazioni)) {
                    $this->getQuotazioniRaw($idPreventivo);
                }
        }
        
        public function getPreventivoResult54($idPreventivo) {
            $query = $this->db->query("SELECT * FROM preventivi_result 
                AS a, trenitalia_classi AS b, trenitalia_tariffe AS c WHERE a.id_preventivo = {$idPreventivo} AND a.id_classe = b.codice_classe
                AND a.id_offerta = c.codice_offerta ORDER BY a.prezzo ASC");
            $result = $query->result_array();
            return $result;
        }
       
        public function postParametri($xml, $soap = 'InfoSolutionsMobile') {
            
            $client = new SoapClient(null, array('location' => "https://stargate.iphone.trenitalia.com:443/servicemobilesolution.svc",
                                                 'uri'      => "http://tempuri.org/ISGMOBILEService/InfoSolutionsMobile",
                                                 'connection_timeout' => $this->_timeout ) );
            $response = ($client->__doRequest($xml,'https://stargate.iphone.trenitalia.com:443/servicemobilesolution.svc','http://tempuri.org/ISGMOBILEService/InfoSolutionsMobile','1.2'));
            $arrayQuotazioni = $this->xml2array(preg_replace('/a:/', '', $response));
            if(!is_array($arrayQuotazioni)) {
                die('<p style="text-align:center;padding-top:20px;font-weight:bold">Server Trenitalia Down</p>');
            }
            $arrayQuotazioni = @$arrayQuotazioni['s:Envelope']['s:Body']['OutputSolutionsMobile']['pOutput']['MobileSolutions']['outputInfoSolutionsMobile'];
            
            if(empty($arrayQuotazioni))
                return null;
            
            return $arrayQuotazioni;
        }
	public function updateStazioni()
	{       
            $station = 'Milano';
                $response = $this->postParametri($this->generateXmlStazioni($station),'InfoAmbiguityStations');
                $response = $this->xml2array($response);
                $response = $response['s:Envelope']['s:Body']['OutputAmbiguityStations']['pOutputMobileStations']['a:MobileStations']['a:outputMobileStations'];
                foreach($response as $stazione) {
                    $data[] = array(
                        'railwaycode' => $stazione['a:RailwayCode'],
                        'stationcode' => $stazione['a:StationCode'],
                        'nome_stazione' => $stazione['a:Name'],
                    );
                }
                $this->db->insert_batch('trenitalia_stazioni', $data); 
            
	}
        
        public function generateXmlStazioni($stazione) {
            
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:tsf="http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.ServiceContracts">
                    <soapenv:Header>
                       <tem:pHeader>
                          <!--Optional:-->
                          <tsf:UnitOfWork>0</tsf:UnitOfWork>
                          <!--Optional:-->
                          <tsf:TCOMUserId></tsf:TCOMUserId>
                          <!--Optional:-->
                          <tsf:TCOMPassword></tsf:TCOMPassword>
                          <!--Optional:-->
                          <tsf:Language>IT</tsf:Language>
                       </tem:pHeader>
                    </soapenv:Header>
                    <soapenv:Body>
                       <tem:InputAmbiguityStations>
                          <!--Optional:-->
                          <tem:pInputMobileStations>
                             <!--Optional:-->
                             <tsf:Name>'.$stazione.'</tsf:Name>
                          </tem:pInputMobileStations>
                       </tem:InputAmbiguityStations>
                    </soapenv:Body>
                 </soapenv:Envelope>';
            
            return $xml;
            
        }
        
        public function generateXml() {
            // Se è oggi evitiamo problemi dandogli l'ora attuale come ora di inizio
            if(date("d/m/Y",$this->_dataPartenza) == date("d/m/Y")) {
                    $ora = date('H:i:00');
            }
            else { // Altrimenti le solite 5:00
                $ora = '05:00:00';
            }
            
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:tsf="http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.ServiceContracts">
                    <soapenv:Header>
                       <tem:pHeader>
                          <!--Optional:-->
                          <tsf:UnitOfWork>0</tsf:UnitOfWork>
                          <!--Optional:-->
                          <tsf:TCOMUserId></tsf:TCOMUserId>
                          <!--Optional:-->
                          <tsf:TCOMPassword></tsf:TCOMPassword>
                          <!--Optional:-->
                          <tsf:Language>IT</tsf:Language>
                       </tem:pHeader>
                    </soapenv:Header>
                    <soapenv:Body>
                       <tem:InputSolutionsMobile>
                          <!--Optional:-->
                          <tem:pInput>
                             <!--Optional:-->
                             <tsf:BoardingRailwayCode>83</tsf:BoardingRailwayCode>
                             <!--Optional:-->
                             <tsf:BoardingStationCode>'.$this->stazioneHelper($this->_stazioneOrigine).'</tsf:BoardingStationCode>
                             <!--Optional:-->
                             <tsf:ArrivalRailwayCode>83</tsf:ArrivalRailwayCode>
                             <!--Optional:-->
                             <tsf:ArrivalStationCode>'.$this->stazioneHelper($this->_stazioneDestinazione).'</tsf:ArrivalStationCode>
                             <!--Optional:-->
                             <tsf:DepartureDateTime>'.date("d/m/Y",$this->_dataPartenza).'-'.$ora.'</tsf:DepartureDateTime>
                          </tem:pInput>
                       </tem:InputSolutionsMobile>
                    </soapenv:Body>
                 </soapenv:Envelope>';

            return $xml;
            
        }
        
        public function getStationCode($stazione) {
            $sql =$this->db->get_where('trenitalia_stazioni', array('nome_stazione' => $stazione))->result_array();
            $sql = $sql[0]['stationcode'];
            return $sql;
        }
        
        public function getStationName($code) {
            $sql =$this->db->get_where('trenitalia_stazioni', array('stationcode' => $code))->result_array();
            $sql = $sql[0]['nome_stazione'];
            return $sql;
        }
        
        public function stazioneHelper($stazione) {
            
            switch ($stazione) {
                case "Milano":
                    return $this->getStationCode('Milano');
                    break;
                case "Firenze":
                    return $this->getStationCode('Firenze');
                    break;
                case "Bologna":
                    return $this->getStationCode('Bologna');
                    break;
                case "Milano Rog.":
                    return "MG_";
                    break;
                case "Napoli":
                    return $this->getStationCode('Napoli');
                    break;
                case "Roma":
                    return $this->getStationCode('Roma');
                    break;
                case "Roma Tib.":
                    return "RTB";
                    break;
                case "Salerno":
                    return $this->getStationCode('Salerno');
                    break;
            }
            
        }
        
        
        
        function xml2array($contents, $get_attributes=1, $priority = 'tag') { 
            if(!$contents) return array(); 

            if(!function_exists('xml_parser_create')) { 
                //print "'xml_parser_create()' function not found!"; 
                return array(); 
            } 

            //Get the XML parser of PHP - PHP must have this module for the parser to work 
            $parser = xml_parser_create(''); 
            xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss 
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); 
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
            xml_parse_into_struct($parser, trim($contents), $xml_values); 
            xml_parser_free($parser); 

            if(!$xml_values) return;//Hmm... 

            //Initializations 
            $xml_array = array(); 
            $parents = array(); 
            $opened_tags = array(); 
            $arr = array(); 

            $current = &$xml_array; //Refference 

            //Go through the tags. 
            $repeated_tag_index = array();//Multiple tags with same name will be turned into an array 
            foreach($xml_values as $data) { 
                unset($attributes,$value);//Remove existing values, or there will be trouble 

                //This command will extract these variables into the foreach scope 
                // tag(string), type(string), level(int), attributes(array). 
                extract($data);//We could use the array by itself, but this cooler. 

                $result = array(); 
                $attributes_data = array(); 

                if(isset($value)) { 
                    if($priority == 'tag') $result = $value; 
                    else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode 
                } 

                //Set the attributes too. 
                if(isset($attributes) and $get_attributes) { 
                    foreach($attributes as $attr => $val) { 
                        if($priority == 'tag') $attributes_data[$attr] = $val; 
                        else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
                    } 
                } 

                //See tag status and do the needed. 
                if($type == "open") {//The starting of the tag '<tag>' 
                    $parent[$level-1] = &$current; 
                    if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
                        $current[$tag] = $result; 
                        if($attributes_data) $current[$tag. '_attr'] = $attributes_data; 
                        $repeated_tag_index[$tag.'_'.$level] = 1; 

                        $current = &$current[$tag]; 

                    } else { //There was another element with the same tag name 

                        if(isset($current[$tag][0])) {//If there is a 0th element it is already an array 
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
                            $repeated_tag_index[$tag.'_'.$level]++; 
                        } else {//This section will make the value an array if multiple tags with the same name appear together 
                            $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array 
                            $repeated_tag_index[$tag.'_'.$level] = 2; 

                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 
                                $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                                unset($current[$tag.'_attr']); 
                            } 

                        } 
                        $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
                        $current = &$current[$tag][$last_item_index]; 
                    } 

                } elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
                    //See if the key is already taken. 
                    if(!isset($current[$tag])) { //New Key 
                        $current[$tag] = $result; 
                        $repeated_tag_index[$tag.'_'.$level] = 1; 
                        if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data; 

                    } else { //If taken, put all things inside a list(array) 
                        if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array... 

                            // ...push the new element into that array. 
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 

                            if($priority == 'tag' and $get_attributes and $attributes_data) { 
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                            } 
                            $repeated_tag_index[$tag.'_'.$level]++; 

                        } else { //If it is not an array... 
                            $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
                            $repeated_tag_index[$tag.'_'.$level] = 1; 
                            if($priority == 'tag' and $get_attributes) { 
                                if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well 

                                    $current[$tag]['0_attr'] = $current[$tag.'_attr']; 
                                    unset($current[$tag.'_attr']); 
                                } 

                                if($attributes_data) { 
                                    $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
                                } 
                            } 
                            $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken 
                        } 
                    } 

                } elseif($type == 'close') { //End of tag '</tag>' 
                    $current = &$parent[$level-1]; 
                } 
            } 

            return($xml_array); 
        }  
        
}
/*
 * 
 */
/* End of file italotreno.php */
/* Location: ./application/models/italotreno.php */