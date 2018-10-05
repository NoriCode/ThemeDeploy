<?php
/**
 * @package Install_starter
 * @version 1.8.1
 */
/*
Plugin Name: Starter Theme Deploy
Plugin URI: http://newemage.com/
*/
if(!defined( 'WPINC'))
    exit;
$dir = __DIR__;
$file = $dir.'/wp-starter.zip';
$tr = get_theme_root();
$del = ['twentysixteen','twentyseventeen','twentyfifteen'];

  if(!file_exists($file)):
    chmod($dir ,0777); 
    file_put_contents($file, 
        file_get_contents("https://github.com/NoriMx/WPstarter/archive/master.zip")
    );
  endif;

  $zip = new ZipArchive;
  $res = $zip->open($file); 
  if ($res === TRUE) {
      switch_theme(trim($zip->getNameIndex(0), '/'));
      $zip->extractTo($tr); 
      $zip->close();
      foreach ($del as $d)
      { if(is_dir("$tr/$d"))
         deletos("$tr/$d"); }
    $mes = "Theme deployed ";
      add_action( 'admin_notices', 'my_acf_notice' );
      unlink($file);
  } 
  else {
      $mes = 'Error. Theme not installed (check dir perms, port blocks and access to file_get_contents()';   
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

update_option( 'default_ping_status', 'closed' );
update_option( 'default_pingback_flag', 0 );
update_option( 'default_comment_status', 'closed' );
update_option( 'comment_registration', 1 );
update_option( 'comment_moderation', 1 );

function deletos($dir) { 
  $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? deletos("$dir/$file") : unlink("$dir/$file"); 
    } 
    rmdir($dir); 
  } 

