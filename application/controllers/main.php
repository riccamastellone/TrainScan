<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

        
        function __construct() {
            parent::__construct();
            $this->load->helper('url');
            $this->load->library('assets');
            $this->load->model('scanner');
            $this->load->model('italotreno');
            $this->load->model('trenitalia');
        }
        
        
	public function index()
	{       
            
            $data['stazioni'] = implode('","',$this->italotreno->_stazioni);
            
            $this->_renderPage($data);
                
	}
        
        public function trenitalia() {
            $this->trenitalia->getQuotazioni();
        }
        
        
        public function ajaxQuotazioni() {
            $post = $this->input->post();
            if(!empty($post)) {            
                $this->italotreno->setStazioni($this->input->post('stazionePartenza', TRUE),$this->input->post('stazioneArrivo', TRUE));
                $this->italotreno->setPersone();
                $this->italotreno->setData($this->input->post('dataPartenza', TRUE));
                $data['content'] = $this->italotreno->getQuotazioni();
            } else $data['content'] = 'Nessun parametro passato';
            
            print_r($data['content']);
            
        }
        
        protected function renderRow($data) {
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