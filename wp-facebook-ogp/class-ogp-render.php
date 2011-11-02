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
	
	function getSiteName(){
		if(array_key_exists('sitename', $this->_model))
			return "\t<meta property='og:site_name' content='" . $this->_model['sitename'] . "' />\n";
		return false;
	}
	
	function getSiteName(){
		if(array_key_exists('sitename', $this->_model))
			return "\t<meta property='og:site_name' content='" . $this->_model['sitename'] . "' />\n";
		return false;
	}	
	
	
}
