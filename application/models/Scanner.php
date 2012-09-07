<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scanner extends CI_Model {
    
        // Stazioni valide
	public $_stazioni = array("Milano P.G.", "Milano C.", "Firenze SMN", "Bologna", "Milano Rog.", "Napoli C.", "Roma Ost.", "Roma Tib.", "Salerno");        
        public $_debug = array();
        
	public function setStazioni($origine,$destinazione) {
            if(!in_array($origine, $this->_stazioni) || !in_array($destinazione, $this->_stazioni))
                    throw new Exception("Stazioni fornite non corrette");
            $this->_stazioneOrigine = $origine;
            $this->_stazioneDestinazione = $destinazione;
        }
        
        public function setPersone($adulti = 1, $bambini = 0, $infanti = 0) {
            $this->_adulti = $adulti;
            $this->_bambini = $bambini;
            $this->_infanti = $infanti;
        }
       
        public function setData($data = null) {
            $this->_dataPartenza = ($data != null) ? strtotime($data) : strtotime(date("Y-m-d"));
        }
}
/*
 * 
 */
/* End of file scanner.php */
/* Location: ./application/models/scanner.php */