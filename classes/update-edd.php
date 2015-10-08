<?php
/* Add :
  I want to register as an individual –artist/author
  I want to register as a gallery and/or production house
  I want to register as the buyer.
  */
  add_action('edd_add_registration_type',add_type,2);
  function add_type(){
	  $level=rcp_get_subscription_levels( 'active' );
	 
	 $data='<div class="saloginform_row" style="padding: 20px 0;">';
	 $w=0;
	 foreach($level as $ex){
		 $w++;
				 $data.='<label for="reg_type'.$ex->id.'">
					<input type="radio" '.(($w==1)?'checked="checked"':'').' class="radiobox required" id="reg_type'.$ex->id.'" name="reg_type" value="'.$ex->id.'" />
					<span style="padding-left: 8px">'.$ex->text.'</span>
				</label>
				<div class="clear"></div>';
	 }
			echo $data.='</div>';
  }
  add_filter('edd_insert_user',setup_user_type);
  function function_get($s){
	  return "<tr class='form-field'>
				<th scope='row' valign='top'>
					<label for='rcp-commission'>Commission</label>
				</th>
				<td>
					<input type='text' name='commission' value='".$level->role."'/>
					<p class='description'>Commission for admin from each products solds</p>
				</td>
			</tr>";
  }
  add_action('rcp_edit_subscription_form',function_get,10,1);
  function get_role_from_update_edd(){
	  if(isset($_POST['reg_type'])){
		  $id=rcp_get_subscription_details($_POST['reg_type']);
		  
		  return $id->role;
	  }else{
		  return get_option('default_role');
	  }
  }
  function setup_user_type($e,$i){
	 $type=isset($_POST['reg_type'])?$_POST['reg_type'] :'buyer';
	 add_user_meta($e,'reg_type',$type);
	  $id=rcp_get_subscription_details($type);
	 add_user_meta($e,'eddc_user_rate',$id->commission);
	 return $e;
  }
  add_action('edd_payment_form',redirect_to_payment,10,2);
  function redirect_to_payment($e,$r){ 
 // echo $e; die(rcp_get_subscription_id(get_current_user_id()));
	  //isset($_GET['rcp_level']) && ($_GET['rcp_level']==1 || $_GET['rcp_level']==2)){//
	  if(isset($_POST['reg_type']) && ($_POST['reg_type']==1 || $_POST['reg_type']==2)){//(rcp_get_subscription_id(get_current_user_id())==1) || (rcp_get_subscription_id(get_current_user_id())==2)){
	  echo '<script>window.location="'.get_permalink(get_page_by_path('payment-process')).'?id='.$e.'";</script>'; exit;
	  }else{
	echo '<script>window.location="'.get_permalink(get_page_by_path('settings')).'";</script>'; exit; 
	  }
  }
  function after_register(){
	  $_GET['level']=get_usermeta($_GET['id'],'reg_type');
	  $levels = rcp_get_subscription_levels( 'active' );
	  $gateways = rcp_get_enabled_payment_gateways();
	   rcp_show_error_messages( 'register' );
	?>


<form id="rcp_registration_form" class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?><?php if(isset($_GET['id'])){ echo '?id='.$_GET['id']; }?>">
<div class="saloginwrapper">
<h2 class="section-title"><span>Successful Registration!</span></h2>
<div class="clear"></div>

		<ul id="rcp_subscription_levels" style="display:none;">
			<?php foreach( $levels as $key => $level ) : ?>
				<?php if( rcp_show_subscription_level( $level->id ) ) : ?>
				<li id="rcp_subscription_level_<?php echo $level->id; ?>" class="rcp_subscription_level">
					<input type="radio" class="required rcp_level" <?php if(( isset( $_GET['level']) && $_GET['level'] == $level->id ) ){ echo 'checked="checked"'; }?> name="rcp_level" rel="<?php echo esc_attr( $level->price ); ?>" value="<?php echo esc_attr( absint( $level->id ) ); ?>" <?php if( $level->duration == 0 ) { echo 'data-duration="forever"'; } ?>/>&nbsp;
					<span class="rcp_subscription_level_name"><?php echo rcp_get_subscription_name( $level->id ); ?></span><span class="rcp_separator">&nbsp;-&nbsp;</span><span class="rcp_price" rel="<?php echo esc_attr( $level->price ); ?>"><?php echo $level->price > 0 ? rcp_currency_filter( $level->price ) : __( 'free', 'rcp' ); ?><span class="rcp_separator">&nbsp;-&nbsp;</span></span>
					<span class="rcp_level_duration"><?php echo $level->duration > 0 ? $level->duration . '&nbsp;' . rcp_filter_duration_unit( $level->duration_unit, $level->duration ) : __( 'unlimited', 'rcp' ); ?></span>
					<div class="rcp_level_description"> <?php echo rcp_get_subscription_description( $level->id ); ?></div>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
    <?php 
	$gateways = rcp_get_enabled_payment_gateways();

	if( count( $gateways ) > 1 ) : $display = rcp_has_paid_levels() ? '' : ' style="display: none;"'; ?>
		<fieldset class="rcp_gateways_fieldset">
            <p>
            <label><?php _e( 'Choose Your Payment Method', 'rcp' ); ?></label>
            </p>
			<p id="rcp_payment_gateways"<?php echo $display; ?>>
				<select name="rcp_gateway" id="rcp_gateway" style="float:left; position:relative;">
					<?php foreach( $gateways as $key => $gateway ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $gateway ); ?></option>
					<?php endforeach; ?>
				</select>
				
			</p>
		</fieldset>
	<?php endif; ?>
    
<?php do_action( 'rcp_before_registration_submit_field', $levels ); ?>

	<p id="rcp_submit_wrap">
		<input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce('rcp-register-nonce' ); ?>"/>
		<input type="submit" name="rcp_submit_registration" id="rcp_submit" value="<?php echo apply_filters ( 'rcp_registration_register_button', __( 'Register', 'rcp' ) ); ?>"/>
	</p>
<div class="clear"></div>
</div></form>
    <?php
	
  }
