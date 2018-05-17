<?php
/**
 * @package Install_starter
 * @version 1.7
 */
/*
Plugin Name: Starter Theme Deploy
Plugin URI: http://newemage.com/
*/
if(!defined( 'WPINC'))
    exit;
$dir = __DIR__;
$file = $dir.'/wp-starter.zip';

  if(!file_exists($file)):
    chmod($dir ,0777); 
    file_put_contents($file, 
        file_get_contents("https://github.com/NoriCode/WPstarter/archive/master.zip")
    );
  endif;

  $zip = new ZipArchive;
  $res = $zip->open($file); 
  if ($res === TRUE) {
      $zip->extractTo(get_theme_root()); 
      $zip->close();
    $mes = "Theme deployed ";
      add_action( 'admin_notices', 'my_acf_notice' );
      unlink($file);
  } 
  else {
      $mes = 'Error. Theme not installed (check dir perms and access to file_get_contents()';   
     add_action( 'admin_notices', 'my_acf_notice' );
  }
  
  //Plugin deactivates right after operation, fix errors and activate again
     add_action( 'admin_init', 'my_plugin_deactivate' );


function my_plugin_deactivate() {
          deactivate_plugins( plugin_basename( __FILE__ ) );
      }

function my_acf_notice() {
  global $mes;
  ?>
  <div class="update-nag notice">
      <p><?=$mes?></p>
  </div>
  <?php
}
