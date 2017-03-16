<?php
class AmazingCart_shortcode{
	
	public function init()
	{
		
		add_shortcode("amazing_group", array($this,'amazingcart_group'));
		add_shortcode("amazing_content", array($this,'amazing_content'));
		
		add_shortcode("amazing_link_btn", array($this,'amazingcart_link_button'));
		
		add_shortcode("amazing_img", array($this,'amazing_img'));
		
		add_shortcode("amazing_slider_item", array($this,'amazingcart_img_slider_item'));
		add_shortcode("amazing_slider_group", array($this,'amazingcart_img_slider_group'));
		
		add_shortcode("amazing_slider_2_group", array($this,'amazingcart_img_slide_2_group'));
		add_shortcode("amazing_slider_2_item", array($this,'amazingcart_img_slide_2_item'));
		
		add_shortcode("amazing_slider_2_product", array($this,'amazing_slider_2_product'));
		
		add_shortcode("amazing_slider_2_post", array($this,'amazing_slider_2_post'));
	}
	
	
	function amazing_slider_2_post($atts)
	{
		extract(shortcode_atts(array(
		 'title' => '',
		 'by'=>'',  
    	 'id' => '',
		 'show' => ''
    ), $atts)); 
		
		
		if($by == "category")
	{
		$term = get_term( $id, "category" );
	
	if($title == "")
	{
		$content = '[amazing_slider_2_group title="'.$term->name.'"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'post', 'category' => $term->slug,'posts_per_page' =>$post_per_page,'paged'=>"1",'order' => "DESC");
	}
	else if($by == "random")
	{
		
		if($title == "")
	{
		$content = '[amazing_slider_2_group title="Random"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'post','posts_per_page' =>$post_per_page,'order' => "DESC",'orderby'=>"rand",'paged'=>"1");
		
	}
	else 
	{
		
		if($title == "")
	{
		$content = '[amazing_slider_2_group title="New Items"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'post','posts_per_page' =>$post_per_page,'paged'=>"1",'order' => "DESC");
		
	}
		
	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) : $loop->the_post();
	
	$thumb = get_the_post_thumbnail($loop->the_post()->ID, 'thumbnail');
	
	$content.='[amazing_slider_2_item  src="'.$thumb.'" link="'.get_permalink($loop->the_post()->ID).'" title="'.substr(get_the_title($loop->the_post()->ID),0,28).'..."]';
	endwhile;
		
		$content.= "[/amazing_slider_2_group]";
		
		
		$output = do_shortcode($content);
		
		return $output;
		
	}
	
	
	function amazing_slider_2_product($atts)
	{
		 extract(shortcode_atts(array(
		 'title' => '',
		 'by'=>'',  
    	 'id' => '',
		 'show' => ''
    ), $atts)); 
	
	if($by == "category")
	{
		$term = get_term( $id, "product_cat" );
	
	if($title == "")
	{
		$content = '[amazing_slider_2_group title="'.$term->name.'"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'product', 'product_cat' => $term->slug,'posts_per_page' =>$post_per_page,'paged'=>"1",'order' => "DESC",'meta_query' => array(
								array(
								'key' => '_visibility',
								'value' => array('visible','catalog')
								)
						    ));
	}
	else if($by == "featured")
	{
		
	
	if($title == "")
	{
		$content = '[amazing_slider_2_group title="Featured"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'product','posts_per_page' =>$post_per_page,'paged'=>"1",'order' => "DESC",'meta_query' => array(
								array(
								'key' => '_visibility',
								'value' => array('visible','catalog')
								),
								array(
							'key' => '_featured',
							'value' => "yes"
							)
								
						    ));
		
	}
	else if($by == "random")
	{
		
		if($title == "")
	{
		$content = '[amazing_slider_2_group title="Random"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'product','posts_per_page' =>$post_per_page,'order' => "DESC",'orderby'=>"rand",'paged'=>"1",'meta_query' => array(
								array(
								'key' => '_visibility',
								'value' => array('visible','catalog')
								),
								array(
							'key' => '_featured',
							'value' => "yes"
							)
								
						    ));
		
	}
	else 
	{
		
		if($title == "")
	{
		$content = '[amazing_slider_2_group title="New Items"]';
	}
	else
	{
		$content = '[amazing_slider_2_group title="'.$title.'"]';
	}
	
	if($show == "")
	{
		$post_per_page = "10";
	}
	else
	{
		$post_per_page = $show;
	}
	
		$args = array( 'post_type' => 'product','posts_per_page' =>$post_per_page,'paged'=>"1",'order' => "DESC",'meta_query' => array(
								array(
								'key' => '_visibility',
								'value' => array('visible','catalog')
								)
								
						    ));
		
	}
		
	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) : $loop->the_post();


