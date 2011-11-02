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
	 * @var Array of output formats for this renderer
	 */
	var $_formats = array(
		'sitename'		=> "\t<meta property='og:site_name' content='%s' />\n",
		'admins'		=> "\t<meta property='fb:admins' content='%s' />\n",
		'app_id'		=> "\t<meta property='fb:app_id' content='%s' />\n",
		'page_id'		=> "\t<meta gitproperty='fb:page_id' content='%s' />\n",
		'url'			=> "\t<meta property='og:url' content='%s' />\n",
		'title'			=> "\t<meta property='og:title' content='%s' />\n",
		'description'	=> "\t<meta property='og:description' content='%s' />\n",
		'type'			=> "\t<meta property='og:type' content='%s' />\n",
		'image'			=> "\t<meta property='og:image' content='%s' />\n",
	)
	
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
			$this->setModel($model);
	}	
	
	function useModel($model){
		$this->_modelData = $model->getData();
		return $this;
	}
	
	function getAllMeta(){
		$_output = '';
		foreach ($this->_modelData as $itemKey => $itemValue){
			switch ($itemKey){				
				case 'images':
					if(array_key_exists('image', $this->_formats)){
						foreach($itemValue as $image){
							$_output .= sprintf($this->_formats['image'], $mage);							
						}						
					}								
					break;
				default:
					if(array_key_exists($itemKey, $this->_formats)){
						$_output .= sprintf($this->_formats[$itemKey], $itemValue);
					}
			}
		}
	}
	
	function getSiteName(){
		if(array_key_exists('sitename', $this->_model))
			return sprintf($this->_formats['sitename'], $this->_model['sitename']);
		return false;
	}
	
	function getDescription(){
		if(array_key_exists('description', $this->_model))
			return "\t<meta property='og:description' content='" . $this->_model['description'] . "' />\n";
		return false;
	}	

	function getType(){
		if(array_key_exists('type', $this->_model))
			return "\t<meta property='og:type' content='" . $this->_model['description'] . "' />\n";
		return false;
	}	
	
	function getImages(){
		if(array_key_exists('images', $this->_model))
			foreach($this->_model['images'] as $_img){
				
			}
		return false;
	}		
	
}
