<?php

/*
 * Save files and resize images
 * 
 * Note that you must have PHP image process and gd libraries installed: http://php.net/manual/en/book.image.php
 * If not, define NO_IMG_RESIZE in your core.php define('NO_IMG_RESIZE', 1);
 * 
 * It can resize jpg, png and gif images.
 * 
 * Usage:
 * 
 * Suppose you're letting a user upload a logo, from a field named 'pic', and you want to keep the full resolution image, large resized version, and a thumbnail.
 * Further, you want them each to be named after the id of the user, stored in $user_id 
 * 
 * 		App::Import('Component', 'Files');
 *		$this->Files = new FilesComponent();
 *			    		
 *		$result = $this->Files->saveFile( $_FILES['pic'], 
 *			array(
 *				array('new_base_name'=>$user_id, 'directory'=>'img/logo/big/'),
 *				array('new_base_name'=>$user_id, 'directory'=>'img/logo/large/', 'resize'=>800),
 *				array('new_base_name'=>$user_id, 'directory'=>'img/logo/small/', 'resize'=>150)	
 *			)
 *		);
 * 
 */
class FilesComponent extends Object {

	var $controller;

	/**
	 * The main save function for saving images that takes parameters for location and resizing
	 * 
	 * @param mixed $pic the picture array of the uploaded file
	 * @param mixed $options the options for resizing the image - how large and where to save
	 * @return mixed $status with 'code' 0 or 1 for success/fail, and 'details' for why. If successful, details is an array of file paths
	 */
	function saveFile( $pic, $options ) {
		
		// Process the image
		if ( isset($pic) && isset ($pic['name'])) {
			
			$ext = strtoupper($this->getFileExtension($pic['name']));
			$paths = array();
			
			foreach( $options as $option ) {
				
				if( !$option['new_base_name'] || !$option['directory'] ) {
					return array('code'=>0, 'details'=>"Not enough information provided");
				}
				
				$new_name = $option['new_base_name'] . ".$ext";				
				
				// Now save it. Either from the web or from the tmp directory
		        if( strpos($pic['name'], "http")===0 ) {
		        	if( file_put_contents(WWW_ROOT . $option['directory'] . $new_name, file_get_contents($pic['name'])) === false ) {
		        		return array('code'=>0, 'details'=>"Cannot save image");
		        	}
		        	$pic['tmp_name'] = WWW_ROOT . $option['directory'] . $new_name;
		        }else {
			        if (!$this->saveAs( $pic, $new_name, WWW_ROOT . $option['directory'] )) {	            
			            return array('code'=>0, 'details'=>"The image upload failed");
			        }
		        }
				
				// Now process the thumbnail if possible
				if( !defined('NO_IMG_RESIZE') && isset($option['resize']) ) {
			        $img_des = $this->resize_img($option['directory'] . $new_name, $option['resize']);
			        $this->save_resized( $img_des, $option['directory'] . $new_name, 80 );
		        }
		        
		        $paths[] = array('directory'=>$option['directory'], 'file'=>$new_name);				
			}
				        	           
	        return array('code'=>1, 'details'=>$paths);
		}
	}

	
	function saveAs($pic, $fileName, $folder) {
		if (is_writable($folder)) {
			if (is_uploaded_file($pic['tmp_name'])) {
				copy($pic['tmp_name'], $folder . $fileName);
				return true;
			} else {
				debug("File was not uploaded.");
				return false;
			}
		} else {
			debug("Folder: " . $folder . " is not writable.");
			return false;
		}
	}

	function getFileExtension($str) {
		$i = strrpos($str, ".");
		if (!$i) {
			return "";
		}
		$l = strlen($str) - $i;
		$ext = strtolower(substr($str, $i +1, $l));
		return $ext;
	}

	function resize_img($imgname, $size) {
		
		$ext = $this->getFileExtension($imgname);

		if ($ext == "jpg" || $ext == "jpeg") {
			$img_src = ImageCreateFromjpeg($imgname);
		} else
			if ($ext == "png") {
				$img_src = ImageCreateFromPNG($imgname);
			} else
				if ($ext == "gif") {
					$img_src = ImageCreateFromGIF($imgname);
				} else {
					return null;
				}

		$true_width = imagesx($img_src);
		$true_height = imagesy($img_src);

		if ($true_width >= $true_height) {
			$width = $size;
			$height = ($width / $true_width) * $true_height;
		} else {
			$height = $size;
			$width = ($height / $true_height) * $true_width;
		}
		$img_des = ImageCreateTrueColor($width, $height);
		imagecopyresampled($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
		return $img_des;
	}
	
	function save_resized( $img_des, $filename, $quality ) {
		
		$ext = $this->getFileExtension( $filename );
		if ($ext == "jpg" || $ext == "jpeg") {
			 imagejpeg($img_des, $filename, 80);
		} else if ($ext == "png") {
			imagepng($img_des, $filename, 8);
		} else if ($ext == "gif") {
			imagegif($img_des, $filename);
		}	
	}

	function delete($fileName) {
		if (unlink($fileName)) {
			return true;
		} else {
			return false;
		}
	}
}
?>