add_shortcode('pay_after_register',after_register);








/* Forum */
function sharearts_forum() {
	register_post_type( 'forum',
                array( 
				'label' => __('Forum'), 
				'singular_label' => __('Forum Item', 'sharearts'),
				'_builtin' => false,
				'public' => true, 
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'hierarchical' => true,
				'capability_type' => 'post',// array('frontend_vendor','ddd'),
				'map_meta_cap'=> true,
				'rewrite' => array(
					'slug' => 'forum',
					'with_front' => FALSE,
				),
				'supports' => array(
						 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'
						)
					) 
				);
	register_taxonomy('sharearts_forum_category', 'forum', array('hierarchical' => true, 'label' => 'Forum Categories', 'singular_name' => 'Forum Category', "rewrite" => true, "query_var" => true));
}

add_action('init', 'sharearts_forum');
function tp_stop_guestes( $content ) {
    global $post;

    if ( $post->post_type == 'forum' ) {
        if ( !is_user_logged_in()) {
            $content = 'Please login to view this post';
        }else{
			global $current_user;
		$first_key = key($current_user);
		if(!array_key_exists('administrator',$current_user->caps) && !array_key_exists('frontend_vendor',$current_user->caps)) {
            $content = 'Please login to view this post';
        }
	}
    }
    return $content;
}
function can_access() {
    global $post;

    if ( $post->post_type == 'forum') {
        if ( !is_user_logged_in()) {
			   return false;
			}else{
				global $current_user;
			$first_key = key($current_user);
			if(!array_key_exists('administrator',$current_user->caps) && !array_key_exists('frontend_vendor',$current_user->caps)) {
				return false;
			}
		}
    }
   return true;
}
add_filter( 'the_content', 'tp_stop_guestes' );
function add_forums_to_admin() {
  $caps = array(
    'read',
    'read_forum',
    'read_private_forum',
    'edit_forum',
    'edit_private_forum',
    'edit_published_forum',
    'edit_others_forum',
    'publish_testimonials',
    'delete_forum',
    'delete_private_forum',
    'delete_published_forum',
    'delete_others_forum',
  );
  $roles = array(
    get_role( 'administrator' ),
    get_role( 'frontend_vendor' ),
  );
  foreach ($roles as $role) {
    foreach ($caps as $cap) {
      $role->add_cap( $cap );
    }
  }
}
add_action( 'after_setup_theme', 'add_forums_to_admin' );

