<?php

defined('ABSPATH') or die("No script kiddies please!");

/**

 * Plugin Name: Clone Maker

 * Plugin URI:https://github.com/nosstradamus/Clonemaker

 * Description: "Clonemaker" is an wordpress plugin. Main target of this plugin is to insert in new page content from another page, simulate a copy - paste action.

 * Version: 1.31

 * Author:Sitnic Victor

 * Author URI: https://github.com/nosstradamus

 * Text Domain: #

 * Domain Path: #

 * Network: #

 * License: GPL2

 *Copyright 2014  SITNIC VICTOR  (email : sitnic.victor@gmail.com)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

*/

function clonemaker_add_meta_box(){
    add_meta_box(
        'clonemaker',
        'Clone a Page',
        'add_post_list',
        'page',
        'side'
    );


}
add_action('add_meta_boxes','clonemaker_add_meta_box');

function add_post_list(){

    $all_page_ids = get_all_page_ids();
    ?>
    <ul class="clone_me">
    <?php
    foreach($all_page_ids as $value){
        ?>
       <li><a href="#" data-content="<?php echo $value ?>"><?php echo get_the_title($value); ?> </a></li>
        <?php


    }
    ?>
    </ul>
<script>
jQuery('.clone_me a').on("click",function(e){
    e.preventDefault();
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    clonepostid = jQuery(this).attr('data-content');

    if(jQuery("#wp-content-wrap").hasClass('html-active')){
        jQuery("#content-tmce").click();
    }

    jQuery.ajax({
        type:"post",
        url:ajaxurl,
        data:{
            action:"clone_content",
            clone_post_id:clonepostid
        },
        success:function(response){
            tinyMCE.activeEditor.setContent(response);


        }
    })

    //
   // tinyMCE.get('content').setContent(jQuery(this).attr("data-content"));
})
</script>
<?php
}


add_action("wp_ajax_clone_content", "clone_content");
add_action("wp_ajax_nopriv_clone_content", "clone_content");
function clone_content(){
    $my_postid = $_POST['clone_post_id'];
    $content_post = get_post($my_postid);
    $content = $content_post->post_content;
    echo $content;
    die();
}