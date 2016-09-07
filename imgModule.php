<?php
ini_set("display_errors",1);
error_reporting(E_ALL);

/*
 * creating modular paintings
 */
 
class imgModule
{
	private $src;
	private $w_src;
	private $h_src;
	private $w_out;
	private $h_out;
	private $config = array();
	private $type;
	private $imgout;
	private $imgsrcName;
	private $imgoutName = 'out123.png';
	
	
	function __construct($config)
	{
		if( !empty($config['modules']) ){
			foreach ($config['modules'] as &$m){
				foreach ($m as $key => &$value)
					$value = intval($value);
			}		
			$this->config = $config;
		}else{
			return false;
		}
	}
	
	
	function __destruct()
	{
		imagedestroy($this->src);
		imagedestroy($this->imgout);
	}
	
	
	public function make($srcName){
		$this->image($srcName);
		$dest = $this->config['modules'];
		foreach ($dest as $key => &$d) {
			if($key > 0)
				$d['x'] += $dest[$key-1]['w']+$dest[$key-1]['x'];
			$this->addImg($d);
		}
				
		$imgout = $this->createImg($this->w_out, $this->h_out);  
		imagecopyresampled($imgout, $this->imgout, 0, 0, 0, 0, $this->w_out, $this->h_out, $this->w_src, $this->h_src);
		
		$srcName = str_replace('.png','',$srcName);
		imagepng($imgout, __DIR__.'/output/'.$srcName.'.png');
	}


	protected function image($srcName){
		list($this->w_src, $this->h_src, $this->type) = getimagesize(__DIR__.'/input/'.$srcName);
		if($this->type==3){
			$this->src = imagecreatefrompng(__DIR__.'/input/'.$srcName);
		}elseif($this->type==2){
			$this->src = imagecreatefromjpeg(__DIR__.'/input/'.$srcName);	
		}
		if(isset($this->config['width'])){
			$this->w_out = $this->config['width'];	
			$this->h_out = $this->w_out/$this->w_src*$this->h_src;	
		}else{
			$this->w_out = $this->w_src;
			$this->h_out = $this->h_src;
		}
		$this->imgout = $this->createImg($this->w_src, $this->h_src);	  
	}
  
  
	protected function addImg($dest){
		$dest['w'] = round($dest['w']*$this->w_src*0.01);
		$dest['h'] = round($dest['h']*$this->h_src*0.01);
		$dest['x'] = round($dest['x']*$this->w_src*0.01);
		$dest['y'] = round($dest['y']*$this->h_src*0.01);
		
		$imgadd = $this->createImg($dest['w'], $dest['h']);
		imagecopy($imgadd, $this->src, 0, 0, $dest['x'], $dest['y'], $this->w_src, $this->h_src);
		imagecopyresampled($this->imgout, $imgadd, $dest['x'], $dest['y'], 0, 0, $dest['w'], $dest['h'], $dest['w'], $dest['h']);
		imagedestroy($imgadd);
	}
	
	
	protected function createImg($w,$h){
		var_dump($w);
		$img = imagecreatetruecolor($w, $h);
		$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagealphablending($img, false);
		imagesavealpha($img, true);
		imagefill($img, 0, 0, $transparent);
		return $img;  
	}
	
}



if(isset($_POST['config'])){
	$config = array();
	$config['width'] = 500;
	$config['modules'] = $_POST['config'];
	// x = margin-left, y = margin-top
	/*$config['modules'][] = array('w' => 17, 'h' => 60, 'x' => 1, 'y' => 0);
	$config['modules'][] = array('w' => 15.5, 'h' => 70, 'x' => 1.8, 'y' => 0);
	$config['modules'][] = array('w' => 26.25, 'h' => 86, 'x' => 1.8, 'y' => 0);
	$config['modules'][] = array('w' => 15.5, 'h' => 70, 'x' => 1.8, 'y' => 0);
	$config['modules'][] = array('w' => 17, 'h' => 60, 'x' => 1.8, 'y' => 0);*/
	$imgModule = new imgModule($config);
	$files = scandir(__DIR__.'/input');
	foreach($files as $file){
		if(preg_match('/\.[(png)|(PNG)|(jpg)|(jpeg)|(JPG)|(JPEG)]/', $file)){
			$imgModule->make($file);
		}
	}
}




