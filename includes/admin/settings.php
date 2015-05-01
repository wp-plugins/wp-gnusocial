<?php

// Don't access this directly, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the settings page.
 *
 * @since 0.1
 */
function av_settings_page() { ?>

	<div class="wrap">

		<?php screen_icon(); ?>

		<h2><?php esc_html_e( 'WP-GNU social: Agordoj', 'wp_gnusocial' ) ?></h2>

		<form action="options.php" method="post">

			<?php settings_fields( 'wp-gnusocial' ); ?>

			<?php do_settings_sections( 'wp-gnusocial' ); ?>

			<?php submit_button(); ?>
			
		</form>
	</div>

<?php }


/**********************************************************/
/******************** General Settings ********************/
/**********************************************************/

/**
 * Prints the general settings section heading.
 *
 * @since 0.1
 */
function wpgs_agordoj_retrovoko_gheneralaj_agordoj() { ?>
	
    <h3>
    <?php esc_html_e( 'Konekto kun GNU social', 'wp_gnusocial' ); ?>
    </h3>
    
<?php }


/**
 * Printas kampon por la API-url
 *
 * @since 0.1
 */
function wpgs_agordoj_retrovoko_apiurl() { ?>
	
	<input name="_wpgs_apiurl" type="text" id="_wpgs_apiurl" value="<?php echo esc_attr( get_option( '_wpgs_apiurl') ); ?>" class="regular-text" />
	<?php 
	     _e( 'Ekzemple: http://lamatriz.org/api/statuses/update.xml', 'wp_gnusocial');
        echo "<p>";
        $teksto = __( 'Tiu ĉi datumo ŝanĝiĝos depende de la url de via nodo de GNU social. Se la url de via nodo estas http://mianodo.org tiam vi devas enmeti jenan API-urlon: http://mianodo.org/api/statuses/update.xml', 'wp_gnusocial');
        printf($teksto);
        echo "</p>";
    ?>	
	
<?php }

/**
 * Printas kampon por la salutnomo
 *
 * @since 0.1
 */
function wpgs_agordoj_retrovoko_salutnomo() { ?>
	
	<input name="_wpgs_salutnomo" type="text" id="_wpgs_salutnomo" value="<?php echo esc_attr( get_option( '_wpgs_salutnomo') ); ?>" class="regular-text" />
	
<?php }

/**
 * Printas kampon por la pasvorto
 *
 * @since 0.1
 */
function wpgs_agordoj_retrovoko_pasvorto() { ?>
	
	<input name="_wpgs_pasvorto" type="password" id="_wpgs_pasvorto" value="<?php echo esc_attr( get_option( '_wpgs_pasvorto') ); ?>" class="regular-text" />
	
<?php }
