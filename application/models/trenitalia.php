<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trenitalia extends Scanner {
    
        public $_trenitaliaUrl = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.svc";
        public $_trenitaliaSoap = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.wsdl";
        public $_stazioni = array("Milano", "Bologna", "Firenze", "Roma", "Napoli", "Salerno");
        public $_quotazioni = array();
        
        
        function __construct() {
             
        }
        
        
        public function getQuotazioni() {
            //print_r($this->postParametri($this->generateXml()));
            $this->updateStazioni();
        }
        
        
       
        public function postParametri($xml, $soap = 'InfoSolutionsMobile') {
            
            
            $headers = array( "SOAPAction: http://tempuri.org/ISGMOBILEService/{$soap}",                                                                                                                       
                              "Content-Type: text/xml; charset=utf-8",                                                                                                                                                       
                              "content-length: ".strlen($xml)
                );
            $this->curl->create("https://stargate.iphone.trenitalia.com:443/servicemobilesolution.svc");
            $this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->option(CURLOPT_RETURNTRANSFER, true);
            $this->curl->option(CURLOPT_HTTPHEADER, $headers);
            $this->curl->option(CURLOPT_POST,true);
            $this->curl->option(CURLOPT_POSTFIELDS,($xml));
            return utf8_decode($this->curl->execute()); 
            
        }
	public function updateStazioni()
	{       
               $response = $this->postParametri($this->generateXmlStazioni('Milano'),'InfoAmbiguityStations');
               $xml = simplexml_load_string($response);
               $ns = $xml->getNamespaces(true);
               $soap = $xml->children($ns['s']);
               die(var_dump($soap));
                $stations = $soap->body->children($ns['a']);
                foreach ($stations->children() as $item)
                {
                  //This example just accesses the iid node but the others are all available.
                  echo $item . '<br />';
                }
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
                          <tsf:Language>EN</tsf:Language>
                       </tem:pHeader>
                    </soapenv:Header>
                    <soapenv:Body>
                       <tem:InputSolutionsMobile>
                          <!--Optional:-->
                          <tem:pInput>
                             <!--Optional:-->
                             <tsf:BoardingRailwayCode>83</tsf:BoardingRailwayCode>
                             <!--Optional:-->
                             <tsf:BoardingStationCode>1650</tsf:BoardingStationCode>
                             <!--Optional:-->
                             <tsf:ArrivalRailwayCode>83</tsf:ArrivalRailwayCode>
                             <!--Optional:-->
                             <tsf:ArrivalStationCode>6998</tsf:ArrivalStationCode>
                             <!--Optional:-->
                             <tsf:DepartureDateTime>09/09/2012-12:45:00</tsf:DepartureDateTime>
                          </tem:pInput>
                       </tem:InputSolutionsMobile>
                    </soapenv:Body>
                 </soapenv:Envelope>';
            
            return $xml;
            
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