function get_forum_top_menu(){
	if ( is_user_logged_in() && rcp_get_subscription_id($current_user->ID)==1) {
						global $current_user;
						if(array_key_exists('administrator',$current_user->caps) || array_key_exists('frontend_vendor',$current_user->caps)) {
            	?>
            <li class="separator"><span>|</span></li>
					<li class="parent">
						<a href="<?php echo get_bloginfo('url'); ?>/forum/">
							<span>Forum</span>
						</a>
					</li>
					<?php
					
					
					}
				}
}
function my_the_content_filter($content) {
  // assuming you have created a page/post entitled 'debug'
  
  // otherwise returns the database content
   return $content;
}

add_filter( 'the_content', 'my_the_content_filter' );
		
/* Detail Page .. */

function language_redirect($e)
 {
      global $post;

      if(is_single() && $post->ID!='')
      {

		  $term_id__='';;
		  $term_id___ins='';
		  $term_id__=wp_get_post_terms($post->ID,'download_category', array("fields" => "ids"));	
			if(is_array($term_id__)){
				$term_id__=$term_id__[0];
			}
			$stc=get_categories_structure();

			foreach($stc as $_kk=>$_vv){
				if(is_array($_vv)){
					foreach($_vv as $__kk=>$__vv){
						if(term_is_ancestor_of($__kk,$term_id__,'download_category')){
							$term_id___ins=$_kk.'-'.$__vv['key']; break;
						}
					}
				}
			}
			
			$file_location=get_template_directory().'/custom-template/'.$term_id___ins.'.php';
			if(file_exists($file_location)){
				return $file_location;
			}else{
				return $e;	
			}
			

      }else{
		  return $e;	
	  }

 }
 add_action( 'template_include', 'language_redirect' );
 /*Users Music */
add_filter('init', 'kill_rewrite_rules_' );
add_filter( 'query_vars', 'wpa5413_query_vars_' );
//add_filter('init','flushRules_');
function kill_rewrite_rules_($rules){
   add_rewrite_rule('artists-music/([^/]+)/?',
		'index.php?pagename=edd-arts-music&artist_id=$matches[1]',
		'top'
	);
}
function wpa5413_query_vars_($query_vars){ 
	array_push($query_vars, 'artist_id');
	return $query_vars;
}
function flushRules_(){
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}
function prefix_url_rewrite_templates() {

    if ( get_query_var('pagename') && get_query_var( 'pagename' )=='edd-arts-music') {
		add_filter('wp_title',function(){
			return "Artist's Music";
		});
		add_filter('bcn_breadcrumb_title',function(){
			return "Artist's Music";
		});
        add_filter( 'template_include', function() {
            return get_template_directory() . '/edd-arts-music.php';
        });
    }
}
 
