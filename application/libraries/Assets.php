<?php

class Assets
{
	private	$css = array();
	private $js = array();
	private $js_ext = array();
	private $scripts = '';
	private $output = '';
	private $js_path = 'assets/js';
	private $css_path = 'assets/css';
	
	public function clear_css_js()
	{
		$this->css = array();
		$this->js = array();
		$this->js_ext = array();
	}
	
	public function add_js($file_name)
	{
		if(!in_array($file_name, $this->js))
			$this->js[] = $file_name;
	}
	
	public function scripts()
	{
		return $this->scripts;
	}
	
	public function add_external_js($file_name)
	{
		if(!in_array($file_name, $this->js_ext))
			$this->js_ext[] = $file_name;
	}
	
	public function add_css($file_name)
	{
		if(!in_array($file_name, $this->css))
			$this->css[] = $file_name;
	}
	
	public function add_script($script)
	{
		$this->scripts .= $script."\n"; 
	}
	
	public function render_css_js()
	{
		$css_path = base_url().$this->css_path.'/';
		$js_path = base_url().$this->js_path.'/';
		
		foreach($this->css as $c)
			$this->output .= "        <link rel=\"stylesheet\" href=\"".$css_path.$c."\"/>";
		$this->output .= "\n";
		foreach($this->js_ext as $j)
			$this->output .= "        <script src=\"$j\"></script>\n";
		foreach($this->js as $j)
			$this->output .= "        <script src=\"".$js_path.$j."\"></script>\n";
		$this->output .= "\n";
		return $this->output;
	}
}

?>