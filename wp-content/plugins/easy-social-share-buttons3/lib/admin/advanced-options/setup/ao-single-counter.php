<?php 

if (function_exists('essb_advancedopts_settings_group')) {
	essb_advancedopts_settings_group('essb_options');
}

essb_advancedopts_section_open('ao-small-values');

essb5_draw_heading( esc_html__('Counter Value & Animation', 'essb'), '6');
$counter_value_mode = array("" => esc_html__('Automatically shorten values above 1000', 'essb'), 'full' => esc_html__('Always display full value (default server settings)', 'essb'), 'fulldot' => esc_html__('Always display full value - dot thousand separator (example 5.000)', 'essb'), 'fullcomma' => esc_html__('Always display full value - comma thousand separator (example 5,000)', 'essb'), 'fullspace' => esc_html__('Always display full value - space thousand separator (example 5 000)', 'essb'), 'no' => esc_html__('Without formating', 'essb'));
essb5_draw_select_option('counter_format', esc_html__('Share value format', 'essb'), '', $counter_value_mode);
essb5_draw_switch_option('animate_single_counter', esc_html__('Animate value appearance', 'essb'), '');

essb_advancedopts_section_close();