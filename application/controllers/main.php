<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
        
        
        function __construct() {
            parent::__construct();
            if($_SERVER['HTTP_HOST'] == 'testing.trainscan.it') {
                $this->config->set_item('glasgow', true);
            }
            $this->load->helper('url');
            $this->load->library('assets');
            $this->load->model('scanner');
            $this->load->library('session');
            date_default_timezone_set('Europe/Rome');
        }
        
	public function index()
	{       
            
            $data['stazioni'] = implode('","',$this->italotreno->_stazioni);
            $data['classi'] = $this->scanner->getClassi();
            $this->_renderHome($data);
                
	}
        public function page($page,$id = null) {
            
            $data['active1'] = '';
            $data['active2'] = '';
            $data['active3'] = '';
            $data['active4'] = '';
            
            switch($page) {
                case 'perche-noi':
                    $data['active1'] = 'class="active"';
                    $data['title'] = 'Perche scegliere noi';
                    $data['description'] = 'TrainScan si propone come il primo servizio di comparazione treni in Italia';
                    $data['content'] = $this->load->view('perche_noi', $data, true);
                    break;
                default:
                    show_404($page, FALSE);
            }
            $this->_renderPage($data);
        }
        
        // Debug diretto trenitalia
        public function _trenitalia() {
            $this->trenitalia->setData('2012-09-25');
            $this->scanner->setData('2012-09-25');
            $this->trenitalia->setStazioni('Milano','Firenze');
            $this->scanner->setStazioni('Milano','Firenze');
            $data['idPreventivo'] = (int)$this->scanner->checkPreventivo(1);
            print_r($this->trenitalia->getQuotazioni($data['idPreventivo']));
        }
        
        // Debug diretto italo
        public function _italotreno() {
            $this->italotreno->setPersone();
            $this->italotreno->setData('2012-09-25');
            $this->scanner->setData('2012-09-25');
            $this->italotreno->setStazioni('Milano','Firenze');
            $this->scanner->setStazioni('Milano','Firenze');
            $data['idPreventivo'] = (int)$this->scanner->checkPreventivo(0);
            print_r($this->italotreno->getQuotazioni($data['idPreventivo']));
        }
        
        public function dettagliPreventivo() {
            
            
            $idPreventivo = $this->input->get('idPreventivo', TRUE);
            if(!empty($idPreventivo)) { 
                $data['quotazione'] = $this->scanner->getDettaglioResult($idPreventivo);
                if($data['quotazione']['id_partenza'] > 0) {
                    $data['quotazione']['id_origine'] = $this->trenitalia->getStationName($data['quotazione']['id_partenza']);
                }
                if($data['quotazione']['id_arrivo'] > 0) {
                    $data['quotazione']['id_destinazione'] = $this->trenitalia->getStationName($data['quotazione']['id_arrivo']);
                }
            } else $data['contenuto'] = 'Nessuna quotazione selezionata';
            
            $this->load->view('dettagli', $data);
            
        }
        public function ajaxQuotazioni($page = 1) {
            
            
            $post = $this->input->post();
            if(!empty($post)) {      
                $cache = ($this->input->post('cache', TRUE) == '1')? true : false;
                $stazionePartenza = $this->input->post('stazionePartenza', TRUE);
                $stazioneArrivo = $this->input->post('stazioneArrivo', TRUE);
                $dataPartenza = $this->input->post('dataPartenza', TRUE);
            
                // Salviamo i dati del preventivo in sessione
                $newdata = array(
                   'stazionePartenza'  => $stazionePartenza,
                   'stazioneArrivo'     =>$stazioneArrivo,
                   'dataPartenza' => $dataPartenza
                );
                $this->session->set_userdata($newdata);
                $this->scanner->setStazioni($stazionePartenza,$stazioneArrivo);
                $this->scanner->setData($dataPartenza);
                $data['idPreventivo'] = $this->scanner->getBothQuotazioni($cache);
                $data['quotazioni'] = $this->scanner->getPreventivoResult($data['idPreventivo']);
                $data['risultati'] = $this->scanner->countPreventivoResult($data['idPreventivo']);
                $data['lastUpdate'] = $this->scanner->_ago($this->scanner->getTimePreventivo($data['idPreventivo']));
                $data['quotazioni'] = $this->renderClassi($data['quotazioni']);
            } else die('Nessun parametro passato');
            
            $this->load->view('row', $data);
            
        }
        
        public function renderClassi($data) {
            foreach($data as $key => $element) {
                if($data[$key]['id_operatore'] == 'I') {
                    switch ($data[$key]['id_classe']) {
                        case "S":
                            $data[$key]['nome_classe'] =  "Smart";
                            break;
                        case "C":
                            $data[$key]['nome_classe'] =  "Club";
                            break;
                        case "P":
                            $data[$key]['nome_classe'] =  "Prima";
                            break;
                    }
                } else if($data[$key]['id_operatore'] == 'T') {
                    switch ($data[$key]['id_classe']) {
                        case "1":
                            $data[$key]['nome_classe'] =  "1° Classe";
                            break;
                        case "2":
                            $data[$key]['nome_classe'] =  "2° Classe";
                            break;
                        case "3":
                            $data[$key]['nome_classe'] =  "Executive";
                            break;
                        case "4":
                            $data[$key]['nome_classe'] =  "Business";
                            break;
                        case "5":
                            $data[$key]['nome_classe'] =  "Premium";
                            break;
                        case "6":
                            $data[$key]['nome_classe'] =  "Standard";
                            break;
                    }
                    
                }
            }
            return $data;
        }
        
        protected function _renderHome($data) {
            $this->assets->add_js('jquery.ui.touch-punch.min.js');
            $this->assets->add_js('bootstrap.min.js');
            $this->assets->add_js('main.js');
            $this->assets->add_css('jquery-ui-1.8.16.custom.css');
            $this->assets->add_css('bootstrap.min.css');
            $this->assets->add_external_js('http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
            $this->assets->add_external_js('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
            
            
            $data['css_js'] = $this->assets->render_css_js();
            $this->load->view('home', $data);
        }
        
        protected function _renderPage($data) {
            $this->assets->add_js('jquery.ui.touch-punch.min.js');
            $this->assets->add_js('bootstrap.min.js');
            $this->assets->add_js('main.js');
            $this->assets->add_css('jquery-ui-1.8.16.custom.css');
            $this->assets->add_css('bootstrap.min.css');
            $this->assets->add_external_js('http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
            $this->assets->add_external_js('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js');
            
            $data['css_js'] = $this->assets->render_css_js();
            $this->load->view('page', $data);
        }
      
}
/*
 * 
 */
/* End of file main.php */
/* Location: ./application/controllers/main.php */