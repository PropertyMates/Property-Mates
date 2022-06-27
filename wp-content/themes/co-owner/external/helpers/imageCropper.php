<?php
if(!function_exists('pr')){
function pr($obj){
	echo '<pre>';
	  print_r($obj);
	echo '<pre>';
}
}
/* Create new image from saved property Images  added Techinno */

//resize and crop image by center
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];
 
    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;
 
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;
 
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;
 
        default:
            return false;
            break;
    }
     
    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);
     
    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
     
    $image($dst_img, $dst_dir, $quality);
 
    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}

function isImageCroped($imagePath,$id){
	$savedImages =array();
   $filePathInfo = pathinfo($imagePath);
   $croppedFileName = $filePathInfo['filename'].'-cropped'.'.'.$filePathInfo['extension'];
 
   $uploads = wp_upload_dir();
	$url = $uploads['url'] . "/$croppedFileName";
	$new_file = $uploads['path'] . "/$croppedFileName";
	$crop_width = 396;
	$crop_height = 297;
  
		$img = resize_crop_image($crop_width, $crop_height, $imagePath, $new_file);
		$type = wp_check_filetype($croppedFileName);
  
	   $new_image = array(
				'file' => $new_file,
				'url' => $url,
				'type' => $type['type'],
			);
		return $new_image;  
   
}


function makePropertiesImageCropped(){
$args = array( 'post_type' => 'property','post_status'=>'publish');

$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
  echo $post_id =  get_the_id();
  echo '<br>';
  $propertyImages = get_post_meta($post_id, '_pl_images', true);
 
   $croppedImages =array(); 	
  if($propertyImages){
	  foreach($propertyImages as $imgData){
		  $croppedNow = isImageCroped($imgData['url'],$post_id); 
		  if($croppedNow){
			  $croppedImages[] = $croppedNow; 
		  }
		
	  }
  } 
 if($croppedImages){
	 update_post_meta($post_id,'_pl_images_cropped',$croppedImages);
 } 


endwhile;
}

/* Create new image from saved property Images  added Techinno */
function makePropertiesImageCroppedByID($post_id){
  $propertyImages = get_post_meta($post_id, '_pl_images', true);
  
  $cropedImages = get_post_meta($id, '_pl_images_cropped', true);	
  
  if($cropedImages){	  
	 foreach($cropedImages as $savedImg){
		 unlink($savedImg['file']); 
	 }	  
  }	

   $croppedImages =array(); 	
  if($propertyImages){
	  foreach($propertyImages as $imgData){
		  $croppedNow = isImageCroped($imgData['file'],$post_id); 
		  if($croppedNow){
			  $croppedImages[] = $croppedNow; 
		  }
		
	  }
  } 
 if($croppedImages){
	 update_post_meta($post_id,'_pl_images_cropped',$croppedImages);
 } 

 
}
//makePropertiesImageCroppedByID(969);

//makePropertiesImageCropped();

/* Usage 
$img = resize_crop_image($crop_width, $crop_height, $actualImagePath, $savedImagePath);
*/
