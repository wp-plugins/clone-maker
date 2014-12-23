
<?php
 function CM_connect_style() {
       /* Register our stylesheet. */
       wp_register_style( 'myPluginStylesheet', plugins_url('clonemaker.css', __FILE__) );
       wp_enqueue_style( 'myPluginStylesheet' );
   }
add_action( 'admin_init', 'CM_connect_style' );
load_plugin_textdomain('clonemaker', false, basename( dirname( __FILE__ ) ) . '/languages' );

function CM_AddBox()  {  

        $cm_postTypes = array("post","page");
        
        foreach($cm_postTypes as $value){
	add_meta_box( 
       'CM-CloneBox'.ucfirst($value), // this is HTML id
       'Clone from '.ucfirst($value), 
       'CM_callList', // the callback function
       $value, // register on post type = page
       'side', // 
       'core'

    );
        }


}

add_action ( 'add_meta_boxes', 'CM_AddBox');


function CM_args($type){
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
	'post_type' => $type,
	'post_status' => 'publish'

); 
}


function CM_showList($val){
    echo "<ul>";
        foreach ($val as $key => $value) {
            
            
            
	echo "<li class='CM-".$value->post_type."'>";
		echo $value->post_title;		
	echo "<a class='clonepage' style='float:right;text-decoration:none' href='".$value->ID."'>+++</a><hr style='clear:both'/></li>";
}
echo "</ul>";
}

function CM_callList( )
{
$cm_posts = array_merge(get_pages(CM_args('page')),get_posts(CM_args('post')));
CM_showList($cm_posts);
}



function CM_getMyData(){
$cm_posts = array_merge(get_pages(CM_args('page')),get_posts(CM_args('post')));
$myid = $_POST['data'];



$myarray = array('title'=>'','content'=>'');

foreach ($cm_posts as $key => $value) {
	
	if($value->ID == $myid){
	$myarray['title']=$value->post_title;
	$myarray['content']=$value->post_content;
	
}	

	
}



$valj = json_encode($myarray);
 print_r($valj);
  die();

   }



add_action('wp_ajax_CM_getMyData', 'CM_getMyData');
add_action('wp_ajax_nopriv_CM_getMyData', 'CM_getMyData');


add_action( 'admin_footer', 'CM_script' ); // Write our JS below here



function CM_script() { ?>

	<script type="text/javascript" >
	function getContent(event){
		event.preventDefault();
			myid = jQuery(this).attr('href');	
		    jQuery.ajax({
            method:'POST',
            url:"<?=admin_url( 'admin-ajax.php' )?>",
            data:{
                 action:'CM_getMyData',
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











