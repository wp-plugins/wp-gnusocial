<?php
/**
 * Define the main plugin class
 *
 * @since 0.2.6
 *
 * @package Wp_Gnusocial
 */

// Don't allow this file to be accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * The main class.
 *
 * @since 0.1.0
 */
final class Wp_Gnusocial {

	/**
	 * The plugin version.
	 *
	 * @since 0.0.1
	 */
	const VERSION = '0.0.1';

	/**
	 * The plugin slug.
	 *
	 * @since 0.0.1
	 */
	const SLUG = 'wp_gnusocial';

	/**
	 * The only instance of this class.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected static $instance = null;

	/**
	 * Avatar type according to stored user format
	 *
	 */
	const URL_AVATAR_TYPE   = 'url';
    const USER_AVATAR_TYPE  = 'user';
    const EMPTY_AVATAR_TYPE = 'empty';

    /**
     * The avatar size we use
     */
    const AVATAR_SIZE = 48;

	/**
	 * Get the only instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return object $instance The only instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent cloning of this class.
	 *
	 * @since 0.2.6
	 *
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Ne permesite', self::SLUG ), self::VERSION );
	}

	/**
	 * Prevent unserializing of this class.
	 *
	 * @since 0.2.6
	 *
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Ne permesite', self::SLUG ), self::VERSION );
	}

	/**
	 * Construct the class!
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function __construct() {

		/**
		 * Require the necessary files.
		 */
		$this->require_files();

