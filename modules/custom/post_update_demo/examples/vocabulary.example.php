<?php

use Drupal\field\Entity\FieldStorageConfig;
use Drupal\field\Entity\FieldConfig;

function post_update_demo_post_update_vocabulary() {

  \Drupal::logger('post_update_demo')->notice('Vocabulary is being created...');

  $vid = 'drupal_gov_con';
  $name = 'Drupal Gov Con';
  $vocabularies = \Drupal\taxonomy\Entity\Vocabulary::loadMultiple();
  
  if (!isset($vocabularies[$vid])) {
    $vocabulary = \Drupal\taxonomy\Entity\Vocabulary::create(array(
          'vid' => $vid,
          'description' => '',
          'name' => $name,
    ));
    
    $fields['category_image'] = [
      'type' => 'image',
      'entity_type' => 'user',
      'bundle' => 'user',
      'label' => 'Category Picture',
      'description' => 'Picture for each category.',
      'required' => FALSE,
      'widget' => [
        'type' => 'image_image',
        'settings' => [
          'progress_indicator' => 'throbber',
          'preview_image_style' => 'thumbnail',
        ],
      ],
      'formatter' => [
        'default' => [
          'type' => 'image',
          'label' => 'hidden',
          'settings' => [
            'image_style' => 'thumbnail',
            'image_link' => 'content',
          ],
        ],
      ],
      'settings' => [
        'file_extensions' => 'png gif jpg jpeg',
        'file_directory' => 'images/[date:custom:Y]-[date:custom:m]',
        'max_filesize' => '',
        'max_resolution' => '',
        'alt_field' => FALSE,
        'title_field' => FALSE,
        'alt_field_required' => FALSE,
        'title_field_required' => FALSE,
      ],
    ];

    foreach ($fields as $field_name => $config) {
      $field_storage = FieldStorageConfig::loadByName($config['entity_type'], $field_name);
      if (empty($field_storage)) {
        FieldStorageConfig::create(array(
          'field_name' => $field_name,
          'entity_type' => $config['entity_type'],
          'type' => $config['type'],
        ))->save();
      }
    }


    $vocabulary->save();

  } else {

    // Vocabulary Already exist
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vid);
    $tids = $query->execute();

  }

}