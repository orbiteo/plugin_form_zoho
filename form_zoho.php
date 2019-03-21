<?php
/*
Plugin Name: Form Zoho
Plugin URI: https://orbiteo.com/wp-content/plugins/form_zoho/
Description: Création ou modification de formulaires en lien avec l'API de Zoho
Author: Orbiteo
Version: 1.0
Author URI: https://orbiteo.com
*/

// create custom plugin settings menu
include_once( plugin_dir_path( __FILE__ ) . 'shortcodeFonction.php');
add_action('admin_menu', 'my_plugin_form_zoho');
function my_plugin_form_zoho() {
// This page will be under "Settings"
    add_options_page(
        'Form Zoho',
        'Form Zoho',
        'manage_options',
        'admin-manage-form_zoho','my_plugin_form_zoho_settings_page'
    );
    //call register settings function
    add_action( 'admin_init', 'register_my_plugin_form_zoho_settings' );

    wp_enqueue_style( 'manage-form_zoho-style', plugins_url( 'assets/css/style.css', __FILE__ ) );
    /*** Affichage calendrier JQuery UI ***/
}
add_shortcode('shortcodeOrbiteoFormZoho', 'create_shortcode_form_zoho');
function register_my_plugin_form_zoho_settings() {
    //register our settings
    register_setting( 'admin-manage-form_zoho', 'formName' );
    register_setting( 'admin-manage-form_zoho', 'formDetails' );
}
function my_plugin_form_zoho_settings_page() {
  if(isset($_GET["action"]) && !empty($_GET["action"])) {
    $formName = '';
    $formDetails = '';
    $formId = '';
    if(isset($_GET["idShortcode"]) && !empty($_GET["idShortcode"])) {
      $idShortcode = htmlentities($_GET["idShortcode"]);
      global $wpdb;
      $table_prefix = $wpdb->prefix;
    	$prefix = $table_prefix.'posts';
      $wpdb->query("SELECT * FROM $prefix WHERE `ID` = $idShortcode");
      $result = $wpdb->last_result;
      foreach ($result as $detResult) {
        $formName = $detResult->post_name;
        $formDetails = $detResult->post_content;
        $formId = $detResult->ID;
      }
    }
    ?>
      <h2>Insérez ici le formulaire html</h2>
      <form method="post" action="<?php echo plugin_dir_url( __FILE__ );?>controleur/addForm.php">
        <input type="hidden" name="formId" value="<?php if(!empty($formId)) { echo $formId;}?>">
        <input type="text" name="formName" placeholder="Nom du shortcode" value="<?php if(!empty($formName)) { echo $formName;}?>" required />
        <textarea name="formDetails" rows="20" cols="80">
          <?php if(!empty($formDetails)) { echo $formDetails;}?>
        </textarea>
        <button type="submit" name="button">ENVOYER</button>
      </form>
    <?php
  }
  else {
    ?>
  <div class="wrap">
    <h1>Formulaires - API Zoho</h1>
    <?php
    global $wpdb;
    $table_prefix = $wpdb->prefix;
  	$prefix = $table_prefix.'posts';
    $wpdb->query("SELECT * FROM $prefix WHERE `post_type` = 'orbiteoformzoho'");
    $result = $wpdb->last_result;
    ?>
    <table class="form-table form-table-form-zoho">
        <thead>
            <tr valign="top">
                <th>Titre</th>
                <th>Shortcode</th>
                <th>Date</th>
            </tr>
        </thead>
        <?php
        foreach ($result as $shortcode) {
          ?>
          <tbody class="form-zoho-tbody">
              <tr valign="top">
                <td><a href="<?php echo get_site_url();?>/wp-admin/options-general.php?page=admin-manage-form_zoho&action=modify&idShortcode=<?php echo $shortcode->ID;?>"><?php echo $shortcode->post_name; ?></td>
                <td><?php echo "[shortcodeOrbiteoFormZoho id=\"".$shortcode->ID."\" title=\"".$shortcode->post_name."\"]"; ?></td>
                <td><?php echo $shortcode->post_date; ?></td>
              <tr>
          </tbody>
          <?php
          }
          ?>
          <tfoot>
            <tr valign="top">
                <th>Titre</th>
                <th>Shortcode</th>
                <th>Date</th>
            </tr>
          </tfoot>
    </table>
  </form>
  <a href="<?php echo get_site_url();?>/wp-admin/options-general.php?page=admin-manage-form_zoho&action=create"><button type="button" name="newFormZoho">CRÉEZ UN NOUVEAU FORMULAIRE</button></a>
  </div>
  <?php
  }
}
