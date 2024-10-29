<?php 
/*
Plugin Name: AKL Webhost Post Widget
Description: This widget allows you to handle your posts in sidebar. There are three main functionalities of this widget 1. Heading of your widget, 2. You can set any image of those posts who's featured image is not available, 3. You can set posts number from 1 to 60 in your sidebar. 
Version: 1.0
Author: Usama Khalid
Author URI: https://profiles.wordpress.org/usamakhalid/
License: none
*/

class Akl_Post_Widget extends WP_Widget{
	function __construct(){
		//						ID             ,             Widget Name
		parent::__construct('akl_post_widget', $name = __('Post Widget (AKL Webhosting)'));
	}

	public function form($instance){ // this is widget input

		$posts = '';
		$input_value = '';
		$image_url = '';
		$post_per_page = array(1, 2, 3, 4, 5, 6, 7, 10, 20, 30, 40, 50, 60);

		if(isset($instance['input_value'])){
			$input_value = $instance['input_value'];
		}
		//var_dump($instance['input_value']);

		?>
			<!-- 
				this is the post heading value setter...........
			 -->
			 <br>
				<label for="<?php echo $this->get_field_id('input_value'); ?>">Set Heading Value</label>
				<input type="text" value="<?php echo $input_value; ?>" id="<?php echo $this->get_field_id('input_value'); ?>" name="<?php echo $this->get_field_name('input_value'); ?>">
			<!-- 
				this is the end post heading value setter...........
			-->


			<br><br>
			<!-- 
				this is field which is used to select image.........
			 -->
			 	<?php
			 	if(isset($instance['image_url'])){
			 		$image_url = $instance['image_url'];
			 	} 
			 	?>

			 	<label for="<?php echo $this->get_field_id('image_url'); ?>">Set not define url </label>
			 	<input type="text" value="<?php echo $image_url; ?>" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>">
			 	<a target="_blank" style="margin: 0px 0px 0px 105px;" href="<?php echo site_url(); ?>/wp-admin/upload.php"><i>Open Media</i></a>
			 <!-- 
				this is end of field which is used to select image.........
			 -->
			<br><br>


			<!-- 
				this is the post counter........... 
			-->
				<label for="<?php echo $this->get_field_id('posts'); ?>">Select Post Counter</label>
				<select name="<?php echo $this->get_field_name('posts'); ?>" 
						id="<?php echo $this->get_field_id('posts'); ?>"> 

					<?php if(isset($instance['posts'])){ ?>

							<option value="<?php echo $instance['posts']; ?>">
								<?php echo $instance['posts']; ?>
							</option>
						
						<?php foreach ($post_per_page as $value): ?>

							<?php if($value != $instance['posts']){ ?>
								<option value="<?php echo $value; ?>">
									<?php echo $value; ?>
								</option>
							<?php } ?>
							


						<?php endforeach; ?>

					<?php }else{ ?>

						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
						<option value="50">50</option>
						<option value="60">60</option>

					<?php } ?>
				</select>
			<!-- 
				this is the end of the post counter selector........
			 -->
		<?php
	}


	public function update($new_instance, $old_instance){ // this is widget update area
		$instance['posts'] = sanitize_text_field($new_instance['posts']);
		$instance['input_value'] = sanitize_text_field($new_instance['input_value']);
		$instance['image_url'] = sanitize_text_field($new_instance['image_url']);
		return $instance;
	}


	 public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );


	public function widget($args, $instance){ // this is widget output (front)
		global $wpdb;
		
		if($instance["input_value"] == ''){
			$title = "Newly Widget";
		}else{
			$title = apply_filters( 'widget_title', $instance["input_value"] );
		}

		if($instance["image_url"] == ''){
			$image = plugins_url( 'assets/question_mark.png', __FILE__ );
		}else{
			$image = $instance["image_url"];
		}
		?> 
		
		<?php 
			echo $args["before_widget"];
		?>
			
			<?php 
				echo $args["before_title"];
				echo $title; 
				echo $args["after_title"];
			?>

			<ul>
			<?php
		// this will get current table prefix
			$post_table = $wpdb->prefix."posts";
		//  --------------end---------------
		$user_count = $wpdb->get_results( "SELECT * FROM $post_table WHERE post_type = 'post' AND post_status = 'publish' order by post_date DESC limit {$instance['posts']}" );
			foreach ($user_count as $key) { 

				?>
					<li style="padding: 0px 0px 10px 0px;">
						<div class="row">
							<div class="col-md-4">
								<img style="width: 90px; height: 60px;" src="
									<?php 
										if(get_the_post_thumbnail_url($key->ID, 'post-thumbnail') != NULL){
													echo get_the_post_thumbnail_url($key->ID, 'post-thumbnail');
										} else {
													echo $image;
										}
									?>
									">
							</div>

							<div class="col-md-8" style="word-wrap:break-word; padding: 17px 0px 0px 0px;">
							<a  href="<?php echo get_permalink( $post = $key->ID, $leavename = false ); ?>">
								<?php echo substr(get_the_title($key->ID),0,40)."..."; ?>
							</a>
							</div>
						</div>
						
					</li>
				<?php
			}

			?>
			</ul>

		<?php
		echo $args["after_widget"];
	}

}


add_action('widgets_init', function(){
	register_widget('Akl_Post_Widget');
});


include "akl_post_widget_create_table.php";
register_activation_hook(__FILE__, 'akl_post_widget_table');



?>