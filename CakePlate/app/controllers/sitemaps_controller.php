<?php
/**
 * Autogenerate sitemap.xml
 * 
 * Check out config/routes.php too!
 *  
 * @author		  rynop
 * @link          http://rynop.com
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 *  <?xml version="1.0" encoding="UTF-8"?>
 *  < urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
 *      < url>
 *          < loc>http://www.example.com/</loc>
 *          < lastmod>2005-01-01 00:00:00</lastmod>
 *          < changefreq>monthly</changefreq>
 *          < priority>0.8</priority>
 *      </url>
 *  </urlset>
 *
 * The Sitemap must:
 * Begin with an opening <urlset> tag and end with a closing </urlset> tag.
 * Include a <url> entry for each URL as a parent XML tag.
 * Include a <loc> child entry for each <url> parent tag.
 *
 */
class SitemapsController extends AppController
{   
    var $components = array('RequestHandler');
    var $helpers = array('Time', 'Xml','Cache');
    var $name = 'Sitemaps';
    
    //Uncomment useTable=false after you setup which model(s) you want to use
    var $useTable = false;
    //var $uses = array('Stuff');
   
    var $cacheAction = "1 day";   
    
    function sitemap ()
    {
        Configure::write ('debug', 0);
        $this->set('urlprefix','http://yourdomain.com');
        
		//Now read your data from your models, pass it to the veiw to be rendered as XML
        
        $this->RequestHandler->respondAs('xml');
        $this->viewPath .= '/xml';
        $this->layoutPath = 'xml';
    }
}
?>