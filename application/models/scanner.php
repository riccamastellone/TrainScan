<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scanner extends CI_Model {
    
        // Stazioni valide
	public $_stazioni = array("Milano", "Firenze", "Bologna", "Napoli", "Roma", "Salerno");        
        public $_debug = array();
        
        function __construct() {
            $this->load->library('curl'); 
        }
        
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
            if($data == null) {
                $date = date("Y-m-d");// current date
                $this->_dataPartenza = strtotime(date("Y-m-d", strtotime($date)) . " +2 day");
            } else
            $this->_dataPartenza = strtotime($data);
        }
        
        
        public function getPreventivoResult($idPreventivo) {
            $query = $this->db->query("SELECT * FROM preventivi_result 
                AS a, italo_classi AS b, trenitalia_classi AS c, operatori AS o WHERE  a.id_preventivo = {$idPreventivo} AND a.id_operatore = o.id
                ORDER BY a.prezzo ASC");
            $result = $query->result_array();
            return $result;
        }
}// IF(a.id_operatore = 'I', b.codice_classe, c.codice_classe ) = a.id_classe AND
/*
 * 
 */
/* End of file scanner.php */
/* Location: ./application/models/scanner.php */