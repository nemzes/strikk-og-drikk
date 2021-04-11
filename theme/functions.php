<?php

/**
 * Author: Robert DeVore | @deviorobert
 * URL: html5blank.com | @html5blank
 * Custom functions, support, custom post types and more.
 */

// require_once 'modules/is-debug.php';

/*------------------------------------*\
  Theme Support
\*------------------------------------*/

if (!isset($content_width)) {
  $content_width = 900;
}

if (function_exists('add_theme_support')) {

  // Add Thumbnail Theme Support.
  add_theme_support('post-thumbnails');

  // Enables post and comment RSS feed links to head.
  add_theme_support('automatic-feed-links');

  // Disable colour pickers in blocks
  add_theme_support('editor-color-palette');
  add_theme_support('disable-custom-colors');

  // Enable HTML5 support.
  add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

  // Localisation Support.
  load_theme_textdomain('html5blank', get_template_directory() . '/languages');

  update_option('thumbnail_size_w', 400);
  update_option('thumbnail_size_h', 300);

  update_option('medium_size_w', 800);
  update_option('medium_size_h', 600);

  update_option('large_size_w', 1200);
  update_option('large_size_h', 900);
}

function ssod_disable_event_organiser_css($options)
{
  $options['disable_css'] = 1;
  return $options;
}

add_filter('eventorganiser_options', 'ssod_disable_event_organiser_css');

/*------------------------------------*\
  Functions
\*------------------------------------*/

// Load HTML5 Blank scripts (header.php)
function ssod_scripts()
{
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
    // Custom scripts
    $version = wp_get_theme()->get('Version');
    
    wp_register_script(
      'ssod_scripts',
      get_template_directory_uri() . '/js/scripts.js',
      array(),
      $version
    );

    // Enqueue Scripts
    wp_enqueue_script('ssod_scripts');
  }
}

// Load HTML5 Blank styles
function html5blank_styles()
{
  $version = wp_get_theme()->get('Version');

  // normalize-css
  wp_register_style('sanitize', get_template_directory_uri() . '/css/lib/sanitize.css', array(), '8.0.0');

  // Custom CSS
  wp_register_style('sogd-settings', get_template_directory_uri() . '/css/settings.css', array('sanitize'), $version);
  wp_register_style('sogd-generic', get_template_directory_uri() . '/css/generic.css', array('sogd-settings'), $version);
  wp_register_style('sogd-objects', get_template_directory_uri() . '/css/objects.css', array('sogd-settings'), $version);
  wp_register_style('sogd-overrides', get_template_directory_uri() . '/css/overrides.css', array('sogd-settings'), $version);

  // Register CSS
  wp_enqueue_style('sanitize');
  wp_enqueue_style('sogd-settings');
  wp_enqueue_style('sogd-generic');
  wp_enqueue_style('sogd-objects');
  wp_enqueue_style('sogd-overrides');
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
  $args['container'] = false;
  return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
  return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
  return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
  global $post;
  if (is_home()) {
    $key = array_search('blog', $classes, true);
    if ($key > -1) {
      unset($classes[$key]);
    }
  } elseif (is_page()) {
    $classes[] = sanitize_html_class($post->post_name);
  } elseif (is_singular()) {
    $classes[] = sanitize_html_class($post->post_name);
  }

  return $classes;
}

// Remove the width and height attributes from inserted images
function remove_width_attribute($html)
{
  $html = preg_replace('/(width|height)="\d*"\s/', "", $html);
  return $html;
}


// If Dynamic Sidebar Exists
if (function_exists('register_sidebar')) {
  register_sidebar(array(
    'name'      => 'Front page widgets',
    'description'   => 'Displayed below the event list',
    'id'      => 'ssod-widgets-front-page',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>',
  ));

  register_sidebar(array(
    'name'      => 'Footer widgets',
    'description'   => 'Displayed in footer in all pages',
    'id'      => 'ssod-widgets-footer',
    'before_widget' => '<div id="%1$s" class="%2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h2>',
    'after_title'   => '</h2>',
  ));
}

// Remove wp_head() injected Recent Comment styles
function ssod_remove_recent_comments_style()
{
  global $wp_widget_factory;

  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action('wp_head', array(
      $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
      'recent_comments_style'
    ));
  }
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function sogd_pagination()
{
  global $wp_query;
  $big = 999999999;
  echo paginate_links(array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'current' => max(1, get_query_var('paged')),
    'format' => '?paged=%#%',
    'next_text' => 'neste',
    'prev_text' => 'forrige',
    'total' => $wp_query->max_num_pages,
  ));
}

