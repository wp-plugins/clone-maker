<?php
load_plugin_textdomain('clonemaker', false, basename( dirname( __FILE__ ) ) . '/languages' );
function AddBox()  {  
	add_meta_box( 
       'CloneBox', // this is HTML id
       'Clone from Page', 
       'showCanClonePage', // the callback function
       'page', // register on post type = page
       'side', // 
       'core'
    );
}
add_action ( 'add_meta_boxes_page', 'AddBox');


function showCanClonePage( )
{
 $args = array(
	'sort_order' => 'ASC',
	'sort_column' => 'post_title',
	'hierarchical' => 1,
	'exclude' => '',
	'include' => '',
	'meta_key' => '',
	'meta_value' => '',
	'authors' => '',
	'child_of' => 0,
	'parent' => -1,
	'exclude_tree' => '',
	'number' => '',
	'offset' => 0,
	'post_type' => 'page',
	'post_status' => 'publish'
); 
$pages = get_pages($args); 

echo "<ul>";
foreach ($pages as $key => $value) {
		
	echo "<li>";
		echo $value->post_title;		
	echo "<a class='clonepage' style='float:right;text-decoration:none' href='".$value->ID."'>+++</a><hr style='clear:both'/></li>";
}
echo "</ul>";
}

function getMyData(){
	
	
	 $args = array(
	'sort_order' => 'ASC',
	'sort_column' => 'post_title',
	'hierarchical' => 1,
	'exclude' => '',
	'include' => '',
	'meta_key' => '',
	'meta_value' => '',
	'authors' => '',
	'child_of' => 0,
	'parent' => -1,
	'exclude_tree' => '',
	'number' => '',
	'offset' => 0,
	'post_type' => 'page',
	'post_status' => 'publish'
); 
$pages = get_pages($args); 
$myid = $_POST['data'];

$myarray = array('title'=>'','content'=>'');
foreach ($pages as $key => $value) {
	
	if($value->ID == $myid){
	$myarray['title']=$value->post_title;
	$myarray['content']=$value->post_content;
	
	
}	
	
	
}

$valj = json_encode($myarray);
 print_r($valj);
  die();
   }

add_action('wp_ajax_getMyData', 'getMyData');
add_action('wp_ajax_nopriv_getMyData', 'getMyData');


add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>
	<script type="text/javascript" >
	
	function getContent(event){
		event.preventDefault();
			myid = jQuery(this).attr('href');
		
		    jQuery.ajax({
            method:'POST',
            url:"<?=admin_url( 'admin-ajax.php' )?>",
            data:{
                 action:'getMyData',
                 data: myid
            },
            success: function(data){
                  fillFields(data);
            },
            error: function(data){return false;}
        });
	}
	
	function fillFields(mydata){
		
		jso = JSON.parse(mydata);

		mycont = jso ['content'];
		
		
		
		
		if(jQuery("#wp-content-wrap").hasClass('tmce-active')){
			z = true;
	   } else {
	   	z=false;
	   }
	    
	    
	   	if(z){jQuery('a#content-html').click()};
	    
	    
		jQuery('textarea[name="content"]').attr('value');
		jQuery('textarea[name="content"]').attr('value',mycont);
		
		if(z){jQuery('a#content-tmce').click()};
		
		
	
	}
	

	jQuery('.clonepage').bind("click",getContent);
	
	
	</script> <?php
}





