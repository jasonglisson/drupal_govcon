<?php

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;

/**
 * Create content with post_update script for DrupalGovCon demo
 */
function post_update_demo_post_update_demo() {

  vocabulary_create();
  taxonomy_create();
  page_paragraph_create();

}

function vocabulary_create() {

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

    $vocabulary->save();

  } else {

    // Vocabulary Already exist
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', $vid);
    $tids = $query->execute();

  }

}

function taxonomy_create() {

  \Drupal::logger('post_update_demo')->notice('Adding taxonomy to vocabulary...');

  // function that will process data from the given array
  function addTaxonomyData($imagePath, $imageNode, $data) {
    $icon_file = File::create([ 'uri' => $imagePath ]);
    $icon_file->save();
    $data[$imageNode]['target_id'] = $icon_file->id();
    $data[$imageNode]['alt'] = $data['name'];

    Term::create($data)->save();
  }

  // Set the module and image path
  $module_path = drupal_get_path('module', 'post_update_demo');
  $images = $module_path . '/images/';

  // Set vocabulary machine name to use
  $vid = 'drupal_gov_con';

  // Taxonomy data array
  $taxonomies = [
    ['image1.jpg', 'Category1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
    ['image2.jpg', 'Category2', 'Phasellus ac leo ipsum.'],
    ['image3.jpg', 'Category3', 'Curabitur est nibh, suscipit et laoreet vitae, tristique in purus.']
  ];

  // loop through the data array and add taxonomies
  foreach($taxonomies as $key => $array) {
    addTaxonomyData(

      $images . $array[0],
      'field_category_image',
      // pass array that matches the data stucture of the taxonomy term
      [
        'parent' => array(),
        'name' => $array[1],
        'vid' => $vid,
        'description' => [
          'value' => isset($array[2]) ? $array[2] : '',
          'format' => 'formatted',
        ]
      ]
    ); 
  }
}

function page_paragraph_create() {

  function load_tid_by_name($term_name, $vocab) {
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
    ->loadByProperties(['name' => $term_name, 'vid' => $vocab]);
    $term = reset($term);

    return !empty($term) ? $term->id() : 0;
  }

  function createAndAddParagraph($node, $array) {
    $paragraph = Paragraph::create([
      'type' => 'paragraph_demo',
      'field_name' => $array[0],
      'field_program_description' => [
        'value' => $array[1],
        'format' => 'full_html',
      ],
      'field_term_demo' => [
        load_tid_by_name($array[2], $array[3]),
      ]  
    ]);
    $paragraph->save();

    $node->field_paragraphs->appendItem($paragraph);
    
  }

  \Drupal::logger('custom_module')->notice('Custom Module Page Creation and Paragraphs being created...');

  // Create the page
  $node = Node::create(['type' => 'page']);

  $title = 'DrupalGovCon Demo Page';
  $node->set('title', $title); 

  // set data array
  $data = [
    ['Name 1', 'Aliquam porta, leo eget malesuada pulvinar, turpis mi interdum leo.', 'Category1','drupal_gov_con'],
    ['Name 2', 'Nulla velit lorem, vulputate sed lobortis nec, posuere vel dui.', 'Category2','drupal_gov_con'],
    ['Name 3', 'Quisque malesuada, ligula eu iaculis mollis.', 'Category3','drupal_gov_con']
  ];

  // loop over array to add paragraphs to page
  foreach($data as $array) createAndAddParagraph($node, $array);

  // Publish Page
  $node->set('uid', 1);
  $node->status = 1;
  $node->save();
}