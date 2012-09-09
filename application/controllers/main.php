<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

        
        function __construct() {
            parent::__construct();
            $this->load->helper('url');
            $this->load->library('assets');
            $this->load->model('scanner');
            $this->load->library('session');
        }
        
        
	public function index()
	{       
            $data['stazioni'] = implode('","',$this->italotreno->_stazioni);
            
            $this->_renderPage($data);
                
	}
        
        public function _trenitalia() {
            $this->trenitalia->setData('2012-09-25');
            $this->scanner->setData('2012-09-25');
            $this->trenitalia->setStazioni('Milano','Firenze');
            $this->scanner->setStazioni('Milano','Firenze');
            $data['idPreventivo'] = (int)$this->scanner->checkPreventivo();
            print_r($this->trenitalia->getQuotazioni($data['idPreventivo']));
        }
        
        
        public function ajaxQuotazioni() {
            
            
            $post = $this->input->post();
            if(!empty($post)) {      
                
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
                $this->scanner->getBothQuotazioni();
                $data['quotazioni'] = $this->scanner->getPreventivoResult($data['idPreventivo']);
                $data['quotazioni'] = $this->renderClassi($data['quotazioni']);
            } else $data['quotazioni'] = 'Nessun parametro passato';
            
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
                    }
                }
            }
            return $data;
        }
        
        protected function _renderPage($data) {
            
            $this->assets->add_js('bootstrap.min.js');
            $this->assets->add_js('main.js');
            $this->assets->add_css('bootstrap.min.css');
            $this->assets->add_external_js('http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js');
            
            $data['css_js'] = $this->assets->render_css_js();
            $this->load->view('home', $data);
        }
      
}
/*
 * 
 */
/* End of file main.php */
/* Location: ./application/controllers/main.php */