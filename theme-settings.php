<?php

/**
 * @file
 * Functions to support theming in the SASS Starterkit subtheme.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Implements hook_form_system_theme_settings_alter() for settings form.
 *
 * Replace Barrio setting options with subtheme ones.
 *
 * Example on how to alter theme settings form
 */
function aeira_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {
 
  if (isset($form_id)){
    return;
  }

  // Opcións de configuración do tema 
 
  $form['aeira_settings'] = [
    '#type' => 'vertical_tabs',
    '#prefix' => '<h2><small>' . t('Configuración da Eira') . '</small></h2>',
    '#weight' => -100,
  ];
   // Imaxe.
   $form['imaxe'] = [
    '#type' => 'details',
    '#title' => t('Imaxe principal'),
    '#group' => 'aeira_settings',
  ]; 
  $form['imaxe']['imaxe_principal'] = array(
    '#type'          => 'managed_file',
    '#title'         => t('Imaxe principal'),
    '#default_value' => theme_get_setting('imaxe_principal','aeira'),
    '#description'   => t("Engade a imaxe principal a paxina principal"),
    '#upload_location' => 'public://portada', //carpeta destino da foto de portada
    '#upload_validators'    => [
      'file_validate_extensions'    => array('png jpg jpeg'), // extensións permitidas
     // 'file_validate_size'          => array(1000000),  // tamaño máximo da foto da portada 
      'file_validate_image_resolution' => ['2000x2000'], // resolucion coa que se carga na web
    ],
    '#cardinality' => 3,
  );
  



  $form['pe'] = [
    '#type' => 'details',
    '#title' => t('Pe do sitio'),
    '#group' => 'aeira_settings',
  ];
  // promotores
  $form['pe']['promotores']= [
    '#type' => 'details',
    '#title' => t('Promoven'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#multiple' => TRUE,
  ];
  $form['pe']['promotores']['logo_promotor'] = array(
    '#type'          => 'managed_file',
    '#title'         => t('Logo do promotor'),
    '#multiple' => TRUE,
    '#default_value' => theme_get_setting('logo_promotor','aeira'),
    '#description'   => t("Engade o logo do promotor ou promotores"),
    '#upload_location' => 'public://portada', //carpeta destino da foto de portada
    '#upload_validators'    => [
      'file_validate_extensions'    => array('png jpg jpeg'), // extensións permitidas
      'file_validate_size'          => array(100000),  // tamaño máximo da foto da portada      
    ],
  );
    // patrocinan
    $form['pe']['patrocinador']= [
      '#type' => 'details',
      '#title' => t('Patrocinan'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#multiple' => TRUE,
    ];
    $form['pe']['patrocinador']['logo_patrocinador'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Logo do patrocinador'),
      '#multiple' => TRUE,
      '#default_value' => theme_get_setting('logo_patrocinador','aeira'),
      '#description'   => t("Engade o logo do patrocionador ou patrocinadores"),
      '#upload_location' => 'public://portada', //carpeta destino da foto de portada
      '#upload_validators'    => [
        'file_validate_extensions'    => array('png jpg jpeg'), // extensións permitidas
        'file_validate_size'          => array(100000),  // tamaño máximo da foto da portada      
      ],
    );

  $form['pe']['asociacion']= [
    '#type' => 'details',
    '#title' => t('Datos da asociación'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#multiple' => TRUE,
  ];

  $form['pe']['asociacion']['nome_asociacion'] = array (
    '#type'          => 'textfield',
    '#title'         => t('Nome da asociación'),
    //  '#required' => TRUE,
    '#default_value' => theme_get_setting('nome_asociacion'),
  
    '#description'   => t("Indica o nome da asociación que fai o proxecto"),
    '#cardinality' => 3,
  );
  $form['pe']['asociacion']['url_asociacion'] = array (
    '#type'          => 'url',
    '#title'         => t('Web da asociación'),
    '#default_value' => theme_get_setting('url_asociacion'),
    '#description'   => t("Indica o enderezo web  da asociación que fai o proxecto"),
  );
  
    $form['pe']['asociacion']['logo_asociacion'] = array(
      '#type'          => 'managed_file',
      '#title'         => t('Logo da asociación'),
     
      '#default_value' => theme_get_setting('logo_asociacion','aeira'),
      '#description'   => t("Engade o logo da asociación"),
      '#upload_location' => 'public://portada', //carpeta destino da foto de portada
      '#upload_validators'    => [
        'file_validate_extensions'    => array('png jpg jpeg'), // extensións permitidas
        'file_validate_size'          => array(100000),  // tamaño máximo da foto da portada 
       
      ],
  
  
  );
     
  
  


  $form['#submit'][] = 'aeira_settings_form_submit';
  $theme = \Drupal::theme()->getActiveTheme()->getName();
  $theme_file = drupal_get_path('theme', $theme) . '/theme-settings.php';
  $build_info = $form_state->getBuildInfo();
  if (!in_array($theme_file, $build_info['files'])) {
    $build_info['files'][] = $theme_file;
  }
  $form_state->setBuildInfo($build_info);

 

}
 
function aeira_settings_form_submit(&$form, FormStateInterface $form_state) {
    // é necesario marcar como permanente para que non sexa borrada polo sistema
    if ($file_id = $form_state->getValue(['imaxe_principal', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }
    if ($file_id = $form_state->getValue(['logo_asociacion', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }
    if ($file_id = $form_state->getValue(['logo_patrocinador', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }
    if ($file_id = $form_state->getValue(['logo_promotor', '0'])) {
      $file = \Drupal::entityTypeManager()->getStorage('file')->load($file_id);
      $file->setPermanent();
      $file->save();
    }
 
 
 
}