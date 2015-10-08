<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Collaboration_Dashboard {
	function __construct() {
		add_shortcode( 'edd_collaborations', array(
			 $this,
			'collaboration_stage' 
		) );
		add_shortcode('edd_forum', array(
			 $this,
			'get_forum' 
		) );
		add_shortcode( 'edd_artist_details', array(
			 $this,
			'edd_artist_details' 
		) );
		add_action( 'template_redirect', array(
			 $this,
			'check_access' 
		) );

		

	}
	public function get_forum( $atts ) {
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/forum.php' );
		echo ob_get_clean();
	}
	public function edd_artist_details(){
		if(isset($_GET['id'])){
			$users = new WP_User($_GET['id']);
			$user_meta=get_userdata($_GET['id']);
		'<div class="form_row">';
		?>
					<table cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td width="200">First Name</td>
                            <td><?php echo $users->first_name; ?></td>
                        </tr>
                        <tr>
                        	<td width="200">Last Name</td>
                            <td><?php echo $users->last_name; ?></td>
                        </tr>
                        <tr>
                        	<td width="200">Display Name</td>
                            <td><?php echo $user_meta->display_name; ?></td>
                        </tr>
                        <tr>
                        	<td width="200">Email Address</td>
                            <td><?php echo $users->user_email; ?></td>
                        </tr>
                    </table>
				
				<?php
				'</div>';
				'<div class="clear"></div>';
		}
	}
	public function collaboration_stage( $atts ) {
		
		ob_start();
				$file='frontend-collaboration-wall.php';
				include(collaboration_plugin_dir.'templates/frontend/'.$atts['template'].'.php' );
		return ob_get_clean();
	}
	public function check_access() {
		global $post;
		if(!is_user_logged_in() || !EDD_FES()->vendors->vendor_is_vendor()){
			if ($post->post_name=='collaboration') {
					$this->fes_secure_logout();
			}
		}
	}

	public function fes_secure_logout() {
		
			$base_url = get_permalink( EDD_FES()->helper->get_option( 'fes-vendor-dashboard-page', false ) );
			$base_url = add_query_arg( array(
				'view' => 'login',
				'task' => false 
			), $base_url );
			wp_redirect( $base_url );
			exit;
		
	}
	
	

}