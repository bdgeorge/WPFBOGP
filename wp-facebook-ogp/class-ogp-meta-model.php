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
		global $post;		
		
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

		if (is_home() || is_front_page() ) {
			$this->_meta['url'] =  get_bloginfo('url');
		}else{
			$this->_meta['url'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //TODO: There must be a more wordpress way to do this. What if we're https? Or with some silly google translate querystring vars?
		}			
		$this->_meta['site_name'] = get_bloginfo('name');			
		//Now Get the post and set up all it's related meta data
		$this->usePost($id);
		
	}	
	
	function usePost($id){
		global $post;
		
		//The global post is the one we want to use then fine
		//else store it in $_oldPost, set post to our required one and restore it at the end
		$_oldPost = $post;
				
		//TODO: Can we assume that the post variable is available? These damn globals are nasty!!
		//Or do we need to get a post Id passed in then and use get_post($id) to get the post object??		
		$this->_post = get_post($id);
					
		$this->_getTitle()
			->_getDescription()	
			->_getType()			
			->_getImages();		
			 
		$post = $_oldPost;
		return $this;
	}
	
	/**
	 * Get all the posts social network meta data
	 * @return array Post Social Network Meta Data
	 */
	function getMeta(){
		return $this->_meta;
	}
					
	/**
	 * Get an array of all images in the post, if we aren't on a single post page then 
	 * don't get any images at all - let facebook etc scrape the page.
	 * @return 
	 */
	function _getImages(){
		global $post;
		
		if (is_singular('post')) {
			$this->_meta['images'] = array();
			if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail($post->ID))) {
				if($_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' )){
					$this->_meta['images'][] = $_thumb[0];					
				};
			}
			if($_images = $this->_getAllImagesInPost()){
				$this->_meta['images'] = array_merge($this->_meta['images'], $this->_getAllImagesInPost());				
			}
			//The thumbnail (if it was found) is typically repeated later in the post
			$this->_meta['images'] = array_unique($this->_meta['images']);
		} else {
			$options = get_option('wpfbogp');				
			//If a fallback image is set then use it, but don't force this option on users 
			//as it can be counter productive on the home page etc
			if (isset($options['wpfbogp_fallback_img']) && $options['wpfbogp_fallback_img'] != '') {
				$this->_meta['images'] = $options['wpfbogp_fallback_img'];			
			} else {
				$this->_meta['images'] = false;
			}
		}
		return $this;	
	}
	
	/**
	 * Scans the post for images within the content.
	 * Regex from Wordpress Get The Image plugin 
	 */
	function _getAllImagesInPost(){
		//global $post;
		
		/* Search the post's content for the <img /> tag and get its URL. */
		$content = apply_filters('the_content', $this->_post->post_content);
		$content = str_replace(']]>', ']]>', $content);
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $content, $matches);
		/* If there is a match for the image, return its URL. */
		if ( isset( $matches ) )
			return $matches[1];
		return false;
	}	
	
	/**
	 * Get the description from (in this order)
	 * 1. A custom meta field TODO
	 * 2. The excerpt
	 * 3. The post content
	 * 4. The blogs default description field
	 * @return OgpMetaModel
	 */
	function _getDescription(){
		global $post;//TODO: These damn globals are nasty!!
		
		if (is_singular('post')) {
			if (has_excerpt()) {
				$this->_meta['description'] = esc_attr(strip_tags(get_the_excerpt()));//Passing post->ID is deprecated
			}else{	
				//Then strip out all tags and chop it after 160 chars
				$this->_meta['description'] = esc_attr(str_replace("\r\n",' ',substr(strip_tags(strip_shortcodes($post->post_content)), 0, 160)));					
				
				//TODO: Would we better using all the sites filters and then stripping it from that? eg
				/*
				//Support all the same filters as the will be used on the post
				$content = $post->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				//Then we just need to break after x chars on word boundaries and without stuffing up encoded chars like: &#8217;
				
				 */
			}
		}else{
			$this->_meta['description'] = get_bloginfo('description');
		}		
		
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
		if (is_singular('post')) {
			$this->_meta['type'] = 'article';
		}else{
			$this->_meta['type'] = 'website';
		}		
		return $this;
	}
	
	
}