$woo = new WC_Product($loop->post);
$woo->id = $loop->post->ID;

$amazingcart_woo_json_api = new amazingcart_woo_json_api();

$thumb = wp_get_attachment_url(get_post_thumbnail_id($woo->id, 'thumbnail'));

if($woo->is_on_sale( ) == true)
{
	$price = "<strike>".get_woocommerce_currency_symbol()." ".get_post_meta( $woo->id, '_regular_price',true )."</strike> ".get_woocommerce_currency_symbol()." ".get_post_meta($woo->id, '_sale_price',true )."";	
}
else
{
	$price = "".get_woocommerce_currency_symbol()." ".get_post_meta($woo->id, '_regular_price',true )."";	
}

if($amazingcart_woo_json_api->get_product_type($woo->id) == "grouped" || $amazingcart_woo_json_api->get_product_type($woo->id) == "variable")
{
	$get_products_group = $amazingcart_woo_json_api->get_products_group($woo->id);
	$content.='[amazing_slider_2_item  src="'.$thumb.'" link="'.get_permalink($woo->id ).'" price="FROM '.get_woocommerce_currency_symbol()." ".$get_products_group['min_price']['price'].'" title="'.substr(get_the_title($woo->id),0,28).'..."]';
}
else
{
	
	
	$content.='[amazing_slider_2_item  src="'.$thumb.'" link="'.get_permalink($woo->id ).'" price="'.$price.'" title="'.substr(get_the_title($woo->id),0,28).'..."]';
}
	      

	endwhile;
		
		$content.= "[/amazing_slider_2_group]";
		
		
		$output = do_shortcode($content);
		
		return $output;
		
	}
	
	 function keepXLines($str, $num=10) {
    $lines = explode("\n", $str);
    $firsts = array_slice($lines, 0, $num);
    return implode("\n", $firsts);
}
	
	
	function amazingcart_group( $atts, $content = null ) {
     extract(shortcode_atts(array(  
    'id' => '',
    'class' => ''
    ), $atts));  
	
	$output  = '<div class="panel-group"  ';
	
	if(!empty($id))
		$output .= 'id="'.$id.'"';
		
	$output .='>'.do_shortcode($content).'</div>';
	return $output;  
	
	}  
	
	
	function amazing_content($atts, $content = null) {
    extract(shortcode_atts(array(  
    'id' => '',
    'title' => '',
	'open'=>'n' 
    ), $atts));  

	
	if(empty($id))
		$id = 'accordian_item_'.rand(100,999);
		
	$output = '<div class="panel panel-default">
        <div class="panel-heading">
		<h4 class="panel-title">
        <a class="accordion-toggle" data-toggle="collapse" 
        data-parent="#accordion2" href="#'.$id.'">'.$title.'</a>
		</h4>
        </div>
         <div id="'.$id.'" class="panel-collapse collapse '.($open == 'y' ? 'in' :'').'">
            <div class="panel-body">'.$content.'</div>
         </div>
        </div>';  
		
	return $output;
}  
	
	
	
	function amazingcart_link_button( $atts, $content = null ) {
     extract(shortcode_atts(array(
    'style' => '',
    'size' => '',
	'link' => '',
	'align' => ''
	), $atts));  
	
	if(empty($id))
		$id = '';
	
	if($style == "")
	{
		$style = "default";
	}
	
	if($size == "")
	{
		$sizeTrans = "";	
	}
	else if($size == "large")
	{
		$sizeTrans = "btn-lg";
	}
	else if($size == "small")
	{
		$sizeTrans = "btn-sm";
	}
	else if($size == "xsmall")
	{
		$sizeTrans = "btn-xs";
	}
	else
	{
		$sizeTrans = "";
	}
	
	$output  = '<p align="'.$align.'"><a href="'.$link.'" class="btn btn-'.$style.' '.$sizeTrans.'">'.$content.'</a></p>';
	
	
	return $output;  
	
	}
	
	function amazing_img($atts) {
    extract(shortcode_atts(array(  
    'src' => '',
    'alt' => '',
	'height'=>'', 
	'width'=>'',
	'style'=>''
    ), $atts));  

	if($style == "")
	{
		$style = "thumbnail";	
	}
		
	$output = '<img src="'.$src.'" height="'.$height.'" width="'.$width.'" alt="'.$alt.'" class="img-'.$style.'">';  
		
	return $output;
}  


	function amazingcart_img_slider_group( $atts, $content = null)
	{
		extract(shortcode_atts(array(  
    'height' => '',
	'pagination' => ''
    ), $atts));  
	
	
	if($pagination == "yes")
	{
		$paginationOut1 = "#pagination_".$idint." {
  position: absolute;
  left: 0;
  text-align: center;
  bottom:5px;
  width: 100%;
}

.swiper-pagination-switch {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 10px;
  background: #999;
  box-shadow: 0px 1px 2px #555 inset;
  margin: 0 3px;
  cursor: pointer;
}
.swiper-active-switch {
  background: #fff;
}";	

	$paginationOut2 = '<div id="pagination_'.$idint.'"></div>';
	
	$paginationOut3 = 'pagination: "#pagination_'.$idint.'",';
	}
	
	$idint = 'swipe_sd'.rand(100,999);
		$output = '
		 <style>
