<style type="text/css">
.forum-wrapper span,.forum-wrapper div{
	float:left;
	height:auto;
	width:auto;
}
.pagepostinfo{
	display:none;
}
.forum-wrapper *{
color: #404040;
  font-family: 'Lato', Arial, Helvetica, sans-serif!important;
  font-size: 15px;
  line-height: 1.5;	
}
.forum-wrapper,
.forum-top-toolbar,
.forum-list,
.forum-bottom-toolbar,
.forum-row,
.forum-expection,
.forum-desc{
	width:100%!important;
	height:100%!important;
}
.forum-list .forum-desc a,.forum-list .forum-expection a{
	color:#404040!important;
}
.forum-list .forum-expection h1{
	text-transform:none!important;
	
}
.forum-expection h1{
	padding:0px 0px 5px 0px;
	font-size: 20px;
	font-weight:bold;
}
.forum-expection>h1>a{
	font-size: 20px;
	text-decoration:none;
}
.forum-desc{
	padding-top:0px;
}
.forum-desc *{
	font-size: 13px;
}
.forum-desc>span{
	margin-right:15px;
	color:#404040;
}
.forum-desc>span a{
	color:#000;
	text-decoration:none;
	color:#404040;
}
.forum-desc>span a:hover{
	text-decoration:underline;
	cursor:pointer;
}
.forum-row,.forum-wrapper{
	margin-bottom:15px;
	float:left;
	height:auto;
}
.tool{
	float:right!important;
	width:auto;
	height:auto;
}
.tool a,.tool span{
	float:left;
	height:auto;
	width:auto;
	background-color:#F00;
	color:#FFF!important;
	margin-right:5px;
	padding:2px 8px 0px;
	border-radius:2px;
	text-decoration:none!important;
}
.tool span{
	background-color:#333;
}
.byline img{
	margin-left:0px;
}
</style>
<div class="forum-wrapper">
<?php
$paged =(isset($_GET['pg']) && $_GET['pg']>0) ? $_GET['pg'] : 1;
    $_query_arg=array(
	  'post_type' =>'forum',
	  'post_status' => 'publish',
	  'posts_per_page' => 6,
	  'paged' => $paged,
	   

  	);
	if(isset($_GET['cat']) && $_GET['cat']!=''){
	$_query_arg['tax_query']=array(
            array(
                'taxonomy'  => 'sharearts_forum_category',
                'field'     => 'term_id',
                'terms'     => $_GET['cat'],
                'operator'  => 'IN'
				)
                );
	}
	$_query = null;
	$_query = new WP_Query($_query_arg);
	?>
    <div class="forum-top-toolbar">
        <div class="tool">
        <?php
        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'prev_text'          => __('Prev'),
        'next_text'          => __('Next'),
            //'base' =>str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?pg=%#%',
            'current' => $paged,
            'total' => $_query->max_num_pages
        ));
        ?>
        </div>
    </div>
    <div class="forum-list">
    
    
    
<?php
if( $_query->have_posts() ) {
  while ($_query->have_posts()) : $_query->the_post(); 
  	?>
  		<div class="forum-row">
        	<div class="forum-expection">
            <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
            <p>
            	<?php the_excerpt(); ?>
            </p>
            </div>
            <div class="forum-desc">
                <div class="entry-meta">
                    <span class="byline">
                    <span class="author vcard">
                    <a class="url fn n" href="#" title="View all posts by admin">
                    <?php echo get_avatar(get_the_author_ID(),50); ?> 
                    <?php the_author(); ?>
                    </a>
                    </span>
                    </span>
                    <span class="entry-date">
                        <i class="icon-calendar"></i> <?php the_date('d F, Y', '', ''); ?>
                    </span>
                    <span class="comments-link">
                        <a href="<?php the_permalink() ?>" title="Comment on Where can I get some?"><i class="icon-chat"></i> <?php
						printf( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'textdomain' ), number_format_i18n( get_comments_number() ) );
						?></a>
                    </span>			
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    <?php

  endwhile;
  
}

wp_reset_query();  // Restore global post data stomped by the_post().
?>
    
    
    	
    </div>
    <div class="forum-bottom-toolbar">
            <div class="tool">
        <?php
        $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'prev_text'          => __('Prev'),
        'next_text'          => __('Next'),
            //'base' =>str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?pg=%#%',
            'current' => $paged,
            'total' => $_query->max_num_pages
        ));
        ?>
        </div>

    </div>
</div>