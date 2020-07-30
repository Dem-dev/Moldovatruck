<?php
/**
 * The template for displaying the footer.
 */

				logistic_company_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (logistic_company_get_custom_option('body_style')!='fullscreen') logistic_company_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (logistic_company_get_custom_option('show_testimonials_in_footer')=='yes') { 
				$count = max(1, logistic_company_get_custom_option('testimonials_count'));
				$data = logistic_company_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(logistic_company_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php logistic_company_show_layout($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}
			
			// Footer sidebar
			$footer_show  = logistic_company_get_custom_option('show_sidebar_footer');
			$sidebar_name = logistic_company_get_custom_option('sidebar_footer');
			if (!logistic_company_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				logistic_company_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(logistic_company_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
                            logistic_company_show_layout(trim(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
				<?php
			}


			// Footer Twitter stream
			if (logistic_company_get_custom_option('show_twitter_in_footer')=='yes') { 
				$count = max(1, logistic_company_get_custom_option('twitter_count'));
				$data = logistic_company_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(logistic_company_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php logistic_company_show_layout($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Google map
			if ( logistic_company_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = logistic_company_get_custom_option('googlemap_address');
				$map_latlng  = logistic_company_get_custom_option('googlemap_latlng');
				$map_zoom    = logistic_company_get_custom_option('googlemap_zoom');
				$map_style   = logistic_company_get_custom_option('googlemap_style');
				$map_height  = logistic_company_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
                    logistic_company_show_layout(logistic_company_sc_googlemap($args));
				}
			}


			// Copyright area
			$copyright_style = logistic_company_get_custom_option('show_copyright_in_footer');
			if (!logistic_company_param_is_off($copyright_style)) {
				?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(logistic_company_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (($menu = logistic_company_get_nav_menu('menu_footer'))!='') {
                                    logistic_company_show_layout($menu);
								}
							} else if ($copyright_style == 'socials') {
                                logistic_company_show_layout(logistic_company_sc_socials(array('size'=>"tiny")));
							}
							?>
							<div class="copyright_text"><?php
                                $logistic_company_copyright = logistic_company_get_custom_option('footer_copyright');
                                $logistic_company_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $logistic_company_copyright);
                                echo wp_kses_post($logistic_company_copyright); ?></div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !logistic_company_param_is_off(logistic_company_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

	<?php wp_footer(); ?>

</body>
</html>