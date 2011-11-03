<?php
/**
 * Rendering class for writing post social meta data in open graph format
 */
class OgpRenderFacebook extends OgpRenderOgp {
	
	/**
	 * Facebook uses the standard OGP meta tags but adds a few fb tags as well
	 * @var Array of output formats for facebook
	 */
	var $_formats = array(
		'admins'		=> "\t<meta property='fb:admins' content='%s' />\n",
		'app_id'		=> "\t<meta property='fb:app_id' content='%s' />\n",
		'page_id'		=> "\t<meta property='fb:page_id' content='%s' />\n",
		'url'			=> "\t<meta property='og:url' content='%s' />\n",
		'title'			=> "\t<meta property='og:title' content='%s' />\n",
		'site_name'		=> "\t<meta property='og:site_name' content='%s' />\n",		
		'description'	=> "\t<meta property='og:description' content='%s' />\n",
		'type'			=> "\t<meta property='og:type' content='%s' />\n",
		'image'			=> "\t<meta property='og:image' content='%s' />\n",
	);
	
}