// Create 20 Word Callback for Index page Excerpts, call using ssod_excerpt('ssod_excerpt_len_index');
function ssod_excerpt_len_index($length)
{
  return 60;
}

// Create 40 Word Callback for Event Excerpts, call using ssod_excerpt('ssod_excerpt_len_event');
function ssod_excerpt_len_event($length)
{
  return 10;
}

function ssod_excerpt_more_none()
{
  return '…';
}

function ssod_get_event_classes($eventId)
{
  $tags_list = get_the_terms($eventId, 'event-tag');

  if (!$tags_list) {
    return '';
  }

  $classes = array_map(
    function ($tag) {
      return 'ssod-event-tag-' . $tag->name;
    },
    $tags_list
  );

  return 'ssod-event-tag ' . implode(' ', $classes);
}

// Create the Custom Excerpts callback
function ssod_excerpt($length_callback = '', $more_callback = '', $id = null)
{
  if (function_exists($length_callback)) {
    add_filter('excerpt_length', $length_callback);
  }
  if (function_exists($more_callback)) {
    add_filter('excerpt_more', $more_callback);
  }
  $output = get_the_excerpt();
  $output = apply_filters('wptexturize', $output);
  $output = apply_filters('convert_chars', $output);
  echo ($output);
}

// Custom View Article link to Post
function sogd_view_article($more)
{
  global $post;
  return '… <a class="ssod-view-article" href="' . get_permalink($post->ID) . '">Les innlegg</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
  return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
  return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html)
{
  $html = preg_replace('/(width|height)=\"\d*\"\s/', '', $html);
  return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar($avatar_defaults)
{
  $myavatar           = get_template_directory_uri() . '/img/gravatar.jpg';
  $avatar_defaults[$myavatar] = 'Custom Gravatar';
  return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
  if (!is_admin()) {
    if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
      wp_enqueue_script('comment-reply');
    }
  }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;
  extract($args, EXTR_SKIP);

  if ('div' == $args['style']) {
    $tag     = 'div';
    $add_below = 'comment';
  } else {
    $tag     = 'li';
    $add_below = 'div-comment';
  }
?>
  <!-- heads up: starting < for the html tag (li or div) in the next line: -->
  <<?php echo esc_html($tag) ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID(); ?>">
    <?php if ('div' != $args['style']) : ?>
      <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
      <?php endif; ?>
      <div class="comment-author vcard">
        <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
        <?php printf(esc_html('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
        <em class="comment-awaiting-moderation"><?php esc_html('Your comment is awaiting moderation.') ?></em>
        <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
          <?php
          printf(esc_html('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(esc_html('(Edit)'), '  ', '');
                                                                                          ?>
      </div>

      <?php comment_text() ?>

      <div class="reply">
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
      <?php if ('div' != $args['style']) : ?>
      </div>
    <?php endif; ?>
  <?php }

/*------------------------------------*\
  Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('wp_enqueue_scripts', 'ssod_scripts'); // Add Custom Scripts to wp_head
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
add_action('widgets_init', 'ssod_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'sogd_pagination');
add_action('init', 'sogd_register_menu');
add_action('get_header', 'remove_admin_login_header');

function remove_admin_login_header()
{
  remove_action('wp_head', '_admin_bar_bump_cb');
}

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter( 'nav_menu_css_class', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter( 'nav_menu_item_id', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter( 'page_css_class', 'my_css_attributes_filter', 100, 1 ); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'sogd_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('post_thumbnail_html', 'remove_width_attribute', 10); // Remove width and height dynamic attributes to post images
add_filter('image_send_to_editor', 'remove_width_attribute', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]


function sogd_register_menu()
{
  register_nav_menu('global-nav', __('Global navigation'));
}

// ---

function sogd_get_festivals()
{
  return get_posts([
    'post_type' => 'sogd-festival',
    'post_status' => 'publish',
    'numberposts' => -1
  ]);
}

function sogd_get_category_festival($cat_id)
{
  $festivals = sogd_get_festivals();
  $cat_parents = get_ancestors($cat_id, 'category');

  if (!empty($cat_parents)) {
    foreach ($festivals as $festival) {
      $festival_parent_cat = sogd_get_festival_parent_cat($festival);

      if ($cat_id === $festival_parent_cat) {
        return $festival;
      }

      if (in_array($festival_parent_cat, $cat_parents)) {
        return $festival;
      }
    }
  }

  return false;
}

function sogd_get_post_festival($post_id)
{
  $post_type = get_post_type($post_id);

  if ($post_type === 'sogd-festival') {
    return get_post($post_id);
  }

  if ($post_type === 'sogd-speaker') {
    $festival_id = get_post_meta($post_id, 'sogd_festival', true);
    return get_post($festival_id);
  }

  $festivals = sogd_get_festivals();
  $post_cats = get_the_category($post_id);

  if (!empty($post_cats)) {
    foreach ($post_cats as $post_cat) {
      $post_cat_id = $post_cat->term_id;
      $post_cat_parents = get_ancestors($post_cat_id, 'category');

      foreach ($festivals as $festival) {
        $festival_parent_cat = sogd_get_festival_parent_cat($festival);

        if ($post_cat_id === $festival_parent_cat) {
          return $festival;
        }

        if (in_array($festival_parent_cat, $post_cat_parents)) {
          return $festival;
        }
      }
    }
  }
}

function sogd_get_event_festival($event_id)
{
  $festivals = sogd_get_festivals();
  $event_cats = get_the_terms($event_id, 'event-category');

  if (!empty($event_cats)) {
    foreach ($event_cats as $event_cat) {
      $event_cat_id = $event_cat->term_id;
      $event_cat_parents = get_ancestors($event_cat_id, 'event-category');

      foreach ($festivals as $festival) {
        $festival_parent_cat = sogd_get_festival_parent_event_cat($festival);

        if ($event_cat_id === $festival_parent_cat) {
          return $festival;
        }

        if (in_array($festival_parent_cat, $event_cat_parents)) {
          return $festival;
        }
      }
    }
  }
}

function sogd_get_festival_parent_cat($festival)
{
  $festival_fields = get_post_meta($festival->ID, 'sogd_festival', true);
  return $festival_fields ? (int)$festival_fields['posts-cat'] : null;
}

function sogd_get_festival_parent_event_cat($festival)
{
  $festival_fields = get_post_meta($festival->ID, 'sogd_festival', true);
  return $festival_fields ? (int)$festival_fields['events-cat'] : null;
}

function sogd_output_festival_header($festival)
{
  ?>
    <header class="festival-header">
      <div class="festival-header_content">
        <h1>
          <a href="<?php the_permalink($festival->ID) ?>">
            <?php echo esc_html($festival->post_title); ?>
          </a>
        </h1>
        <nav>
          <?php sogd_output_festival_links($festival) ?>
        </nav>
      </div>
    </header>
    <?php
  }

  function sogd_output_festival_links($festival)
  {
    if ($festival_parent_cat = sogd_get_festival_parent_cat($festival)) {
    ?>
      <ul>
        <li>
          <a href="<?php the_permalink($festival->ID) ?>/program">
            Program
          </a>
        </li>
        <li>
          <a href="<?php the_permalink($festival->ID) ?>/bidragsytere">
            Bidragsytere
          </a>
        </li>

        <?php
        wp_list_categories(array(
          'child_of' => $festival_parent_cat,
          'hide_empty' => false,
          'hierarchical' => false,
          'title_li' => null,
          'show_option_none' => false,
        ));

        $festival_posts = sogd_get_festival_root_posts($festival_parent_cat);

        foreach ($festival_posts as $post) {
        ?>
          <li>
            <a href="<?php the_permalink($post->ID) ?>">
              <?php echo htmlspecialchars($post->post_title); ?>
            </a>
          </li>
        <?php
        }
        ?>
      </ul>
  <?php
    }
  }

  function sogd_get_festival_root_posts($festival_cat_id)
  {
    $query = new WP_Query(array(
      'category__in' => $festival_cat_id
    ));

    return $query->get_posts();
  }

  function sogd_output_page_content($page_id)
  {
    $query = new WP_Query(array(
      'p' => $page_id,
      //'post_type' => 'page',
    ));

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        the_content(false);
      }
      wp_reset_postdata();
    }
  }

  function sogd_get_all_festivals_parent_cats()
  {
    $festivals = sogd_get_festivals();

    $cats = array_map(
      function ($festival) {
        return sogd_get_festival_parent_cat($festival);
      },
      $festivals
    );

    return $cats;
  }

  function sogd_get_all_festivals_parent_events_cats()
  {
    $festivals = sogd_get_festivals();

    $cats = array_map(
      function ($festival) {
        return sogd_get_festival_parent_event_cat($festival);
      },
      $festivals
    );

    return $cats;
  }

  function sogd_get_category_classname($cat)
  {
    return 'cat-' . $cat->term_id;
  }
