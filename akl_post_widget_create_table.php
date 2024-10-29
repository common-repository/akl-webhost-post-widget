<?php 


function akl_post_widget_table(){
		global $wpdb;

		$table_name = $wpdb->prefix . "akl_post_widget";

		if( $wpdb->get_var('SHOW TABLES LIKE ' . $table_name) != $table_name){
			$create_table = "CREATE TABLE " . $table_name . "(
				ID int NOT NULL AUTO_INCREMENT,
				Total_posts_display varchar(255) NOT NULL,
				PRIMARY KEY (ID)
			)";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($create_table);

		}
	}
