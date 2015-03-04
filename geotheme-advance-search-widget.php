<?php
/*
Plugin Name: GeoTheme Advance Search Widget
Plugin URI: http://buykodo.com
Description: Easy search widget for geotheme. just activate plugin and place it where you want to show it.
Version: 1.0
Author: cybergeekshop
Author URI: http://www.cybergeekshop.net
License: GPL2
*/

###################################################
####### Widget Code ###############
###################################################

class AdvanceSearchWidget extends WP_Widget
{
  function AdvanceSearchWidget()
  {
    $widget_ops = array('classname' => 'AdvanceSearchWidget', 'description' => 'GeoTheme Advance Search Widget' );
    $this->WP_Widget('AdvanceSearchWidget', 'GeoTheme Advance Search', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 ?> 

  <form method="get" id="searchform3" action="<?php bloginfo('home'); ?>/" onsubmit="if(geocodeAddress1())alert('<?php _('Everything is ok');?>'); else { return false; }"> 
   <input type="hidden" name="t" value="1" />
    <br/>
 <?php  require_once (ABSPATH . 'wp-content/plugins/geotheme-advance-search-widget/places_search.php');?> 
     <br/>
 
   <span id="set_nears"></span><input name="sn" id="sns" type="text" class="s" value="<?php if(isset($_REQUEST['sns'])){echo stripslashes($_REQUEST['sns']);}else{echo NEAR_TEXT;}?>" onblur="if (this.value == '') {this.value = '<?php echo NEAR_TEXT;?>';}" onkeydown="if (event.keyCode == 13){set_srch1()}"  onfocus="if (this.value == '<?php echo NEAR_TEXT;?>') {this.value = '';}"  style="padding: 3px;width: 97%;margin-bottom: 10px;" /> 
      <br/>
     <input name="Sgeo_lat" id="Sgeo_lats" type="hidden" value="" />
     <input name="Sgeo_lon" id="Sgeo_lons" type="hidden" value="" />
    <input type="button" class="search_btn" value="<?php echo SEARCH;?>" alt="<?php echo SEARCH;?>" onclick="set_srch1();" />
  </form>

<script type="text/javascript">

jQuery('#set_nears').click(function() {
jQuery('#sns').val('mes');
});
function set_srch1()
{ 		    
 
	 geocodeAddress1();
}
var latlng;
var Sgeocoder;
var address;
var Sgeocoder = new google.maps.Geocoder();
function updateSearchPosition1(latLng) {
	document.getElementById('Sgeo_lats').value=latLng.lat();
	  document.getElementById('Sgeo_lons').value=latLng.lng();
  
  document.forms["searchform3"].submit(); // submit form after insering the lat long positions

 
}
function geocodeAddress1() {
	  Sgeocoder = new google.maps.Geocoder(); // Call the geocode function
	  //alert('click1');
	  if(document.getElementById('sns').value=='')
	{
			  document.forms["searchform3"].submit(); // submit form after insering the lat long positions

	}else{
	
    var address = document.getElementById("sns").value;
    Sgeocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
		 // alert('click2'+ results);
		  if(document.getElementById('sns').value=='mes')
		  {initialise3();}
		  else{updateSearchPosition1(results[0].geometry.location);}
		
 
      } else {
        alert("<?php _e('Search was not successful for the following reason: ');?>" + status);
      }
    });
	}
  }
</script>
<script type="text/javascript"> 
  function initialise3() {
    var latlng = new google.maps.LatLng(-25.363882,131.044922);
    var myOptions = {
      zoom: 4,
      mapTypeId: google.maps.MapTypeId.TERRAIN,
      disableDefaultUI: true
    }
	//alert(latLng);
    prepareGeolocation();
    doGeolocation1();
  }
 
  function doGeolocation1() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(positionSuccess1, positionError1);
    } else {
      positionError1(-1);
    }
  }
 
  function positionError1(err) {
    var msg;
    switch(err.code) {
      case err.UNKNOWN_ERROR:
        msg = "<?php _e('Unable to find your location');?>";
        break;
      case err.PERMISSION_DENINED:
        msg = "<?php _e('Permission denied in finding your location');?>";
        break;
      case err.POSITION_UNAVAILABLE:
        msg = "<?php _e('Your location is currently unknown');?>";
        break;
      case err.BREAK:
        msg = "<?php _e('Attempt to find location took too long');?>";
        break;
      default:
        msg = "<?php _e('Location detection not supported in browser');?>";
    }
    document.getElementById('info').innerHTML = msg;
  }
 
  function positionSuccess1(position) {
    var coords = position.coords || position.coordinate || position;
	 //alert(coords.latitude + ', ' + coords.longitude );
	 document.getElementById('Sgeo_lats').value=coords.latitude;
	  document.getElementById('Sgeo_lons').value=coords.longitude;
	  
	    document.forms["searchform3"].submit(); // submit form after insering the lat long positions


   
  }
 
 
</script> 


<?php
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("AdvanceSearchWidget");') ); 
?>