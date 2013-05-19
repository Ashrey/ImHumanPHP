<?
class ImHuman{
	/**
	 * Param for generation of captcha
	 */
	protected $param = array(
		'size' => 45, //Size of text in points
		'font' => 'font.ttf', //name of font
		'text' => '', //string for show in captcha
	);
	
	/**
	 * Imagen resourse
	 */
	protected $img = null;
	
	/**
	 * Create new instance
	 */
	function __construct($text = null){
		$this->param['text'] = is_string($text) ? $text:self::generateText(5);
	}
	
	/**
	 * Set param
	 */
	function set($key, $value){
		$this->param[$key] = $value;
	}
	
	/**
	 * Get param
	 */
	function get($key){
		return isset($this->param[$key]) ? $this->param[$key]:null;
	}
	
	/**
	 * Generate image
	 */
	function generate(){
		if(!function_exists('imagettfbbox')){
			throw new Exception('GD is required!');
		}
		//set font path
		$fontfile = __DIR__.'/'. $this->param['font'];
		//calculate image dimensions
		$details = imagettfbbox($this->param['size'], 0, $fontfile, $this->param['text']);
		$image2d_x = $details[4];
		$image2d_y = $this->param['size'] * 1.2;
		//create imagen
		$image2d = imagecreatetruecolor($image2d_x, $image2d_y);
		//create palete of colors
		$palette = array(
			imagecolorallocate($image2d, 255, 255, 255) //it's white
		);
		$init = 20;
		$end = 250;
		$size = (int)$this->param['size'] *.4;
		//add ramdon color to palette
		for($i=1;$i<$size;$i++)
			$palette[] =
			 imagecolorallocate($image2d, rand($init,$end), rand($init,$end), rand($init,$end));
		imagettftext($image2d, $this->param['size'], 0, 2,
			$this->param['size'], $palette[0], $fontfile, $this->param['text']);
		//remove white color of palette
		array_shift($palette);
		
		/*Add Noise*/
		//Along X axis
		for($i=1;$i<$image2d_x;$i+=2){
			$color = $palette[array_rand($palette)];
			imageline ($image2d , $i , 0 , $i, $image2d_x, $color);
		}
		//Along Y axis
		for($i=1;$i<$image2d_y;$i+=2){
			$color = $palette[array_rand($palette)];
			imageline ($image2d , 0 , $i , $image2d_x, $i, $color);
		}
		$this->img = $image2d;
	}
	/**
	 * Send image to browser
	 */
	function render(){
		header("Content-type: image/gif");
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Mon, 03 Apr 1977 11:05:00 GMT"); // Date in the very past, guess what it is
		imagegif($this->img);
	}
	
	/**
	 * Generate ramdon string
	 */
	static function  generateText($long = 3){
		return substr(
			str_shuffle(
				str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',$long)
			)
		,0,$long);
	}
}
?>
