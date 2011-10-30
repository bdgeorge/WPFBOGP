<?php
/**
 * 'Model' class for extracting and managing Social network related meta data.
 * By using this one class to gather all the post meta we can then choose to show it 
 * in different contexts and with different output rendering.
 * 
 * This needs a post ID on construction, it can also 
 * 
 * Different renderers can be injected into the class to mangage the output of the 
 * meta data
 */
class OgpMetaModel {
	
	/**
	 * @var array Array of meta data about this post
	 */
	var $_meta;
	
	/**
	 * @var OgpRenderer Class to render the meta data output
	 */
	var $_renderer;
		
	/**
	 * @var The current objects post (in WP object form)
	 */
	var $_post;
	
	/**
	 * PHP4 style Constructor - Calls PHP5 Style Constructor
	 *
	 * @return WP_Http
	 */
	function OgpMetaModel($id) {
		$this->__construct($id);
	}

	/**
	 * PHP5 style Constructor - Set up available transport if not available.
	 *
	 * @return OgpMetaModel
	 */
	function __construct($id = 0) {
		
		//Get the non post related meta data
		$options = get_option('wpfbogp');
		if (isset($options['wpfbogp_admin_ids']) && $options['wpfbogp_admin_ids'] != '') {
			$this->_meta['admins'] = $options['wpfbogp_admin_ids'];
		}
		if (isset($options['wpfbogp_app_id']) && $options['wpfbogp_app_id'] != '') {
			$this->_meta['app_id'] = $options['wpfbogp_app_id'];
		}
		if (isset($options['wpfbogp_page_id']) && $options['wpfbogp_page_id'] != '') {
			$this->_meta['page_id'] = $options['wpfbogp_page_id'];
		}		
		$this->_meta['site_name'] = get_bloginfo('name');		
		
		//Now Get the post and set up all it's related meta data
		$this->usePost($id);
		
	}	
	
	function usePost($id){
		global $post;
		
		//If the global post is the one we want to use then fine
		//else store it in $_oldPost, set post to our required one and restore it at the end
		$_oldPost = $post;
				
		//TODO: Can we assume that the post variable is available? These damn globals are nasty!!
		$this->_post = get_post($id);
		//Or do we need to get a post Id passed in then and use get_post($id) to get the post object??
					
		$this->_getDescription()	
			 ->_getImages()
			 ->_getTitle()
			 ->_getType();		
			 
		$post = $_oldPost;
		return $this;
	}
	
	function getPost(){
		
	}
	
	function _getImages(){
		return $this;	
	}
	
	/**
	 * Get the description from (in this order)
	 * 1. A custom meta field
	 * 2. The excerpt
	 * 3. The post content
	 * 4. The blogs default description field
	 * @return OgpMetaModel
	 */
	function _getDescription(){
		global $post;//TODO: These damn globals are nasty!!
		
		$this->_meta['description'] = get_bloginfo('description');
		return $this;
	}

	/**
	 * Get the title, site name and url
	 * @return 
	 */	
	function _getTitle(){
		$this->_meta['title'] = ( is_home() || is_front_page() ) ? get_bloginfo('name') : get_the_title();		
		return $this;
	}
	
	function _getType(){
		return $this;
	}
	
	
}
