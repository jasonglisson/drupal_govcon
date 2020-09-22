<?php

use Drupal\node\Entity\Node;

function post_update_demo_post_update_page() {

  // Comment for logging what is going on
  \Drupal::logger('post_update_demo')->notice('Page is being created...');

  // Creating landing page
  $node = Node::create(['type' => 'page']);

  // Set page title
  $title = 'DrupalGovCon Demo Page';
  $node->set('title', $title);   

  // Set body copy
  $body = [
    'value' => '<p>This is a sample page produed by the post-update script.</p>',
    'format' => 'full_html',
  ];
  $node->set('body', $body);

  // Publish Page
  $node->set('uid', 1);
  $node->status = 1;
  $node->save();

}