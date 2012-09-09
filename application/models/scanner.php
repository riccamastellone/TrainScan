<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scanner extends CI_Model {
    
        // Stazioni valide
	public $_stazioni = array("Milano", "Firenze", "Bologna", "Napoli", "Roma", "Salerno");        
        public $_debug = array();
        public $_interval = '2 HOUR'; // validità del preventivo
        
        function __construct() {
            
            $this->load->library('curl'); 
            $this->load->model('italotreno');
            $this->load->model('trenitalia');
        }
        
	public function setStazioni($origine,$destinazione) {
            if(!in_array($origine, $this->_stazioni) || !in_array($destinazione, $this->_stazioni))
                    throw new Exception("Stazioni fornite non corrette");
            $this->trenitalia->_stazioneOrigine = $origine;
            $this->trenitalia->_stazioneDestinazione = $destinazione;
            $this->italotreno->_stazioneOrigine = $origine;
            $this->italotreno->_stazioneDestinazione = $destinazione;
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
            
            $this->trenitalia->_dataPartenza = $this->_dataPartenza;
            $this->italotreno->_dataPartenza = $this->_dataPartenza;
            
        }
        
        public function getBothQuotazioni() {
            $this->italotreno->setPersone();
            $data['idPreventivo'] = (int)$this->checkPreventivo();
            $this->trenitalia->getQuotazioni($data['idPreventivo']);
            $this->italotreno->getQuotazioni($data['idPreventivo']);
            return $data['idPreventivo'];
        }
        public function checkPreventivo() {
            
            $datiPreventivo = array(
                'id_origine' => ($this->_stazioneOrigine),
                'id_destinazione' => ($this->_stazioneDestinazione),
                'data' => $this->dataHelper('year-month-day')
                );            
            $idPreventivo = $this->db->select('id')->from('preventivi')->where($datiPreventivo)->where('data_generazione >  DATE_SUB(now(), INTERVAL '.$this->_interval.')')->get()->result_array();
           
            if(!$idPreventivo) {
                $datiPreventivo = array(
                    'id_origine' => ($this->_stazioneOrigine),
                    'id_destinazione' => ($this->_stazioneDestinazione),
                    'data' => $this->dataHelper('year-month-day'),
                    'indirizzo_ip' => $_SERVER['REMOTE_ADDR']
                );
                $this->db->insert('preventivi',$datiPreventivo);
                $idPreventivo = $this->db->insert_id();
            } else $idPreventivo = $idPreventivo[0]['id'];
            return $idPreventivo;
        }
        
        public function getTimePreventivo($idPreventivo) {
            $query = $this->db->query("SELECT data_generazione FROM preventivi WHERE id = {$idPreventivo} LIMIT 1");
            $result = $query->result();
            return $result[0]->data_generazione;
        }
        
        public function getPreventivoResult($idPreventivo) {
            $query = $this->db->query("SELECT *, a.id AS result_id FROM preventivi_result AS a, operatori AS o WHERE  a.id_preventivo = {$idPreventivo} AND a.id_operatore = o.id
                ORDER BY a.prezzo ASC");
            $result = $query->result_array();
            return $result;
        }
        
        public function getClassi() {
            $query = $this->db->query("SELECT * FROM italo_classi ");
            $result[] = $query->result_array();
            $query = $this->db->query("SELECT * FROM trenitalia_classi ");
            $result[] = $query->result_array();
            return $result;
        }
        
        public function getDettaglioResult($idResult) {
            $query = $this->db->query("SELECT id_operatore FROM preventivi_result WHERE id = {$idResult} LIMIT 1")->result();
            $operatore = $query[0]->id_operatore;
            if($operatore == 'I') $tabella = 'italo'; else $tabella = 'trenitalia';
            $query = $this->db->query("SELECT * FROM preventivi_result AS a, operatori AS b,
                {$tabella}_classi AS c, preventivi AS d
                WHERE a.id_operatore = b.id AND a.id = {$idResult} AND a.id_classe = c.codice_classe AND a.id_preventivo = d.id LIMIT 1");
            $result = $query->result_array();
            return $result[0];
            
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
        public function _ago($tm) {
            if($this->config->item('glasgow')) {
                $cur_tm = time()-3600*6; 
            } else $cur_tm = time(); 
            $tm = strtotime($tm);
            $dif = ($cur_tm-$tm);
            $pds = array('secondo','minuto','ora','giorno','settimana','mese','anno','decade');
            $pdsPlurale = array('secondi','minuti','ore','giorni','settimane','mesi','anni','decadi');
            $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);

            for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

            $no = floor($no); 
            if($no <> 1) 
                $tempo = $pdsPlurale[$v];
            else  
                $tempo = $pds[$v]; 

            $x=sprintf("%d %s fa",$no,$tempo);
            return $x;
        }   
}// IF(a.id_operatore = 'I', b.codice_classe, c.codice_classe ) = a.id_classe AND
/*
 * 
 */
/* End of file scanner.php */
/* Location: ./application/models/scanner.php */