		/**
		 * Add the necessary action hooks.
		 */
		$this->add_actions();
	}

	/**
	 * Require the necessary files.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function require_files() {

		/**
		 * PHP library for publishing to a GNU social node.
		 */
		require( plugin_dir_path( __FILE__ ) . 'gsfluo/gsfluo.php' );
	}

	/**
	 * Add the necessary action hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	private function add_actions() {

		// Load the text domain for i18n.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action('wp_head', array( $this, 'komento_butono' ));

		// Publikigi enskribon en nodon de GNU social ĉe publikiĝo - malneto publikiĝas - de WordPress A
		add_action( 'draft_to_publish', array( $this, 'gs_publikigo' ), 10, 1);

		// Publikigi enskribon en nodon de GNU social ĉe publikiĝo - planita afiŝo publikiĝas - de WordPress A
		add_action( 'future_to_publish', array( $this, 'gs_publikigo' ), 10, 1 );

		add_action('pre_get_comments', array( $this, 'load_comments_wpgs_template' ));

        add_filter( 'comment_form_default_fields', array( $this, 'wpgs_comment_form_fields' ) );

        add_filter( 'comment_form_defaults', array( $this, 'wpgs_comment_form' ) );

        add_action('comment_form', array( $this, 'wpgs_comment_button' ));

        add_filter('get_avatar', array($this,'wpgs_akiri_avataron'), 10, 5);
        
        $apiurl = get_option( '_wpgs_apiurl');
        $salutnomo = get_option( '_wpgs_salutnomo');
        $pasvorto = get_option( '_wpgs_pasvorto');
        
        if (empty($apiurl) || empty($salutnomo) || empty($pasvorto)) {
            
            add_action('admin_notices', array($this, 'atentigi_pri_agordomanko'));
        }

	}
	
	/**
	 * Load the text domain.
	 *
	 * Based on the bbPress implementation.
	 *
	 * @since 0.1.0
	 *
	 * @return The textdomain or false on failure.
	 */
	public function load_textdomain() {

		$locale = get_locale();
		$locale = apply_filters( 'plugin_locale',  $locale, 'wp_gnusocial' );
		$mofile = sprintf( 'wp_gnusocial-%s.mo', $locale );

		$mofile_local  = plugin_dir_path( dirname( __FILE__ ) ) . 'languages/' . $mofile;
		$mofile_global = WP_LANG_DIR . '/wp-gnusocial/' . $mofile;

		if ( file_exists( $mofile_local ) )
			return load_textdomain( 'wp_gnusocial', $mofile_local );

		if ( file_exists( $mofile_global ) )
			return load_textdomain( 'wp_gnusocial', $mofile_global );

		load_plugin_textdomain( 'wp_gnusocial' );

		return false;
	}

	/**
	 * Enqueue the styles.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'wpgs-stiloj', plugin_dir_url( __FILE__ ) . 'assets/styles.css' );
	}

    public function komento_butono() {
        global $post;
        if (!(get_post_meta( $post->ID, 'wpgs_conversation_id', true ) == '') ) {
            echo "<style>";
                echo ".form-submit {";
                echo "display: none;";
                echo "}";
            echo "</style>";
        }
    }
    
    public function atentigi_pri_agordomanko() {
        $screen = get_current_screen();
        if ($screen->base === 'plugins') {
        ?>
            <div class="updated">
                <p><a href="<?php print admin_url('options-general.php?page=wp-gnusocial');?>" class="button"><?php _e('Konekto kun GNU social', 'wp_gnusocial');?></a> &mdash; <?php _e('Preskaŭ farite! Konektu vian blogon kun GNU social kaj publikigu novan afiŝon por ekuzi la novan komentosistemon.', 'wp_gnusocial');?></p>
            </div>
            <?php
        }
    }

    public function gs_publikigo($post) {

        $title = $post->post_title;
        $priskribo = $post->post_excerpt;        
        $permalink = get_permalink( $post->ID );
        
        //$kategorioj = get_the_category($ID);

        //foreach($kategorioj as $kategorio) {
        //    $kategoricheno .= '#' . $kategorio->cat_name . ' ';
        //}

        $kategoricheno = '';

        $gs_konektilo = new GsKonektilo(get_option( '_wpgs_apiurl'), get_option( '_wpgs_salutnomo'), get_option( '_wpgs_pasvorto'));

        $respondo = $gs_konektilo->afishi($title, $permalink, $priskribo, $kategoricheno);
        
        $status = new SimpleXMLElement(wp_remote_retrieve_body($respondo));

        add_post_meta( $post->ID, 'wpgs_conversation_id', (string)($status->id), true);        

    }

    /**
     * Parse comment author url and get a formated GNU Social user and node
     * @object $komento
     * @return user@node
     */
    public function wpgs_get_comment_user_at_node($komento) {
      $comment_host   =  parse_url($komento->auhtoro_url, PHP_URL_HOST);
      $comment_author =  $komento->auhtoro_salutnomo;
      return $comment_author . '@' . $comment_host;
    }

    public function load_comments_wpgs_template($comment_template){

        if( !(get_post_meta( get_the_ID(), 'wpgs_conversation_id', true ) == '') ) {
        
            if( (get_post_meta( get_the_ID(), 'wpgs_updating', true ) == '') OR
                (get_post_meta( get_the_ID(), 'wpgs_updating', true ) == '0')) {
        
                update_post_meta( get_the_ID(), 'wpgs_updating', '1');
                
                $konversacio_id = get_post_meta( get_the_ID(), 'wpgs_conversation_id', true );

                $nodo_url = parse_url(get_option( '_wpgs_apiurl'));
                $nodo_url = $nodo_url['host'];

                $atom_fluo_url = 'http://' . $nodo_url . '/api/statusnet/conversation/' .  $konversacio_id . '.atom';

                $atom_legilo = new AtomLegilo($atom_fluo_url);

                $komentoj = $atom_legilo->legi(get_the_ID());

                foreach ($komentoj as $komento) {
                    $datumoj = array(
                        'comment_post_ID' => get_the_ID(),
                        'comment_author' => $komento->auhtoro,
                        'comment_author_email' => self::wpgs_get_comment_user_at_node($komento),
                        'comment_author_url' => $komento->auhtoro_url,
                        'comment_content' => $komento->enhavo,
                        'comment_type' => '',
                        'comment_parent' => 0,
                        'comment_author_IP' => '',
                        'comment_agent' => 'GNU social',
                        'comment_date' => $komento->publikig_dato->format('Y-m-d H:i:s'),
                        'comment_approved' => 1,
                    );

                    wp_insert_comment($datumoj);
                }

                $atom_legilo->ghisdatigi_daton(get_the_ID());
                
                update_post_meta( get_the_ID(), 'wpgs_updating', '0');
        
            }
        }
    }

    public function wpgs_comment_form_fields( $fields ) {

         global $post;

        if( !(get_post_meta( $post->ID, 'wpgs_conversation_id', true ) == '') ) {
            $fields = array(
            'author' => '',
            'email' => '',
            'url' => '',
            );

       }

       return $fields;
    }

    public function wpgs_comment_form( $args ) {
        global $post;

        if( !(get_post_meta( $post->ID, 'wpgs_conversation_id', true ) == '') ) {

          $args['comment_field'] = '';
          $args['comment_notes_before'] = '';
          $args['comment_notes_after'] = '';
          $args['logged_in_as'] = '';
        }

        return $args;
    }


    public function wpgs_comment_button() {
      global $post;

      if( !(get_post_meta( $post->ID, 'wpgs_conversation_id', true ) == '') ) {

            $konversacio_id = get_post_meta( $post->ID, 'wpgs_conversation_id', true );
            $nodo_url = parse_url(get_option( '_wpgs_apiurl'));
            $nodo_url = $nodo_url['host'];
            $konversacio_url = 'http://' . $nodo_url . '/conversation/' . $konversacio_id;
            $uzanto = get_option( '_wpgs_salutnomo');
            $uzanto_url = 'http://' . $nodo_url . '/' . $uzanto;

            $helpo_mesagho = __('Forigu+tion+ĉi+kaj+skribu+vian+komenton', 'wp_gnusocial');
            $respondo_url = 'http://' . $nodo_url . '/index.php?action=newnotice&status_textarea='. $helpo_mesagho . '&inreplyto=' . $konversacio_id;

            echo '<a href="' . $respondo_url . '"><h3 id="commentform" class="comment-form">' . __('Klaku ĉi tie por sendi komenton per', 'wp_gnusocial') . ' ' .  $nodo_url . '</a></h3>';

            printf('<p class="comment-form-comment"> ' . __('Se vi havas uzanton ĉe %s vi povos rekte komenti. Se vi havas <strong>uzanton ĉe alia <a href="http://www.skilledtests.com/wiki/List_of_Independent_Statusnet_Instances">nodo de GNU social</strong></a>, vi devas sekvi <a href="%s">la uzanton %s</a> por ke la konversacio aperu en via nodo kaj vi povu aldoni komentojn al ĝi.', 'wp_gnusocial') . '', $nodo_url, $uzanto_url, $uzanto);

      }
    }

    /**
     * First version of the plugin used to store the user img full path
     * now we store user@gnusocialnode but we want to be backwards compatible
     * The meaning of the diferent options are:
     * user: user@gnusocialnode - We use email validation because has email format so can be validated as so
     * url: Old style image path stored as http://gnusocial.node/whatever/foo.jpg
     * empty: We also check if for some reason this data was not correctly stored
     * @object $comment
     * return $avatar_type {string}
     */
    function wpgs_get_avatar_type($comment) {

      if(filter_var($comment->comment_author_email, FILTER_VALIDATE_EMAIL)){
        $avatar_type = self::USER_AVATAR_TYPE;
      }elseif(filter_var($comment->comment_author_email, FILTER_VALIDATE_URL)){
        $avatar_type = self::URL_AVATAR_TYPE;
      }else{
        $avatar_type = self::EMPTY_AVATAR_TYPE;
      }

      return $avatar_type;
    }

  /**
   * This one will return the url for the default avatar that is configured
   * in the blog
   */
  function wpgs_get_default_avatar() {
    $img_default = get_option( 'avatar_default');

    if($img_default == 'blank'){
      $img_path = get_bloginfo('url') . 'wp-includes/images/blank.gif';
    }else{
      $img_path = 'http://0.gravatar.com/avatar/?d=' . $img_default . '&s='. self::AVATAR_SIZE;
    }

    return $img_path;
  }

  /**
   * Get the $img_path and return the formated img tag
   */
  function wpgs_format_avatar_tag($img_path) {
    return  $img_tag   = '<img src="' . $img_path . '" class="avatar avatar-' .
                       self::AVATAR_SIZE.' photo" height="' . self::AVATAR_SIZE .
                       '" width="' . self::AVATAR_SIZE . '">';
  }


  function wpgs_fetch_or_default($url) {
    $response = wp_remote_get($url);
    if(wp_remote_retrieve_response_code($response) == 200){
      $body      = json_decode(wp_remote_retrieve_body($response));
      $img_path  = $body[0]->user->profile_image_url;

    }else{
      $img_path = self::wpgs_get_default_avatar();
    }
    return $img_path;
  }
  /**
   * Check if we got it in cache and if not, let's fecth the user's avatar from
   * the correct gnu social node
   */
  function wpgs_get_avatar_url($node_host, $user_nick, $user_id = null) {

    if($user_nick){
      /**
       * First try to get the avatar url from cache
       */
      if(!wp_cache_get( $user_nick, 'wpgs_avatars' )){
        $url = 'http://' . $node_host .
             '/api/statuses/user_timeline.json?screen_name=' . $user_nick;

        $img_path = self::wpgs_fetch_or_default($url);
        // Add the avatar url to Wordpress cache
        wp_cache_add( $user_nick, $img_path, 'wpgs_avatars');
      }else{
        $img_path = wp_cache_get( $user_nick, 'wpgs_avatars' );
      }
    }else{
      if(!wp_cache_get( $user_id, 'wpgs_avatars' )){
        $url = 'http://' . $node_host .
             '/api/statuses/user_timeline.json?user_id=' . $user_id;

        $img_path = self::wpgs_fetch_or_default($url);
        // Add the avatar url to Wordpress cache
        wp_cache_add( $user_id, $img_path, 'wpgs_avatars');
      }else{
        $img_path = wp_cache_get( $user_id, 'wpgs_avatars' );
      }

    }

    $img_tag = self::wpgs_format_avatar_tag($img_path);

    return $img_tag;
  }

  /**
   * Explode our user data to get request parameters
   */
  function wpgs_get_avatar_by_user($comment) {
    $user_data = explode('@', $comment->comment_author_email);
    $user_nick = $user_data[0];
    $node_host = $user_data[1];

    $img_tag = self::wpgs_get_avatar_url($node_host, $user_nick);


    return $img_tag;
  }

  /**
   * This one is here to keep backwards compatibility
   * we had comments from the first version wich had their image stored as a url
   * but ... Hey! Let's give them their new avater instead!  ;)
   */

  function wpgs_get_img_url_users_avatar($comment) {
    $node_host = parse_url($comment->comment_author_url, PHP_URL_HOST);
    $user_url_data = explode('/', $comment->comment_author_url);
    $user_id = array_pop($user_url_data);
    return self::wpgs_get_avatar_url($node_host, $user_nick = null, $user_id );
  }

  /**
   * Dispatch type of user avatar and apply correct method
   */
  function wpgs_get_current_avatar($comment, $avatar_type) {
    if($avatar_type == self::USER_AVATAR_TYPE){
      $avatar = self::wpgs_get_avatar_by_user($comment);
    }elseif($avatar_type == self::EMPTY_AVATAR_TYPE){
      $avatar = self::wpgs_format_avatar_tag(self::wpgs_get_default_avatar());
    }else{
      $avatar = self::wpgs_get_img_url_users_avatar($comment);
    }

    return $avatar;
  }

  function wpgs_akiri_avataron($avatar){
    global $post;
    global $comment;

    if(isset($post) && !(get_post_meta( $post->ID, 'wpgs_conversation_id', true ) == '') ) {
      if ($comment) {
        $avatar_type = self::wpgs_get_avatar_type($comment);
        $avatar      = self::wpgs_get_current_avatar($comment, $avatar_type);
      }
    }

    return $avatar;
  }

}
