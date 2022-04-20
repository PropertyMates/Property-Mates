<?php
function get_deactive_usermeta($user_id,$key){
	global $wpdb;
	$db_table_meta =  $wpdb->prefix . 'deactive_usersmeta';  // table name
	$userMeta = $wpdb->get_row( "SELECT meta_value FROM $db_table_meta where user_id='$user_id' AND meta_key='$key' ");
	if($userMeta){
		return $userMeta->meta_value;
	}else{
		return false;
	}
	
}

function del_deactive_user($user_id){
	global $wpdb;
	$db_table_meta =  $wpdb->prefix . 'deactive_usersmeta';  // table name
	$db_table_user =  $wpdb->prefix . 'deactive_users';  // table name

	$wpdb->delete( $db_table_meta, array( 'user_id' => $user_id ) );
	$res = $wpdb->delete( $db_table_user, array( 'ID' => $user_id ) );
	if($res){
		return true;
	}else{
		return false;
	}
	
}

add_action('wp_ajax_delete_deactive_user_fnc','delete_deactive_user_fnc');
function delete_deactive_user_fnc(){
	$response = 100;
	$user_id = $_POST['user_id'];
	if(del_deactive_user($user_id)){
		$response = 200;
	}	
	echo $response;	
	die;
}

add_action('admin_footer',function(){
	?>
	<script>
	   jQuery('.delete_deactive_user').click(function(){
		   var _this = jQuery(this);		   
		   var id = _this.data('id');
		       _this.text('Deleteing...');
		   jQuery.ajax({
			   type:"POST",
			   url : '<?php echo admin_url("admin-ajax.php");?>',
			   data: {
				   action: "delete_deactive_user_fnc",
				   user_id : id
			   },
			   success:function(res){
				   if(res==200){
				   _this.parent().parent().remove(); 
				   }else{
					   alert('Please try again');
				   }
			   }
		   });
		   return false;
	   });	
	</script>
	
	<?php
	
});



?>