add_action( 'template_redirect', 'prefix_url_rewrite_templates' );
function check_if_posted($e,$type='parent',$yes=true){
	if(isset($_POST[$type])){
		if(in_array($e,$_POST[$type])){
			if($yes){
				return ' checked="checked" ';
			}else{
				return true;
			}
		}
	}
}
function edd_search(){
	if(isset($_GET['key'])){
		$_POST['key']=$_GET['key'];
		$_POST['parent']=array('all');
	}
	?>
    <style type="text/css">
	.search_for_{
		width:100%; min-height:500px; height:auto;
	}
	.search_option_{
		width:100%;
		height:auto;
	}
	.option_,.last_option_,.option_ li,.last_option_ li{
		width:auto;
		height:auto;
		float:left;
		list-style:none;
	}
	.option_ li{
		padding-right:10px;
	}
	.option_,.last_option_{
		width:100%;
		padding:0px!important;
		margin:0px!important;
	}
	.option_ li.title,.last_option_ li.title{
		width:100%;
	}
	.last_option_{
		
	}
	.last_option_ li{
		width:30%!important;
	}
	[data-group="sub"]{
		display:none;
	}
	.entry-content > *:not(.edd_downloads_list) .edd_purchase_submit_wrapper, .popup .edd_purchase_submit_wrapper,
	.entry-content > *:not(.edd_downloads_list) .edd_download_purchase_form:last-of-type{
		margin-top:0px!important;
	}
	div.content-grid-download div.entry-image div.actions{
		overflow:visible;
	}
	</style>
    <div class="search_for_">
    	<form method="post" action="<?php echo get_the_permalink(get_page_by_path('search')); ?>">
        <div class="searchcontainer">
            <div class="searchfieldholder">
                <div class="searchfield">
                    <span class="screen-reader-text">Search for:</span>
                    <input type="search" class="sitesearchfield" placeholder="Search..." value="<?php echo (isset($_POST['key'])?$_POST['key']:''); ?>" name="key" title="Search for:">
                    
                    <div class="clear"></div>
                </div>
                <button type="submit" class="sitesearchbttn">
                    <i class="fa fa-search"></i>
                </button>
                
                <div class="clear"></div>
            </div>
    
            
            <div class="clear"></div>
        </div>
        <div class="search_option_">
        <ul class="option_">
            <li class='title'>
                <h1 class="home-widget-title" style="text-transform:none;">
                What are looking for?
                </h1>
            </li>
            <li><label><input type="checkbox" name="parent[]" data-all="all" value="all" <?php echo check_if_posted('all'); ?> <?php echo (!isset($_POST))?"checked":''; ?> /> Match any</label></li>
            <?php
			$finished_items=array();
			$data=get_categories_structure();
			foreach($data['finished'] as $key=>$options_){
				$finished_items[]=$key;
			?>
        	<li><label><input type="checkbox" name="parent[]" <?php echo check_if_posted($key); ?> value="<?php echo $key; ?>" data-type="<?php echo $key; ?>" /> <?php echo ucfirst($options_['key']); ?></label></li>
            <?php
			}
			if(rcp_get_subscription_id(get_current_user_id())==1){
			?>
            <li><label><input type="checkbox" name="parent[]" <?php echo check_if_posted('67'); ?> value="67" data-type="67" /> Compose</label></li>
            <li><label><input type="checkbox" name="parent[]" <?php echo check_if_posted('4'); ?> value="4" data-type="4" /> Unfinished design</label></li>
            <li><label><input type="checkbox" name="parent[]" <?php echo check_if_posted('80'); ?> value="80" data-type="80" /> Write</label></li>     
            <?php
			}
			?>  
        </ul>
        <?php
		$op_='';
		if(check_if_posted('all','parent',false)==true){
			$op_='display:none;text-transform:none;';
		}else{
			$op_='display:block;text-transform:none;';
		}
		?>
        <h1 data-group="sub" class="home-widget-title" style="<?php echo $op_; ?>">
                Do you want more filter options?
         </h1>
         <?php
		 $all_rerms=array();
		$stc=get_categories_structure();
		foreach($stc as $key=>$val){
			
			foreach($val as $key_=>$val_){
				
			$_terms =get_term_children($key_,'download_category');
			//print_r($_terms);die;
				foreach($_terms as $key__=>$val__){
					$term = get_term_by("id", $val__, 'download_category');
					$childs__=get_term_children($val__,"download_category");
					if(strtolower(trim($term->name))!='other'){
					$all_rerms[$key_][$val__]=array("term_id"=>$val__,"name"=>str_replace("'", "",$term->name));
					}
					if(is_array($childs__) && sizeof($childs__)){
						foreach($childs___ as $key___=>$val___){
							$details____= get_term_by( 'id', $val___, 'download_category');
							if(strtolower(trim($details____->name))!='other'){
							$all_rerms[$key_][$key__]["children"][$key___]=array("term_id"=>$key___,"name"=>str_replace("'", "",$details____->name));
							}
						}
					}
					
				}
			}
		}
		 ?>
        <ul class="last_option_"  data-group="sub" style="<?php echo $op_; ?>">
        	
			<?php
            foreach($all_rerms as $key=>$_last){
				foreach($_last as $__last){
            ?>
                <li <?php echo check_if_posted($key)?'style="display:block;"':'style="display:none;"'; ?>><label><input name="sub[]" <?php echo check_if_posted($__last['term_id'],'sub'); ?> value="<?php echo $__last['term_id']; ?>" data-parent-id="<?php echo $key; ?>" data-id="<?php echo $__last['name']; ?>" <?php echo check_if_posted($__last['term_id'],'sub'); ?> type="checkbox" /> <?php echo $__last['name']; ?></label></li>
            <?php
			}
            }
            ?>
        </ul>
        </div>
        </form>
        <div class="clear"></div>
        <?php 
		//print_r($_POST);
		
		$args=array();
		if(isset($_POST['key']) && $_POST['key']!=''){
		$args['s']=$_POST['key'];
		}
		
		if(!check_if_posted('all')){
			if(isset($_POST['parent']) && is_array($_POST['parent'])){
				if(!isset($_POST['sub']) && !is_array($_POST['sub'])){
					$args['tax_query']=array( 
						array(
						'taxonomy' => 'download_category', 
						'field' => 'id',
						'terms' => $_POST['parent'], 
						'include_children' => true
						)
					);
				}
			}
			//allitems
		
				if(isset($_POST['sub']) && is_array($_POST['sub'])){
					$args['tax_query']['include_children']=false;
				$args['tax_query']=array(
					array(
					'taxonomy' => 'download_category', 
					'field' => 'id',
					'terms' => $_POST['sub'], 
					'include_children' => false
					)
				);
				}
			
			
		}else{
			if(rcp_get_subscription_id(get_current_user_id())!=1){
					$args['tax_query']=array( 
						array(
						'taxonomy' => 'download_category', 
						'field' => 'id',
						'terms' =>$finished_items, 
						'include_children' => true
						)
					);
			}
		}
		$args['post_type']='download';
        $the_query = new WP_Query( $args );
echo 
'<div class="row">
<div class="col-lg-12">';
 if($the_query->have_posts() ) : 
 ?>
 <h1 class="home-widget-title">Search Results</h1>

 	<div class="row">
 	<?php
	while ( $the_query->have_posts() ) : $the_query->the_post();
	?>
    <div class="col-lg-3 col-md-6 col-sm-12">
    <?php
    get_template_part('content-grid', 'download');
    ?>
    <div class="clear"></div>
    </div>
    
	<?php
	endwhile;?>
    </div>
    <?php
else:
?>
<h1 class="home-widget-title">Search Results</h1>
<p>No products found matching your products.</p>
<?php
endif; 
echo '</div></div>';
 wp_reset_postdata();












		?>
        
    </div>
    <script type="text/javascript">
	jQuery(document).ready(function(e) {
		 jQuery("[data-all]").click(function(){
			 
		 	if(jQuery(this).is(":checked")){
				jQuery('[data-group="sub"]').hide()
				jQuery("[data-type]").prop('checked',false);
			}
		 })
        jQuery("[data-type]").click(function(){ 
			jQuery("[data-parent-id]").closest('li').hide();
			jQuery("[data-parent-id]").prop('checked',false);
			jQuery("[data-all]").prop('checked',false);
			jQuery('[data-group="sub"]').show()
			if(jQuery("[data-type]").is(":checked")){
				jQuery.each(jQuery("[data-type]"),function(i,v){
					if(jQuery(v).is(":checked")){
						jQuery("[data-parent-id='"+jQuery(v).data('type')+"']").prop("checked",false);
						jQuery("[data-parent-id='"+jQuery(v).data('type')+"']").closest('li').show();
					}else{
						jQuery("[data-parent-id='"+jQuery(v).data('type')+"']").prop("checked",false);
						//jQuery("[data-parent-id='"+jQuery(v).data('type')+"']").closest('li').hide();
					}
				})
			}else{
				jQuery('[data-group="sub"]').hide()
				jQuery("[data-all]").prop('checked',true);
			}
		})
		jQuery("[data-all-items]").click(function(){
			if(jQuery(this).is(":checked")){
				jQuery.each(jQuery("[data-type]"),function(i,v){
					if(jQuery(v).is(":checked")){
				jQuery('[data-parent-id="'+jQuery(v).data('type')+'"]').prop('checked',true);
					}
					})
			}else{
				jQuery("[data-parent-id]").prop('checked',false);
			}
		})
		jQuery("[data-parent-id]").click(function(){
			if(jQuery("[data-parent-id]").not(":checked")){
				jQuery("[data-all-items]").prop('checked',false);
			}
		})
    });
	</script>
    <?php
}
add_shortcode('edd_search',edd_search);
add_action('sharearts_products_like',add_likes,10, 2);
function add_likes($e,$type='product'){
	?>
    <style type="text/css">
	.user-controllers-icons{
		float:left;
		height:auto;
		width:100%;
		border-top:1px solid #cfcfcf;
		padding:10px 0px 0px 0px;
	}
	.likes-wraper{
		display:inline-block;
		height:auto;
		width:100%;
		text-align:center;
	}
	.user-likes{
		width:auto;
		height:20px;
		display:inline-block;
		padding-right:10px;
		
		
	}
	.count{
		
		font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
		padding-right:10px;
	}
	.like,.recommand-button{
		height:auto;
		width:auto;
		border:0px;
		background-color:#f8ac00;
		font-size:12px;
		border-radius:2px;
		cursor:pointer;
		color:#FFF;
	}
	.like:hover,.recommand-button:hover{
		background-color:#fbb71e;
		color:#FFF;
	}
	.likes-wraper{
		display:inline-block;
	}
	.like{
		
	}
	.user-recommand{
		display:inline-block;

	}
	.ico{
		height:15px;
		width:15px;
		float:left;
		background-image:url('data:image/svg+xml;utf8,<svg version="1.1" id="Thumbs_up" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve"><path fill="#FFFFFF" d="M13.648,7.362c-0.133-0.355,3.539-3.634,1.398-6.291c-0.501-0.621-2.201,2.975-4.615,4.603	C9.099,6.572,6,8.484,6,9.541v6.842C6,17.654,10.914,19,14.648,19C16.017,19,18,10.424,18,9.062C18,7.694,13.779,7.718,13.648,7.362	z M5,7.457c-0.658,0-3,0.4-3,3.123v4.848c0,2.721,2.342,3.021,3,3.021c0.657,0-1-0.572-1-2.26V9.816C4,8.048,5.657,7.457,5,7.457z"/></svg>');
		background-repeat:no-repeat;
		padding-left:20px;
		background-position:left top;
	}
	.count,.text{
		font-size:12px;
		font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
		padding:2px;
		color:#000;
	}
	</style>
    <script type="text/javascript">
	jQuery(document).ready(function(e) {
        jQuery(".like").click('click',function(){
			var this_=jQuery(this).parent();
			jQuery.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:{'product_id':'<?php echo $e; ?>','Method':'user_like','type':'<?php echo $type; ?>'},
			}).done(function(msg) {
				if(msg.status=='Ok'){
					this_.find('.count').html(msg.total);
					if(msg.total<=1){
						this_.find('.text').html('Like');
						
					}else{
						this_.find('.text').html('Likes');
					}
				}
			})
		})
		jQuery(".recommand-button").fancybox({
          'href'            :'<?php echo get_permalink(get_page_by_path('product-recommand')); ?>?id=<?php echo $e; ?>',
          'type'            : 'iframe',
		  autoDimensions: false,
		  width:350,
		  height:209
     	});
    });
	</script>
    <?php
	echo '<div class="user-controllers-icons" data-product-id="'.$e.'">';
	echo '	<div class="likes-wraper">';
	echo '	<div class="user-likes">';
	echo '		<span class="count">'.get_user_like($e,$type).'</span><span class="text">'.(get_user_like($e,$type)<=1?'Like':'Likes').'</span>';
	echo '		<button class="like"><i class="ico"></i>Like</button>';
	echo '	</div>';
	echo '	<div class="user-recommand">';
	echo '		<button class="recommand-button">Recommend</button>';
	echo '	</div>';
	echo '	</div>';
	echo '</div>';
}
//function total_inhold($item){
	//if(is_hold){
		//echo "<b style='color:#f00'> - on hold by ShareArts</b>";
	//}

