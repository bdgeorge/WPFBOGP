<?php
/**
 * Rendering class for writing post social meta data in open graph format
 */
class OgpRenderOgp {
	
	/**
	 * @var OgpMetaModel
	 */
	var $_modelData;
		
	/**
	 * @var The current objects post (in WP object form)
	 */
	var $_post;
	
	/**
	 * @var Array of basic output formats for this renderer
	 */
	var $_formats = array(
		'url'			=> "\t<meta property='og:url' content='%s' />\n",
		'title'			=> "\t<meta property='og:title' content='%s' />\n",
		'site_name'		=> "\t<meta property='og:site_name' content='%s' />\n",		
		'description'	=> "\t<meta property='og:description' content='%s' />\n",
		'type'			=> "\t<meta property='og:type' content='%s' />\n",
		'image'			=> "\t<meta property='og:image' content='%s' />\n",
	);
	
	/**
	 * PHP4 style Constructor - Calls PHP5 Style Constructor
	 *
	 * @return WP_Http
	 */
	function OgpRenderOgp($model) {
		$this->__construct($model);
	}

	/**
	 * PHP5 style Constructor - Set up a model to use if available
	 *
	 * @return OgpMetaModel
	 */
	function __construct($model = null) {
		if($model)
			$this->useModel($model);
	}	
	
	/**
	 * Pass the renderer a model to use
	 * @param OgpMetaModel $model
	 * @return 
	 */
	function useModel($model){
		$this->_modelData = $model->getMeta();
		return $this;
	}
	
	function getAllMeta(){
		$_output = '';
		foreach ($this->_modelData as $itemKey => $itemValue){
			switch ($itemKey){				
				case 'images':
					if(array_key_exists('image', $this->_formats) && false != $itemValue){
						if(is_array($itemValue)){
							foreach($itemValue as $image){
								$_output .= sprintf($this->_formats['image'], $image);							
							}							
						} elseif($itemValue){
							$_output .= sprintf($this->_formats['image'], $itemValue);
						}						
					} else {
						$_output .= "\t<!-- There is no image here as you haven't set a default image in the plugin settings! -->\n"; 
					}								
					break;
				default:
					if(array_key_exists($itemKey, $this->_formats) && false != $itemValue){
						$_output .= sprintf($this->_formats[$itemKey], $itemValue);
					}
			}
		}
		return $_output;
	}	
}
