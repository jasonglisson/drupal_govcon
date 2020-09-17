<?php
function post_update_demo_post_add_paragraphs() {

  use Drupal\paragraphs\Entity\Paragraph;

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

  \Drupal::logger('custom_module')->notice('Custom Page and Paragraphs being created...');

  // Create the page
  $node = Node::create(['type' => 'page']);

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
