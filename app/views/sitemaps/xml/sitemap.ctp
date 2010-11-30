<?php 
/**
 * Autogenerate sitemap.xml
 *  
 * @author		  rynop
 * @link          http://rynop.com
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
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
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
            <loc><?php echo $urlprefix.'/'; ?></loc>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
    </url>
</urlset>