/* Demo Syles */

.swiper-container{
 
}

 #'.$idint.'{
	  width: 300px;
  height: '.$height.'px;
 }

.swiper-slide {
  color:#fff;
  font-size:25px;
  text-align:center;
  line-height:200px;
}
.swiper-scrollbar {
  height:10px;
  margin:20px auto;
  width: 300px;
}
.red-slide {
  background:#900;
}

'.$paginationOut1.'

  </style>
  
  <div class="swiper-container" id="'.$idint.'">
    <div class="swiper-wrapper">
     '.do_shortcode($content).'
    </div>
	 '.$paginationOut2.'
  </div>
  
  
   
  <script>
 
  
  
  $(function(){
	$("#'.$idint.'").swiper({
		'.$paginationOut3.'
    loop:false,
    grabCursor: true,
    paginationClickable: true
	})
	
})
  
  </script>
  
		
		';
		
	return $output;
		
	}

	
	
	function amazingcart_img_slider_item( $atts)
	{
		extract(shortcode_atts(array(  
    'src' => '',
	'link'=>''
    ), $atts)); 
	
	if($link == "")
	{
		$new = '<img src="'.$src.'" />';	
	}
	else
	{
		$new = '<a href="'.$link.'"><img src="'.$src.'" /></a>';
	}
	
	$output ='<div class="swiper-slide red-slide">
			'.$new.'
        
      </div>';
	
	return $output;
		
	}
	
	
	function amazingcart_img_slide_2_group($atts,$content) {
    extract(shortcode_atts(array(  
    'title' => ''
    ), $atts));  

	$rand = 'thumbnail_'.rand(100,999);
	$output = '
	<style>
	
#'.$rand.' .thumbs-title {
	position: absolute;
	top: 15px;
	
	font-size: 15px;
}
#'.$rand.' {
	padding-top: 45px;
}

#'.$rand.' .swiper-slide {
	width: 100px;
	text-align: left;
	line-height: 1.3;
}
#'.$rand.' img {
	width: 80px;
	height: 80px;
}
#'.$rand.' .app-title {
	margin-top:5px;
	font-size: 10px;
	color:#000000;
	text-overflow:ellipsis;
	position: relative;
	overflow: hidden;
	max-width: 90%
}

#'.$rand.' .app-price {
	
	font-weight: bold;
	color: #999;
	font-size: 10px;
	color:#000000;
	text-overflow:ellipsis;
	position: relative;
	overflow: hidden;
	max-width: 90%
}
</style>


	
	
	<div class="swiper-container" id="'.$rand.'">
		<div class="thumbs-title">'.$title.'</div>
		<div class="swiper-wrapper">
			
			'.do_shortcode($content).'
			
			<div class="swiper-slide" style="display:none;">
				<img src="http://www.idangero.us/sliders/swiper/demo-apps/appstore/img/thumbs/2.jpg" height="150" width="150" alt="">
				<div class="app-title">Sleepless Night</div>
				<div class="app-price">$10</div>
			</div>
			
			
		</div>
	</div>


<script>

 $(function(){
	$("#'.$rand.'").each(function(){
		$(this).swiper({
			slidesPerView:"auto",
			offsetPxBefore:0,
			offsetPxAfter:0,
			calculateHeight: true
		})
	})
	
})
</script>
	';  
		
	return $output;
}  


function amazingcart_img_slide_2_item($atts){
	
	extract(shortcode_atts(array(  
    'src' => '',
	'link'=>'',
	'title'=>'',
	'price'=>''
    ), $atts)); 
	
	
	if($link == "")
	{
		$new1 = '<img src="'.$src.'" height="150" width="150" alt="">';
	}
	else
	{
		$new1 = '<a href="'.$link.'"><img src="'.$src.'" height="150" width="150" alt=""></a>';
	}
	
	
	
	$output = '
		<div class="swiper-slide">
				'.$new1.'
				<div class="app-title">'.$title.'</div>
				<div class="app-price">'.$price.'</div>
			</div>
	';
	
	return $output;
	
}

}


?>