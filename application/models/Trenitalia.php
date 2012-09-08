<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trenitalia extends Scanner {
    
        public $_trenitaliaUrl = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.svc";
        public $_trenitaliaSoap = "https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.wsdl";
            public $_classi = array("S","C","P"); // Smart, Club, Prima
        public $_quotazioni = array();
        
        
        function __construct() {
             
        }
        
        
        public function getQuotazioni() {
            $this->postParametri();
        }
        
        
       
        public function postParametri() {
            
            
            $xml = '<?xml version: "1.0"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:SGMOBILEServiceSvc="http://tempuri.org/" xmlns:ns1="http://tempuri.org/Imports" xmlns:tns1="http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.ServiceContracts" xmlns:tns2="http://schemas.microsoft.com/2003/10/Serialization/" xmlns:tns3="http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.DataContracts" xmlns:tns4="http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.MessageContracts" xsl:version="1.0"><soap:Header><SGMOBILEServiceSvc:pHeader><tns1:UnitOfWork>0</tns1:UnitOfWork><tns1:Language>EN</tns1:Language></SGMOBILEServiceSvc:pHeader></soap:Header><soap:Body><SGMOBILEServiceSvc:InputSolutionsMobile><SGMOBILEServiceSvc:pInput><tns1:BoardingRailwayCode>83</tns1:BoardingRailwayCode><tns1:BoardingStationCode>1650</tns1:BoardingStationCode><tns1:ArrivalRailwayCode>83</tns1:ArrivalRailwayCode><tns1:ArrivalStationCode>6998</tns1:ArrivalStationCode><tns1:DepartureDateTime>08/09/2012-12:45:00</tns1:DepartureDateTime></SGMOBILEServiceSvc:pInput></SGMOBILEServiceSvc:InputSolutionsMobile></soap:Body></soap:Envelope>';
            
            
            $headers = array( "Host: stargate.iphone.trenitalia.com",                                                                                                                                               
                                "User-Agent: wsdl2objc",                                                                                                                                                                     
                                "Accept: */*",                                                                                                                                                                           
                                "SOAPAction: http://tempuri.org/ISGMOBILEService/InfoSolutionsMobile",                                                                                                                       
                                "Content-Type: text/xml; charset=utf-8",                                                                                                                                                       
                                "Accept-Language: en-us",                                                                                                                                                                         
                                "Accept-Encoding: gzip, deflate",                                                                                                                                                                 
                                "Connection: keep-alive",                                                                                                                                                                    
                                "Proxy-Connection: keep-alive",                                                                                                                                                                    
                                "content-length: ".strlen($xml)
                );var_dump($headers);
            $this->curl->create($this->_trenitaliaUrl);
            $this->curl->option(CURLOPT_SSL_VERIFYPEER, false);
            $this->curl->option(CURLOPT_RETURNTRANSFER, true);
            //$this->curl->option(CURLOPT_USERAGENT, 'wsdl2objc');
            $this->curl->option(CURLOPT_HTTPHEADER, $headers);
            $this->curl->option(CURLOPT_HEADER, true);
            $this->curl->option(CURLOPT_VERBOSE, true); 
            $this->curl->option(CURLOPT_POST,true);
            
            $this->curl->option(CURLOPT_POSTFIELDS,urlencode($xml));
            
            //$this->curl->option(CURLOPT_TIMEOUT,1);
            //echo ($this->curl->execute()); 
             
            /*$this->_wsdl = tempnam ("/tmp", "tempWSDLnew");
            $url404 = '<xs:import schemaLocation="https://stargate.iphone.trenitalia.com:443/xsd1_mobilesolution.xsd" namespace="http://schemas.microsoft.com/2003/10/Serialization/"/>';
            
            file_put_contents($this->_wsdl,str_replace($url404, "", file_get_contents('https://stargate.iphone.trenitalia.com/xsd2_mobilesolution.xsd')));

            $client = new SoapClient(null, array('location' => $this->_wsdl, 'uri' => 'http://schemas.datacontract.org/2004/07/TSF.TI.NSV.Common.WCF.ServiceContracts'));
            var_dump($client);
            var_dump(file_get_contents($this->_wsdl));
            */
           $client = $this->load->library('nusoap',array('https://stargate.iphone.trenitalia.com/serviceMOBILESOLUTION.wsdl', 'wsdl'));
            //return $client->__getFunctions();
            
            
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