//}
function item_inhold($item,$payment){
	if(is_hold($item)==true){

		if(isset($payment->post_author) && ($payment->post_author!=get_current_user_id())){
		echo "<b style='color:#f00'> - on hold by ShareArts</b>";
		}else{
		echo "<br/><b style='color:#f00; padding-left:10px;'><input type='checkbox' class='is_received' data-id='".$item['id']."' value='Y'/> Please check this checkbox when you receive this Physical Piece from saler.</b>
		<script type='text/javascript'>
		jQuery(document).ready(function(){
			jQuery('.is_received').click(function(){
				var _this=jQuery(this);
				jQuery.ajax({
					type: 'POST',
					url:'".admin_url('admin-ajax.php')."',
					dataType: 'json',
					data:{'id':jQuery(this).data('id'),'p_id':'".$payment->ID."','action': 'remove_from_hold'},
				}).done(function(msg) {
_this.parent().remove();
				})	
			});
		})
		</script>
		";	
		}
	}

}
function is_hold($item){
	if(isset($item['id'])){
		if(get_post_meta($item['id'],'physical_piece',true)!=''){
			if(get_post_meta($item['id'],'in_hold',true)=='0'){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}else{
		return false;
	}
}
function is_physical($e){
	if(_isphysical($e)==true){
		add_post_meta($e,'in_hold','1',true);
		//$my_post = array('ID'=>$e,'post_status'=>'publish');
		//wp_update_post($my_post);
	}
}
function _isphysical($e){
	$_d=get_post_meta($e,'physical_piece');
	return ($_d!='' && is_array($_d) && sizeof($_d)>0)?true:false;
}
add_action('is_physical','is_physical',10,1);
add_action("wp_ajax_remove_from_hold", "remove_from_hold");
function remove_from_hold(){
	$id=$_POST['id'];
	update_post_meta($id,'in_hold','0',true);
	wp_mail('alexsharma68@gmail.com',$_POST['p_id'].' is now clear','Hello Admin,<br/><br/> The payment id : '.$_POST['p_id'].' is now clear. buyer received the physical piece.<br/><br/>Thanks');
	
	 die();
}
add_action('list_materials','list_materials_',10,1);
function list_materials_($term){


	$term_id=wp_get_post_terms($term,'download_category', array("fields" => "ids"));
	if(is_array($term_id)){
				$term_id=$term_id[0];
			}
			echo '<style type="text/css">
			.con_{
				background-position:left top; 
				background-repeat:repeat-x; 
				height:26px; width:40px; 
				float:left; 
				position:absolute; 
				bottom:2px; 
				left:2px; 
				z-index: 99999;
				background-color: rgba(251, 250, 255, 0.9); 
				border:1px solid;
				border-color:rgba(251, 250, 255, 0.1);
				width:266px;
				padding:0px 3px 0px 3px;
				
				}
				.sml-box{
				height:25px; width:40px; 
				background-size: contain;
    			background-repeat: no-repeat;
   				background-position: center center;
				}
			.music_{
				background-image:url('.get_template_directory_uri().'/images/spectrum.png); 
			}
			.let_{
				width:266px;
				
			}
			.des_{
				/*background-image:url('.get_template_directory_uri().'/images/painting.png);*/
				background-size: contain;
    			background-repeat: no-repeat;
    			background-position: center center;
			}
			.phy{
				/*background-image:url('.get_template_directory_uri().'/images/painting.png);*/
				background-size: contain;
    			background-repeat: no-repeat;
    			background-position: center center;
							}
			.let1{
				/*background-image:url('.get_template_directory_uri().'/images/painting.png);*/
				background-size: contain;
    			background-repeat: no-repeat;
    			background-position: center center;
			}
			.sml-box{
				margin:1px 2% 1px 2%;
				width:21%;
				float:left;
				height:23px;
			}
			.let1{
				background-image:url('.get_template_directory_uri().'/images/let1.png); 
			}
			.let2{
				background-image:url('.get_template_directory_uri().'/images/let2.png); 
			}
			.des1{
				background-image:url('.get_template_directory_uri().'/images/des1.png); 
			}
			.des2{
				background-image:url('.get_template_directory_uri().'/images/des2.png); 
			}
			.des3{
				background-image:url('.get_template_directory_uri().'/images/des3.png); 
			}
			.des4{
				background-image:url('.get_template_directory_uri().'/images/des4.png); 
			}
			.phy1{
				background-image:url('.get_template_directory_uri().'/images/phy1.png); 
				background-color: rgba(251, 250, 255, 0.3); 
			}
			.phy2{
				background-image:url('.get_template_directory_uri().'/images/phy2.png); 
				background-color: rgba(251, 250, 255, 0.3); 
			}
				</style>';
	if (term_is_ancestor_of(10, $term_id, 'download_category')){
	?>
		<div title="Music" class="con_ music_"></div>
	<?php
	}elseif(term_is_ancestor_of(25, $term_id, 'download_category')){
		$_xx='<div class="sml-box let1"></div><div class="sml-box let2"></div><div class="sml-box let1"></div><div class="sml-box let2"></div>';
		$terms_d=wp_get_post_terms($term,'download_tag', array("fields" => "ids"));
		$_ob = get_term_by( 'id', absint( $terms_d[0] ), 'download_tag' );
		if($_ob->name!==''){
			$_cn=explode(',',$_ob->name);
			$_cn=array_filter($_cn);
			
			if(isset($_cn[0])){
				echo '<div title="Literature" class="con_ let_">'.$_xx.'</div>';
			}else{
				echo '<div title="Literature" class="con_ let_">'.$_xx.'</div>';
			}
		}else{
				echo '<div title="Literature" class="con_ let_">'.$_xx.'</div>';
			}

	}elseif(term_is_ancestor_of(76, $term_id, 'download_category')){
		
		if(_isphysical($term)!=true){
		?>
		<div title="Design" class="con_ des_"><div class="sml-box des1"></div><div class="sml-box des2"></div><div class="sml-box des3"></div><div class="sml-box des4"></div></div>
        <?php
		}else{
			?>
            <div title="Design" class="con_ phy"><div class="sml-box phy1"></div><div class="sml-box phy2"></div><div class="sml-box phy1"></div><div class="sml-box phy2"></div></div>
            <?php
		}
	}
}

?>