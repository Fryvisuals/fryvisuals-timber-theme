<?php
/**
 * SPDX-FileCopyrightText: 2012-2020 Jared Novack and contributors
 * SPDX-License-Identifier: MIT
 * SPDX-FileCopyrightText: 2017-2020 Johannes Siipola
 * SPDX-License-Identifier: GPL-2.0-or-later
 */

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    if (!class_exists('Timber')) {
        Timber\Timber::init();
    }
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' .
            esc_url(admin_url('plugins.php#timber')) .
            '">' .
            esc_url(admin_url('plugins.php')) .
            '</a></p></div>';
    });

    add_filter('template_include', function ($template) {
        return get_stylesheet_directory() . '/no-timber.html';
    });
    return;
}

Timber::$dirname = ['src/templates', 'dist'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
    /** Add timber support. */
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'theme_supports']);
        add_filter('timber/context', [$this, 'add_to_context']);
        add_filter('timber/twig', [$this, 'add_to_twig']);
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        parent::__construct();
    }
    /** This is where you can register custom post types. */
    public function register_post_types()
    {
    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies()
    {
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context($context)
    {
        $context['site'] = $this;
        $context['footer_menu'] = Timber::get_menu('Footer');
        $context['primary_menu'] = Timber::get_menu('Primary menu');
        $context['custom_logo_url'] = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
        // $context['dynamic_footer'] = Timber::get_widgets('after-post');
        // $context['footer_form'] = Timber::get_widgets('footer');
        return $context;
    }

    public function theme_supports()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', [
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]);

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        //add_theme_support('post-formats', [
        //    'aside',
        //    'image',
        //    'video',
        //    'quote',
        //    'link',
        //    'gallery',
        //    'audio',
        //]);

        add_theme_support('menus');

        /*
         * Add custom logo support
         */

         $defaults = array(
            'height'               => 53,
            'width'                => 159,
            'flex-height'          => true,
            'flex-width'           => true,
        );

        add_theme_support('custom-logo', $defaults);
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig($twig)
    {
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());
        // $twig->addFilter(new Twig\TwigFilter('myfoo', [$this, 'myfoo']));
        return $twig;
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'style',
            get_template_directory_uri() . '/dist/style.css',
            [],
            $this->get_file_hash('/dist/style.css')
        );
        wp_enqueue_script(
            'scripts',
            get_template_directory_uri() . '/dist/script.js',
            [],
            $this->get_file_hash('/dist/script.js'),
            true
        );
    }

    public static function get_file_hash($file)
    {
        $hash = @md5_file(get_template_directory() . $file);
        if ($hash) {
            return $hash;
        }
        return null;
    }

}

new StarterSite();

// Block Editor Scripts
add_action('enqueue_block_editor_assets', function () {
    // wp_enqueue_script('600-gutenberg-filters', get_template_directory_uri() . '/dist/blockMod.js', ['wp-edit-post']);

    wp_enqueue_style(
        'editor-style',
        get_template_directory_uri() . '/dist/editor.css',
        [],
    );
});



// Register Custom ACF Blocks
add_action('acf/init', 'my_acf_init_block_types');

function my_acf_init_block_types()
{

    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'home_hero',
            'title'             => __('Home Hero'),
            'description'       => __('Home page hero section'),
            'render_template'   => 'template-parts/blocks/home-hero/home-hero.php',
            'category'          => 'media',
            'icon'              => 'admin-home',
            'keywords'          => array('hero', 'home', 'media'),
            'post_types'        => array('page'),
            'parent'            => array('core/post-content'),
            'supports' => array(
                'mode' => false,
                'jsx' => true,
                'align' => false
            )
        ));
        
    }
}