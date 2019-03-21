<?php
require_once($_SERVER["DOCUMENT_ROOT"] .'/wp-config.php');

			function create_shortcode_form_zoho($atts) {
				global $wpdb;
				 $shortcode = "";
				$atts = shortcode_atts( array( // atts n'est utilisé que s'il n'est pas préciser dans le shortcode un n°
			        'id' => 18778,
							'title' => 'formulaire de base'
			    ), $atts);
				//$image = $wpdb->get_results("SELECT * FROM `wpup_posts` WHERE `post_parent` = '$atts[id]' ORDER BY `ID` DESC LIMIT 1");
				$table_prefix = $wpdb->prefix;
				$prefix = $table_prefix.'posts';
				$image = $wpdb->get_results("SELECT * FROM `$prefix` WHERE `ID` = '$atts[id]'");
				foreach ($image as $value) {
					$formulaireHtml = $value->post_content;
				}
				$shortcode .= $formulaireHtml;
				return $shortcode;
			}
