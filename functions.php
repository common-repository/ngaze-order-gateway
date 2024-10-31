<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!function_exists('custom_ngaze_setup_post_type')) { 
function custom_ngaze_setup_post_type() { $args = array( 'public' => true,
    'label'     => __( 'NGAZE OrderGate', 'textdomain' ),
	'menu_icon' => 'dashicons-cart',
	'public' => false,
	'show_ui' => true, 
	'supports' => array('title','editor' => true, 'public'=> false)
);
register_post_type( 'ngaze_order_gate', $args );
}
add_action( 'init', 'custom_ngaze_setup_post_type' );
}
if (!function_exists('prefix_register_meta_boxes_events_ngaze')) { 
function prefix_register_meta_boxes_events_ngaze( $meta_boxes ) {
$prefix = 'ngazeordergetway_';

$meta_boxes[] = array(
    'id'         => $prefix . 'details',
    'title'      => 'ODER DETAILS',
    'post_types' => 'ngaze_order_gate',
    'context'    => 'normal',
    'priority'   => 'core',

    'fields' => array(
        array(
            'name'  => 'Email Listing Id',
            'desc'  => 'Add Email Listing Id from email platform',
            'id'    => $prefix . 'listingid',
            'type'  => 'text',
        ),
		
        array (
            'name' => 'Order Button',
            'desc' => 'Order button text',
            'id'   => $prefix . 'button',
            'type' => 'text',
			'clone' => true,
        ),
		array (
            'name' => 'Order Url',
            'desc' => 'Add Order URL to continue after form submitted',
            'id'   => $prefix . 'url',
            'type' => 'text',
			'clone' => true,
        )			
    )
);
return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'prefix_register_meta_boxes_events_ngaze' );
}


//	add_action( 'wp_enqueue_scripts', 'ngaze_wpse90382_popup_function' );
function ngaze_wpse90382_popup_function(){
wp_register_script( 
'ajaxHandle', 
plugin_dir_url( __FILE__ ).'scripts/ngaze_order_gate.js', 
array(), 
false, 
true 
);
wp_enqueue_script( 'ajaxHandle' );

wp_localize_script( 
'ajaxHandle', 
'ajax_object', 
array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
);

add_action( 'wp_footer', 'ngaze_wpse90382_popup_html' );

wp_register_style( 'my-ngaze_order_gate', plugin_dir_url( __FILE__ ). '/scripts/ngaze_order_gate.css' );
wp_enqueue_style( 'my-ngaze_order_gate' );


}

if (!function_exists('ngaze_wpse90382_popup_html')) { 
function ngaze_wpse90382_popup_html() {

    echo '<div class="poupbg"></div>';
    
    echo '<div class="poupbgcontentwrap">
		<div class="poupclose"><img src="'.plugin_dir_url( __FILE__ ).'assets/images/close-btn.png" width="30"></div>			
		<div class="poupbgcontent"><img src="'.plugin_dir_url( __FILE__ ).'assets/images/loading.gif"></div>
	</div>';
	
	$scriptoutput="";
	$args = array(  
    'post_type' => 'ngaze_order_gate'        
	);

	$loop = new WP_Query( $args ); 
	
	$scriptoutput .='<script>
		jQuery(function($) {'; 
	
	$query = new WP_Query( 
	array( 'post_type' => 'ngaze_order_gate',		
	'order' => 'DESC', 
	'orderby' => 'date' ) ); 
	while ( $query->have_posts() ) {
		$query->the_post();			
		$scriptoutput .= '$(".nogw_'.get_the_ID().'").on("click",function(e){
				e.preventDefault();
				$(this).myfunction(".nogw_'.get_the_ID().'","'.plugin_dir_url( __FILE__ ).'");		
			});';
	}
	 $scriptoutput .='});
	</script>';
	wp_reset_postdata(); 
	echo $scriptoutput;		
}
}

$hasposts = get_posts('post_type=ngaze_order_gate');

if( !empty ( $hasposts ) ) {
// ngaze_wpse90382_popup_function();
add_action( 'wp_enqueue_scripts', 'ngaze_wpse90382_popup_function' );
}
if (!function_exists('ngaze_my_theme_on_admin_init')) {

function ngaze_my_theme_on_admin_init() {
add_meta_box('my_metabox',
    __('POP UP CLASS', 'textdomain'),
    'ngaze_my_metabox_render',
    'ngaze_order_gate', 'normal', 'low'
);
}
function ngaze_my_metabox_render($post) {
$data = get_post_meta($post->ID, '_meta_key', true);
// Use nonce for verification
wp_nonce_field('add_my_meta', 'my_meta_nonce');
$showclass="";
if(isset($_REQUEST['post']))
	$showclass="nogw_".sanitize_text_field($_REQUEST['post']);
else
	$showclass="Will show after saving";	
?>
<div class="inside">
<table class="form-table">
    <tr valign="top">
        <th scope="row"><label for="my_meta_value"><?php _e('Popup Class', 'textdomain'); ?></label></th>
        <td>
		<input type="text" readonly value="<?php echo $showclass ?>">
		
		</td>
    </tr>
</table>
</div>
<?php
}
add_action('admin_init', 'ngaze_my_theme_on_admin_init');
}



