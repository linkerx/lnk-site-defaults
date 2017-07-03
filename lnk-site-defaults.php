<?php

/**
 * Plugin Name: LNK Site defaults
 * Plugin URI: https://github.com/linkerx/lnk-mp-site-defaults
 * Description: Aplica configuraciones por defecto a cada nuevo sitio.
 * Version: 0.1
 * Author: Diego Martinez Diaz
 * Author URI: https://github.com/linkerx
 * License: GPLv3
 */

/**
 * Aplica una lista de personalizaciones a un sitio recien creado
 *
 * @param int $blog_id ID asignado al blog recien creado
 * @param int $user_id
 */
function lnk_new_site($blog_id, $user_id) {
  switch_to_blog($blog_id);

  // Borro el post & page que vienen por defecto
  wp_delete_post(1, true);
  wp_delete_post(2, true);

  // Borro el comentario que viene por defecto
  wp_delete_comment(1, true);

  // Descripcion del Sitio (la uso de nombre interno, por defecto le clono el nombre)
  update_option('blogdescription', get_option('blogname'));

  // Configuraciones de fecha y hora
  update_option('date_format', 'd/m/Y');
  update_option('time_format', 'H:i');

  // Zona Horaria
  update_option( 'gmt_offset','UTC-3' );

  // Creo categoría novedades
  wp_insert_term(
    'Novedades',
    'category',
    array(
      'description'	=> 'Novedades del área. Las que aparecen en la página principal',
      'slug' => 'novedades'
    )
  );

  // La asigno como categoría por defecto
  update_option('default_category', 2);

  // Post por pagina por defecto
  update_option('posts_per_page', 12);
  update_option('posts_per_rss', 6);

  // Opciones de Comentarios y Rastreo
  update_option('default_pingback_flag', 1);
  update_option('default_ping_status','open');
  update_option('default_comment_status','closed');
  update_option('require_name_email',0);
  update_option('comment_registration',1);
  update_option('close_comments_for_old_posts',1);
  update_option('close_comments_days_old',1);
  update_option('comments_notify',1);
  update_option('moderation_notify',1);
  update_option('comment_moderation',1);
  update_option('comment_whitelist',0);

  // Configuraciín de tamaños de imagen
  update_option('thumbnail_size_w','560');
  update_option('thumbnail_size_h','292');
  update_option('thumbnail_crop',1);
  update_option('medium_size_w','800');
  update_option('medium_size_h','800');
  update_option('large_size_w','1200');
  update_option('large_size_h','1200');

  // Configuracion de enlaces permanentes
  update_option('permalink_structure','/%category%/%postname%/');
  update_option('category_base','/.');

  // Creacion del Menú
  $nuevoMenuId = wp_create_nav_menu('menu-del-area');
  register_nav_menu('main-menu-location', __('Menú del Área', 'menu-del-area'));

  // Items iniciales
  wp_update_nav_menu_item($nuevoMenuId,0,array(
    'menu-item-title' => 'Novedades',
    'menu-item-type' => 'taxonomy',
    'menu-item-object' => 'category',
    'menu-item-object-id' => 2,
    'menu-item-classes' => 'novedades',
    'menu-item-status' => 'publish'
  ));

  restore_current_blog();
}

add_action('wpmu_new_blog','lnk_new_site');