add_action( "wp_ajax_ngaze_show_order_form", "ngaze_show_order_form" );
add_action( "wp_ajax_nopriv_ngaze_show_order_form", "ngaze_show_order_form" );

function ngaze_show_order_form($post) {
$prefix = 'ngazeordergetway_';
$cpoiID = sanitize_text_field($_POST["dataid"]);
$args = array(  
'p' => $cpoiID,
'post_type' => 'ngaze_order_gate'       
);
$scriptoutput="";
$query = new WP_Query( $args ); 	
$thecontent="";
$btnData="";
$mailForId="";
$buttonsdata='';
while ( $query->have_posts() ) {
	$query->the_post();				
	$custom_fields = get_post_custom($post->ID);
	$my_custom_listingid = $custom_fields[$prefix . 'listingid'];
	$my_custom_button = $custom_fields[$prefix . 'button'];
	$my_custom_url = $custom_fields[$prefix . 'url'];
	
	$formultiplebuttons = unserialize($my_custom_button[0]); 
	$formultipleurls = unserialize($my_custom_url[0]); 
	
	
	$mailForId=$my_custom_listingid[0]; 
	for($loopa=0;$loopa<count($formultiplebuttons);$loopa++){
		$btnData .= '<button data-baseurl="'.plugin_dir_url( __FILE__ ).'" data-orderurl="'.$formultipleurls[$loopa].'" class="selectthisoption" type="button" data-formid="order'.$post->ID.'">
		'.$formultiplebuttons[$loopa].' <i class="fa fa-angle-double-right"></i></button>';
				}
	$thecontent= get_the_content() ; 
}

wp_reset_postdata(); 


$scriptoutput .= '<form id="order'.$post->ID.'" accept-charset="utf-8" action="proceed.php" method="POST" target="_blank">';
$scriptoutput .= $thecontent;
$scriptoutput .= '<div class="full"><input id="ordername" name="name" type="text" value="" placeholder="Enter full name *"/></div>
<div class="half fullmobile"><input id="orderemail" name="email" type="email" value="" placeholder="Enter email *"/></div>
<div class="half fullmobile" style="padding: 0;">
<input id="orderphone" name="phone" type="text" value="" placeholder="Enter mobile No. *"/>
<input id="orderurl" name="orderurl" type="hidden" value="" />
<input id="listval" name="listval" type="hidden" value="'.$mailForId.'" /></div>
<div class="full"><p id="errormessage" style="color:red"></p>';	
$scriptoutput .=$btnData;
$scriptoutput .= '</div>
</form>';
echo $scriptoutput;
wp_die();
}

add_action( "wp_ajax_ngaze_ngaze_proceed_order_form", "ngaze_proceed_order_form" );
add_action( "wp_ajax_nopriv_ngaze_proceed_order_form", "ngaze_proceed_order_form" );

function ngaze_proceed_order_form($post) {
	if($_POST){
	    
	$sendy_url = 'https://email.thedigitalrestaurant.com/subscribe';
	
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $listval = sanitize_text_field($_POST['listval']);
    $orderurl = sanitize_text_field($_POST['orderurl']);

	$postdata = 
	    array(
	    'name' => $name,
	    'email' => $email,
	    'phone'=>$phone,
	    'list' => $listval,
	    'boolean' => 'true',
	    'api_key' => '73gjZX7ecq3u22gcSIS6'
	    );
    
	$args = array(
			'method'      => 'POST',
			'timeout'     => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(),
			'body'        => $postdata,
			'cookies'     => array(),
			'data_format' => 'body',
			);
	
	$response = wp_remote_post(esc_url_raw($sendy_url), $args );
	
	
	if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		echo '<script> console.log('.$error_message.')</script>';
		echo "Something went wrong! please Try Agin.";
	} else {
		echo '<script>
        	jQuery(function($) {   
        		$(".poupbgcontentwrap .poupbgcontent").html();
        		$(".poupbg").hide();
        		$(".poupbgcontentwrap").hide();
        		var url = "'.$orderurl.'";
        		window.open(url, "_blank");
        	});
        </script>';
	}
	
    }
    wp_die();
}


