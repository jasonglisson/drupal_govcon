<?php
/**
 * This update will load all content into the Disaster Assistance Tool
 * 
 */

use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;

function farmers_dadt_post_update_dadt() {
  addTaxonomies();
  addContent();
}

function addTaxonomies() {
  function addTaxonomyData($imagePath, $imageNode, $data) {
    $icon_file = File::create([ 'uri' => $imagePath ]);
    $icon_file->save();
    $data[$imageNode]['target_id'] = $icon_file->id();
    $data[$imageNode]['alt'] = $data['name'];

    Term::create($data)->save();
  }

  // Disasters
  // Setting the dadt module image path
  $module_path = drupal_get_path('module', 'farmers_dadt');
  $dadt_images = $module_path . '/images/';

  // Create terms and add them to the vocabulary
  $disasterTaxonomies = [
    ['avian-animal.svg', 'Avian/animal attack', 'Other'],
    ['blizzard.svg', 'Blizzard', 'Low Temperatures'],
    ['biohazard.svg', 'Biohazard', 'Other', 'Examples: disease, anthrax, colony collapse disorder'],
    ['drought.svg', 'Drought', 'High Temperatures'],
    ['earthquake.svg', 'Earthquake', 'Other'],
    ['excess-moisture.svg', 'Excess moisture', 'Water'],
    ['excess-wind.svg', 'Excessive wind', 'Storms'],
    ['excess-heat.svg', 'Extreme heat', 'High Temperatures'],
    ['flood.svg', 'Flood', 'Water'],
    ['frost.svg', 'Freeze/frost', 'Low Temperatures'],
    ['hail.svg', 'Hail', 'Storms'],
    ['hurricane.svg', 'Hurricane', 'Storms'],
    ['ice-storm.svg', 'Ice storm', 'Low Temperatures'],
    ['insufficient-chill-hours.svg', 'Insufficient chill hours', 'High Temperatures'],
    ['land-mudslide.svg', 'Land/mudslide', 'Water'],
    ['lightning.svg', 'Lightning', 'Storms'],
    ['plant-disease-infest.svg', 'Plant/disease infestation', 'Other'],
    ['tidal-surge.svg', 'Tidal surge', 'Water'],
    ['tornado.svg', 'Tornado', 'Storms'],
    ['tropical-storm.svg', 'Tropical storm', 'Storms'],
    ['volcano.svg', 'Volcanic eruption', 'Other'],
    ['wildfire.svg', 'Wildfire', 'High Temperatures'],
    ['winter-storm.svg', 'Winter storm', 'Low Temperatures']
  ];

  foreach($disasterTaxonomies as $key => $array) {
     addTaxonomyData(
      $dadt_images . $array[0],
      'field_disasters_icon',
      [
        'parent' => array(),
        'name' => $array[1],
        'vid' => 'disaster_assist_prog_disasters',    
        'field_disaster_id' => $key + 1,
        'field_disaster_type' => $array[2],
        'field_disaster_description' => [
          'value' => isset($array[3]) ? $array[3] : '',
          'format' => 'formatted',
        ]
      ]
    ); 
  }

  // Crops
  // Create terms and add them to the vocabulary
  $cropTaxonomies = [
    [
      'aquaculture.svg', 
      'Aquaculture',
      'Animals',
      'Examples: crustaceans, mollusks, oysters',
    ],
    ['bush-vine.svg', 'Bushes or vines', 'Plants'],
    ['christmas-tree-plantation.svg', 'Christmas tree plantations', 'Lands'],
    ['christmas-trees.svg', 'Christmas trees', 'Plants'],
    [
      'conservation-structures.svg', 
      'Conservation structures',
      'Other',
      'Examples: crossfence/exclusion fence, culvert, pipedrop, water control structure, dam or levee, pond, grassed waterway, or streambank'
    ],
    [
      'crops-food.svg', 
      'Crop grown for food', 
      'Plants',
      'Examples: blueberries, peaches, ginseng, honey, maple sap, mushrooms, seed crops'
    ],
    [
      'crops-livestock.svg', 
      'Crop grown for live-stock consumption', 
      'Plants',
      'Examples: grain and forage crops, including native forage'
    ],
    ['cropland.svg', 'Croplands', 'Lands'],
    ['farm-raised-fish.svg', 'Farm-raised fish', 'Animals'],
    [
      'feed-loss.svg', 
      'Feed losses',
      'Plants', 
      'Examples: loss of purchased or mechanically harvested forage or feed stuffs, or a loss that results in the purchase of additional livestock feed above normal quantities'
    ],
    [
      'field-windbreak.svg', 
      'Field wind breaks',
      'Lands', 
      'Example: a row of trees or a fence, wall, or screen that provides shelter or protection from the wind'
    ],
    [
      'floriculture.svg', 
      'Floriculture',
      'Plants',
      'Example: flowering and ornamental plants for gardens and for floristry'
    ],
    [
      'grazing.svg', 
      'Grazing',
      'Plants',
      'Example: crops grown for livestock to consume and forage, such as grass, wheat, alfalfa, mixed forage, etc.'
    ],
    ['honeybee.svg', 'Honeybees', 'Animals'],
    [
      'industrial-crop.svg', 
      'Industrial crops', 
      'Plants',
      'Example: non-food crops grown to produce goods for manufacturing rather than food for consumption, such as biofuels, fiber, etc.'
    ],
    ['livestock.svg', 'Livestock', 'Animals'],
    [
      'natural-resc-issues.svg', 
      'Natural resource', 
      'Lands',
      'Examples: severe soil erosion, streambank stability, stream channel blockage by debris, potential soil erosion of denuded landscape, catastrophic animal loss'
    ],
    [
      'non-industrial-private-forestland.svg',
      'Non-industrial private forestland', 
      'Lands',
      'Example: land owned by non-industrial private individuals, groups, associations, corporations, or other private legal entities'
    ],
    ['nurseries.svg', 'Nurseries', 'Lands'],
    ['orchards.svg', 'Orchard or nursery trees', 'Plants'],
    ['orchards-tree-plantations.svg', 'Orchards and tree plantations', 'Lands'],
    [
      'ornamental-nursery.svg', 
      'Ornamental nursery',
      'Plants',
      'Examples: deciduous shrubs, broadleaf evergreens, coniferous evergreens, shade trees, and flowering trees'
    ],
    ['pastureland.svg', 'Pastureland', 'Lands'],
    ['sea-grass.svg', 'Sea Oats and sea grass', 'Plants'],
    ['turfgrass.svg', 'Turfgrass sod', 'Plants'],
    [
      'water-transportation.svg', 
      'Water transportation', 
      'Other',
      'Example: occurs when a producer hires out water and transports it to the farm for their livestock after a drought impacts the water supply on a farm or ranch'
    ],
    [
      'watershed-features.svg', 
      'Watershed features or structures', 
      'Other',
      'Examples: dams or levees, streams, rivers, ditches, retention ponds, large scale control structures, or bridges'
    ]
  ];

  foreach($cropTaxonomies as $key => $array) {
     addTaxonomyData(
      $dadt_images . $array[0],
      'field_crops_icon',
      [ 
        'parent' => array(),
        'name' => $array[1],
        'vid' => 'disaster_assist_prog_crops',    
        'field_crop_id' => $key + 1,
        'field_crop_types' => $array[2],
         'field_crop_description' => [
          'value' => isset($array[3]) ? $array[3] : '',
          'format' => 'formatted',
        ]
      ]
    );
  }
}

function addYears() {
  function createYears(){
    $year = [
      'type' => 'program_year_and_eligibility_ran',
      'field_program_eligibility_date_r' => [
        'value' => $startYear,
        'end_value' => $endYear,
      ],
      'field_program_year' => $mainYear
    ];
    Paragraph::create($year)->save();
    return !empty($year) ? $year : 0;
  }

  // $programYears = [
  //   // PROGRAM: ELAP
  //   [
  //     'field_program_eligibility_date_r' => ['2016-10-01', '2017-09-30'],
  //     'field_program_year' => '2017'
  //   ],
  //   [
  //     'field_program_eligibility_date_r' => ['2017-10-01', '2018-09-30'],
  //     'field_program_year' => '2018'
  //   ],
  //   [
  //     'field_program_eligibility_date_r' => ['2018-10-01', '2019-09-30'],
  //     'field_program_year' => '2019'
  //   ],   
  //   [
  //     'field_program_eligibility_date_r' => ['2020-01-01', '2020-12-31'],
  //     'field_program_year' => '2020'
  //   ],
  //   // PROGRAM: LFP
  //   [
  //     'field_program_eligibility_date_r' => ['2017-01-01', '2017-12-31'],
  //     'field_program_year' => '2017'    
  //   ],
  //   [
  //     'field_program_eligibility_date_r' => ['2018-01-01', '2018-12-31'],
  //     'field_program_year' => '2018'    
  //   ],
  //   [
  //     'field_program_eligibility_date_r' => ['2019-01-01', '2019-12-31'],
  //     'field_program_year' => '2019'    
  //   ],
  //   [
  //     'field_program_eligibility_date_r' => ['2020-01-01', '2020-12-31'],
  //     'field_program_year' => '2020'    
  //   ]
  // ];

  // foreach($programYears as $key => $array) {

  // }

}


function addContent() {
  \Drupal::logger('farmers_dadt')->notice('Disaster Assistance Tool content is updating...');

  function addNoResultsCards($load, $cards) {
    $resources = Paragraph::load($load->id());
    foreach($cards as $row) {
      $card = Paragraph::create([
        'type' => 'card',
        'field_card_header' => $row['field_card_header'],
        'field_card_content' => $row['field_card_content']
      ]);

      $card->save();
      $resources->field_cards_multiple->appendItem($card);
    }
  }

  function createProgram($node, $data) {
    foreach($data['field_crops'] as $id) {    
      $fieldCropsData['target_id'] = $id;
      $fieldCrops[] = $fieldCropsData;
    }

    foreach($data['field_disasters'] as $id) {
      $fieldDisastersData['target_id'] = $id;
      $fieldDisasters[] = $fieldDisastersData;
    }

    // foreach($data['field_program_year_and_eligibili'] as $id) {
    //   $fieldYearData['id'] = $id;
    //   $fieldYear[] = $fieldYearData;
    // }    

    $program = Paragraph::create([
      'type' => 'disaster_assistance_programs',
      'field_program_name' => $data['field_program_name'],
      'field_program_abbreviation' => $data['field_program_abbreviation'],
      'field_program_description' => [
        'value' => $data['field_program_description'],
        'format' => 'formatted',
      ],
      'field_program_year_and_eligibili' => $fieldYear,
      'field_crops' => $fieldCrops,
      'field_disasters' => $fieldDisasters,
      'field_program_link' => [
        'uri' => $data['field_program_link_uri'],
        'title' => $data['field_program_link_title']
      ],
      'field_bring_the_following_items' => $data['field_bring_the_following_items']
    ]);
    $program->save();

    // Append programs to node
    $node->field_paragraphs->appendItem($program);
  }

  function load_tid_by_name($term_name, $vocab) {
    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')
    ->loadByProperties(['name' => $term_name, 'vid' => $vocab]);
    $term = reset($term);

    return !empty($term) ? $term->id() : 0;
  }

  // Setting the DADT module image path
  $module_path = drupal_get_path('module', 'farmers_dadt');
  $dadt_images = $module_path . '/images';

  // Node 1 : Creating the Disaster Tool landing page
  $node = Node::create(['type' => 'program_page']);
  
  $title1 = 'Disaster Assistance Discovery Tool';
  $node->set('title', $title1);
  $node->set('field_description_metatag', $title1);    

  $body1 = [
    'value' => '<p>Learn about USDA disaster assistance programs that might be right for you by completing five simple steps.</p><em>Note: This tool is not optimized for Internet Explorer. Use Chrome, Edge, or Safari for the best experience.</em>',
    'format' => 'full_html',
  ];
  $node->set('field_summary', $body1);

  // Node 1 : Header Image
  $node_header_image = $dadt_images . '/recover-short.jpg';
  $node_header_image_file = File::create([
    'uri' => $node_header_image,
  ]);
  $node_header_image_file->save();
  $node->field_featured_image[] = [
    'target_id' => $node_header_image_file->id(),
    'alt' => $title1,
    'title' => $title1,
  ];

  $programs = [
    // PROGRAM: ELAP
    [
      'field_program_name' => 'Emergency Assistance for Livestock, Honey Bees, and Farm-raised Fish',
      'field_program_abbreviation' => 'ELAP',
      'field_program_description' => 'The Emergency Assistance for Livestock, Honeybees, and Farm-raised Fish Program provides financial assistance to eligible producers for livestock, honeybee, and farm-raised fish losses – such as death, feed, grazing, and associated transportation costs – due to disease and certain adverse weather events or loss conditions.  This program addresses losses not covered by other USDA disaster assistance programs.',
      'field_program_year_and_eligibili' => [],
      'field_crops' => [
        load_tid_by_name('Farm-Raised Fish', 'disaster_assist_prog_crops'),
        load_tid_by_name('Feed Losses', 'disaster_assist_prog_crops'),
        load_tid_by_name('Grazing', 'disaster_assist_prog_crops'),
        load_tid_by_name('honeybees', 'disaster_assist_prog_crops'),
        load_tid_by_name('livestock', 'disaster_assist_prog_crops'),
        load_tid_by_name('Water Transportation', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
        load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Biohazard', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Extreme Heat', 'disaster_assist_prog_disasters'),      
        load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Lightning', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Plant/Disease Infestation', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tidal Surge', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/emergency-assist-for-livestock-honey-bees-fish/index',
      'field_program_link_title' => 'Read More About This Program'
    ],
    // PROGRAM: LFP
    [
      'field_program_name' => 'Livestock Forage Disaster Program',
      'field_program_abbreviation' => 'LFP',
      'field_program_description' => 'The Livestock Forage Disaster Program provides compensation to eligible livestock producers who have suffered grazing losses on native or improved pastureland with permanent vegetative cover, or land planted specifically for grazing.  The grazing losses must be due to a qualifying drought condition or fire on federally-managed land during the normal grazing period for a county.',
      'field_program_year_and_eligibili' => [],      
      'field_crops' => [
        load_tid_by_name('Livestock', 'disaster_assist_prog_crops'),
        load_tid_by_name('Grazing', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
        load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/livestock-forage/index',
      'field_program_link_title' => 'Read More About This Program',
      'field_bring_the_following_items' => [
        'A copy of your grower contract if you are a contract grower',
        'A current inventory of the physical location of livestock',
        'Documents showing evidence of:
          <ul>
              <li>Loss</li>
              <li>Ownership or lease of grazing land or pastureland</li>
              <li>A federal agency prohibited you from grazing the normal permitted livestock on the managed rangeland due to a fire</li>
          </ul>'
      ]
    ],
    // PROGRAM: LIP
    [
      'field_program_name' => 'Livestock Indemnity Program',
      'field_program_abbreviation' => 'LIP',
      'field_program_description' => 'The Livestock Forage Disaster Program provides compensation to eligible livestock producers who have suffered grazing losses on native or improved pastureland with permanent vegetative cover, or land planted specifically for grazing.  The grazing losses must be due to a qualifying drought condition or fire on federally-managed land during the normal grazing period for a county.',
      'field_crops' => [
        load_tid_by_name('livestock', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
        load_tid_by_name('Avian/Animal Attack', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Biohazard', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Extreme Heat', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hail', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Lightning', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/livestock-indemnity/index',
      'field_program_link_title' => 'Read More About This Program',
      'field_bring_the_following_items' => [
        'Adequate proof that the eligible livestock deaths occurred, such as purchase records, veterinarian records, written contracts, bank or loan documents, and/or production records',
        'You may also need to complete:
        <ul>
            <li>A notice of loss</li>
            <li>An application for payment</li>
        </ul>'
      ],
      'field_may_also_need_to_complete' => [
        'A notice of loss',
        'An application for payment'
      ] 
    ],
    // PROGRAM: NAP
    [
      'field_program_name' => 'Noninsured Crop Disaster Assistance Program',
      'field_program_abbreviation' => 'NAP',
      'field_program_description' => 'The Noninsured Crop Disaster Assistance Program helps producers manage risk by covering crop losses and crop planting that was prevented due to natural disasters. The eligible or “noninsured” crops include agricultural commodities not covered by federal crop insurance. Producers must be enrolled in the program and have purchased coverage for the eligible crop in the crop year in which the loss incurred to receive program benefits following a qualifying natural disaster.',
      'field_crops' => [
          load_tid_by_name('Aquaculture', 'disaster_assist_prog_crops'),
          load_tid_by_name('Christmas Trees', 'disaster_assist_prog_crops'),
          load_tid_by_name('Crop Grown for Food', 'disaster_assist_prog_crops'),
          load_tid_by_name('Crop grown for live-stock consumption', 'disaster_assist_prog_crops'),
          load_tid_by_name('Farm-Raised Fish', 'disaster_assist_prog_crops'),
          load_tid_by_name('floriculture', 'disaster_assist_prog_crops'),
          load_tid_by_name('grazing', 'disaster_assist_prog_crops'),
          load_tid_by_name('Industrial crops', 'disaster_assist_prog_crops'),
          load_tid_by_name('Ornamental Nursery', 'disaster_assist_prog_crops'),
          load_tid_by_name('Sea Oats and Sea Grass', 'disaster_assist_prog_crops'),
          load_tid_by_name('Turfgrass Sod', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
          load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Extreme Heat', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hail', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Insufficient Chill Hours', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Lightning', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Plant/Disease Infestation', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/noninsured-crop-disaster-assistance/index',
      'field_program_link_title' => 'Read More About This Program'
    ],
    // PROGRAM: TAP
    [
      'field_program_name' => 'Tree Assistance Program',
      'field_program_abbreviation' => 'TAP',
      'field_program_description' => 'The Tree Assistance Program helps orchardists and nursery tree growers replant or rehabilitate eligible trees, bushes, and vines damaged by natural disasters and eligible plant disease.',
      'field_crops' => [
          load_tid_by_name('bushes or vines', 'disaster_assist_prog_crops'),
          load_tid_by_name('Christmas Trees', 'disaster_assist_prog_crops'),
          load_tid_by_name('Orchard or Nursery Trees', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
        load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Extreme Heat', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hail', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Lightning', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Plant/Disease Infestation', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/tree-assistance-program/index',
      'field_program_link_title' => 'Read More About This Program'
    ],
    // PROGRAM: ECP
    [
      'field_program_name' => 'Emergency Conservation Program',
      'field_program_abbreviation' => 'ECP',
      'field_program_description' => 'The Emergency Conservation Program provides funding for farmers and ranchers to rehabilitate farmland damaged by natural disasters and to carry out emergency water conservation measures during periods of severe drought.',
      'field_crops' => [
          load_tid_by_name('Christmas tree plantations', 'disaster_assist_prog_crops'),
          load_tid_by_name('croplands', 'disaster_assist_prog_crops'),
          load_tid_by_name('Orchards and tree plantations', 'disaster_assist_prog_crops'),
          load_tid_by_name('field wind breaks', 'disaster_assist_prog_crops'),
          load_tid_by_name('nurseries', 'disaster_assist_prog_crops'),
          load_tid_by_name('pastureland', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
          load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Ice Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Land/Mudslide', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tidal Surge', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/conservation-programs/emergency-conservation/index',
      'field_program_link_title' => 'Read More About This Program'
    ],
    // PROGRAM: EFRP
    [
      'field_program_name' => 'Emergency Forest Restoration Program',
      'field_program_abbreviation' => 'EFRP',
      'field_program_description' => 'The Emergency Forest Restoration Program offers financial payments to eligible private forest landowners who restore forests damaged by natural disasters or insect and disease infestation.',
      'field_crops' => [
          load_tid_by_name('Non-Industrial Private Forestland', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
          load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Ice Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Land/Mudslide', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tidal Surge', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/emergency-forest-restoration/index',
      'field_program_link_title' => 'Read More About This Program'
    ],
    // PROGRAM: EQIP
    [
      'field_program_name' => 'Environmental Quality Incentives Program',
      'field_program_abbreviation' => 'EQIP',
      'field_program_description' => 'The Environmental Quality Incentives Program provides agricultural producers with financial resources and one-on-one help to plan and implement improvements on the land.  While not established specifically for disaster response, the program can assist with immediate recovery needs and provide long-term support to help conserve water resources, reduce wind erosion on drought-impacted fields, improve livestock access to water, recover from natural disasters like wildfires, and more.',
      'field_crops' => [
          load_tid_by_name('conservation structures', 'disaster_assist_prog_crops'),
          load_tid_by_name('Natural Resource', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
          load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Drought', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Earthquake', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hail', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Lightning', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tidal Surge', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.nrcs.usda.gov/wps/portal/nrcs/main/national/programs/financial/eqip/',
      'field_program_link_title' => 'Read More About This Program',
      'field_bring_the_following_items' => [
        'A map of the area to be enrolled',
      ],
      'field_bring_the_following_items' => [
        '<a href="https://www.nrcs.usda.gov/wps/PA_NRCSConsumption/download?cid=nrcseprd1342640&ext=pdf" target="_blank">NRCS-CPA-1200</a>
            – Conservation Program Application',
        'You may also need to complete the following form:
          <ul>
              <li>
                  <a href="https://www.nrcs.usda.gov/wps/PA_NRCSConsumption/download?cid=nrcseprd1342640&ext=pdf" target="_blank">NRCS-CPA-1200</a>
                  – Conservation Program Application</li>
          </ul>'
      ],
      'field_may_also_need_to_complete' => [ 
        'For the Environmental Quality Incentives Program: <a href="https://www.nrcs.usda.gov/wps/PA_NRCSConsumption/download?cid=nrcseprd1342640&ext=pdf" target="_blank">NRCS-CPA-1200</a>
            – Conservation Program Application'
      ]
    ],
    // PROGRAM: EWP
    [
      'field_program_name' => 'Emergency Watershed Protection Program',
      'field_program_abbreviation' => 'EWP',
      'field_program_description' => 'The Emergency Watershed Protection Program – with recovery and floodplain easement options – provides personalized advice and financial assistance to relieve imminent threats to life and property caused by floods, fires, windstorms, and other natural disasters that impair a watershed.',
      'field_crops' => [
          load_tid_by_name('Natural Resource', 'disaster_assist_prog_crops'),
          load_tid_by_name('watershed features or structures', 'disaster_assist_prog_crops')
      ],
      'field_disasters' => [
          load_tid_by_name('Blizzard', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excess Moisture', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Excessive Wind', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tidal Surge', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters'),
          load_tid_by_name('Winter Storm', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.nrcs.usda.gov/wps/portal/nrcs/main/national/programs/landscape/ewpp/',
      'field_program_link_title' => 'Read More About This Program',
      'field_bring_the_following_items' => [
        'For floodplain easements: records or photographs of previous flood events',
        'For floodplain easements: copy of deed (land ownership required)',
        'For recovery projects: applications cannot be accepted from individuals, but local staff can provide help and get you in touch with local project sponsors',
      ]
    ],
    // PROGRAM: WHIP
    [
      'field_program_name' => '2017 Wildfires and Hurricanes Indemnity Program',
      'field_program_abbreviation' => 'WHIP',
      'field_program_description' => 'The 2017 Wildfires and Hurricanes Indemnity Program (2017 WHIP) provides producers with disaster payments to offset losses – particularly losses of crops, trees, bushes, and vines – from specific natural disasters that occurred during 2017 and 2018.',
      'field_crops' => [
        load_tid_by_name('Bushes or Vines', 'disaster_assist_prog_crops'),
        load_tid_by_name('Christmas Trees', 'disaster_assist_prog_crops'),
        load_tid_by_name('Crop Grown for Food', 'disaster_assist_prog_crops'),
        load_tid_by_name('Crop grown for live-stock consumption', 'disaster_assist_prog_crops'),
        load_tid_by_name('Farm-raised fish', 'disaster_assist_prog_crops'),
        load_tid_by_name('Industrial Crops', 'disaster_assist_prog_crops'),
        load_tid_by_name('Orchard or Nursery Trees', 'disaster_assist_prog_crops'),
        load_tid_by_name('Ornamental Nursery', 'disaster_assist_prog_crops'),
        load_tid_by_name('Sea Oats and Sea Grass', 'disaster_assist_prog_crops'),
        load_tid_by_name('Turfgrass Sod', 'disaster_assist_prog_crops')
        ],
      'field_disasters' => [
        load_tid_by_name('Freeze/Frost', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tropical Storm', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters')
      ],
      'field_program_link_uri' => 'https://www.farmers.gov/recover/2017-whip',
      'field_program_link_title' => 'Read More About This Program',
      'field_bring_the_following_items' => [
        'Verifiable and reliable production records by crop, type, practice, intended use, and acres if not already on file',
        'You may also need to complete:
          <ul>
              <li><a href="https://www.reginfo.gov/public/do/DownloadDocument?objectID=83821101">FSA-892</a> – Request an exception to the 2017 Wildfires and Hurricanes Indemnity Program payment limitation of $125,000, if applicable.</li>
          </ul>'
      ]
    ],
    // WHIP+
    [
      'field_program_name' => 'Wildfire and Hurricane Indemnity Program Plus',
      'field_program_abbreviation' => 'WHIPPLUS',
      'field_program_description' => 'The Wildfires and Hurricanes Indemnity Program Plus (WHIP+) provides producers with disaster payments to offset losses – particularly losses of crops, trees, bushes, and vines – from specific natural disasters that occurred during 2018 and 2019. Producers must be located in a qualifying county to be eligible for WHIP+. Producers not in these counties may also be eligible, but must supply documentation establishing that crops were directly impacted by a qualifying disaster event. A full list of all eligible counties is <a href="/recover/whip-plus/eligible-counties" target="_blank">available here</a>.',
      'field_crops' => [
        load_tid_by_name('Bushes or Vines', 'disaster_assist_prog_crops'),
        load_tid_by_name('Christmas Trees', 'disaster_assist_prog_crops'),
        load_tid_by_name('Crop Grown for Food', 'disaster_assist_prog_crops'),
        load_tid_by_name('Crop grown for live-stock consumption', 'disaster_assist_prog_crops'),
        load_tid_by_name('Industrial Crops', 'disaster_assist_prog_crops'),
        load_tid_by_name('Orchard or Nursery Trees', 'disaster_assist_prog_crops'),
        load_tid_by_name('Ornamental Nursery', 'disaster_assist_prog_crops'),
        load_tid_by_name('Sea Oats and Sea Grass', 'disaster_assist_prog_crops'),
        load_tid_by_name('Turfgrass Sod', 'disaster_assist_prog_crops')
        ],
      'field_disasters' => [
        load_tid_by_name('Flood', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Hurricane', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Tornado', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Volcanic Eruption', 'disaster_assist_prog_disasters'),
        load_tid_by_name('Wildfire', 'disaster_assist_prog_disasters')  
      ],
      'field_program_link_uri' => 'https://www.farmers.gov/recover/whip-plus',
      'field_program_link_title' => 'Read More About This Program',
      'field_may_also_need_to_complete' => [
        'FSA-892 – Request an exception to the 2017 Wildfires and Hurricanes Indemnity Program payment limitation of $125,000, if applicable.'
      ]
    ]  
  ];

  foreach($programs as $array) createProgram($node, $array);

  // Set to draft
  $node->set('uid', 1);
  $node->status = 1;
  $node->enforceIsNew();
  $node->save();

  $nid = $node->id();  

  $node_load = Node::load($nid);

  $elapYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2016-10-01',
      'end_value' => '2017-09-30',
    ]
  ]);
  $elapYears1->save();

  $elapYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2017-10-01',
      'end_value' => '2018-09-30',
    ]
  ]);
  $elapYears2->save();

  $elapYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2018-10-01',
      'end_value' => '2019-09-30',
    ]
  ]);
  $elapYears3->save();

  $elapYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2020-01-01',
      'end_value' => '2020-12-31',
    ]
  ]);
  $elapYears4->save();

  $lfpYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $lfpYears1->save();

  $lfpYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $lfpYears2->save();

  $lfpYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $lfpYears3->save();
  
  $lfpYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2020-01-01',
      'end_value' => '2020-12-31',
    ]
  ]);
  $lfpYears4->save();  

  $lipYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $lipYears1->save();

  $lipYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $lipYears2->save();

  $lipYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $lipYears3->save();
  
  $lipYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2020-01-01',
      'end_value' => '2020-12-31',
    ]
  ]);
  $lipYears4->save();  

  $napYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $napYears1->save();

  $napYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $napYears2->save();

  $napYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $napYears3->save();
  
  $napYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2020-01-01',
      'end_value' => '2020-12-31',
    ]
  ]);
  $napYears4->save(); 

  $tapYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $tapYears1->save();

  $tapYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $tapYears2->save();

  $tapYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $tapYears3->save();
  
  $tapYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2020-01-01',
      'end_value' => '2020-12-31',
    ]
  ]);
  $tapYears4->save();

  $ecpYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $ecpYears1->save();

  $ecpYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $ecpYears2->save();

  $ecpYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-09-30',
    ]
  ]);
  $ecpYears3->save();
  
  $ecpYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2019-10-01',
      'end_value' => '2020-09-30',
    ]
  ]);
  $ecpYears4->save();

  $efrpYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $efrpYears1->save();

  $efrpYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $efrpYears2->save();

  $efrpYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-09-30',
    ]
  ]);
  $efrpYears3->save();
  
  $efrpYears4 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2019-10-01',
      'end_value' => '2020-09-30',
    ]
  ]);
  $efrpYears4->save();

  $eqipYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-10-01',
      'end_value' => '2018-09-30',
    ]
  ]);
  $eqipYears1->save();

  $ewpYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-10-01',
      'end_value' => '2018-09-30',
    ]
  ]);
  $ewpYears1->save();  

  $whipYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2017',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $whipYears1->save();
 
  $whipYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2017-01-01',
      'end_value' => '2017-12-31',
    ]
  ]);
  $whipYears2->save();

  $whipplusYears1 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2018',
    'field_program_eligibility_date_r' => [
      'value' => '2018-01-01',
      'end_value' => '2018-12-31',
    ]
  ]);
  $whipplusYears1->save();

  $whipplusYears2 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2019',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $whipplusYears2->save(); 
  
  $whipplusYears3 = Paragraph::create([
    'type' => 'program_year_and_eligibility_ran',
    'field_program_year' => '2020',
    'field_program_eligibility_date_r' => [
      'value' => '2019-01-01',
      'end_value' => '2019-12-31',
    ]
  ]);
  $whipplusYears3->save();   

  // Load all of the program paragraphs
  $program_list = $node_load->field_paragraphs->referencedEntities();

  $elap = $program_list[0];
  $lfp = $program_list[1];
  $lip = $program_list[2];
  $nap = $program_list[3];
  $tap = $program_list[4];
  $ecp = $program_list[5];
  $efrp = $program_list[6];
  $eqip = $program_list[7];
  $ewp = $program_list[8];
  $whip = $program_list[9];
  $whipplus = $program_list[10];

  $elap_load = Paragraph::load($elap->id());
  $elap_load->field_program_year_and_eligibili->appendItem($elapYears1);
  $elap_load->field_program_year_and_eligibili->appendItem($elapYears2);
  $elap_load->field_program_year_and_eligibili->appendItem($elapYears3);
  $elap_load->field_program_year_and_eligibili->appendItem($elapYears4);
  $elap_load->save();

  $lfp_load = Paragraph::load($lfp->id());
  $lfp_load->field_program_year_and_eligibili->appendItem($lfpYears1);
  $lfp_load->field_program_year_and_eligibili->appendItem($lfpYears2);
  $lfp_load->field_program_year_and_eligibili->appendItem($lfpYears3);
  $lfp_load->field_program_year_and_eligibili->appendItem($lfpYears4);
  $lfp_load->save();

  $lip_load = Paragraph::load($lip->id());
  $lip_load->field_program_year_and_eligibili->appendItem($lipYears1);
  $lip_load->field_program_year_and_eligibili->appendItem($lipYears2);
  $lip_load->field_program_year_and_eligibili->appendItem($lipYears3);
  $lip_load->field_program_year_and_eligibili->appendItem($lipYears4);
  $lip_load->save();

  $nap_load = Paragraph::load($nap->id());
  $nap_load->field_program_year_and_eligibili->appendItem($napYears1);
  $nap_load->field_program_year_and_eligibili->appendItem($napYears2);
  $nap_load->field_program_year_and_eligibili->appendItem($napYears3);
  $nap_load->field_program_year_and_eligibili->appendItem($napYears4);
  $nap_load->save();

  $tap_load = Paragraph::load($tap->id());
  $tap_load->field_program_year_and_eligibili->appendItem($tapYears1);
  $tap_load->field_program_year_and_eligibili->appendItem($tapYears2);
  $tap_load->field_program_year_and_eligibili->appendItem($tapYears3);
  $tap_load->field_program_year_and_eligibili->appendItem($tapYears4);
  $tap_load->save();  

  $ecp_load = Paragraph::load($ecp->id());
  $ecp_load->field_program_year_and_eligibili->appendItem($ecpYears1);
  $ecp_load->field_program_year_and_eligibili->appendItem($ecpYears2);
  $ecp_load->field_program_year_and_eligibili->appendItem($ecpYears3);
  $ecp_load->field_program_year_and_eligibili->appendItem($ecpYears4);
  $ecp_load->save();  
  
  $efrp_load = Paragraph::load($efrp->id());
  $efrp_load->field_program_year_and_eligibili->appendItem($efrpYears1);
  $efrp_load->field_program_year_and_eligibili->appendItem($efrpYears2);
  $efrp_load->field_program_year_and_eligibili->appendItem($efrpYears3);
  $efrp_load->field_program_year_and_eligibili->appendItem($efrpYears4);
  $efrp_load->save();    

  $eqip_load = Paragraph::load($eqip->id());
  $eqip_load->field_program_year_and_eligibili->appendItem($eqipYears1);
  $eqip_load->save();

  $ewp_load = Paragraph::load($ewp->id());
  $ewp_load->field_program_year_and_eligibili->appendItem($ewpYears1);
  $ewp_load->save(); 

  $whip_load = Paragraph::load($whip->id());
  $whip_load->field_program_year_and_eligibili->appendItem($whipYears1);
  $whip_load->save();

  $whipplus_load = Paragraph::load($whipplus->id());
  $whipplus_load->field_program_year_and_eligibili->appendItem($whipplusYears1);
  $whipplus_load->field_program_year_and_eligibili->appendItem($whipplusYears2);
  $whipplus_load->field_program_year_and_eligibili->appendItem($whipplusYears3);
  $whipplus_load->save();

  //Add Prepare for your Service Center Visit section
  $prepare_section = Paragraph::create([
    'type' => 'prepare_for_your_visit_to_the_se',
    'field_bring_the_following_items' => [
      'Proof of identity such as driver’s license or Social Security number/card',
      'Copy of recorded deed, survey plat, rental, or lease agreement of the land (You do not have to own property to participate in USDA programs.)',
      'Articles of incorporation, estate, or trust documents for entities'
    ]
  ]);
  $prepare_section->save();

  function uploadPdfAndSave($row, $prepare_section_load) {
    $module_path = drupal_get_path('module', 'farmers_dadt');
    $dadt_pdfs = $module_path . '/pdfs/';
    
    $file = $dadt_pdfs . $row['filename'];
    $file_upload = File::create([
      'uri' => $file,
    ]);
    $file_upload->save();
    unset($row['filename']);

    $row['field_form_scl_upload'] = [
      'target_id' => $file_upload->id(),
      'description' => $row['field_form_name'] ? $row['field_form_name'] : ''
    ];
    
    $form = Paragraph::create($row);
    $form->save();

    $prepare_section_load->field_forms_to_bring->appendItem($form);
  }

  $pdfs = [
    [
      'type' => 'forms_to_bring',
      'filename' => 'Form-CCC0941-Adjusted-Gross-Income.pdf',
      'field_form_name' => 'CCC-941',
      'field_form_description' => 'Reports your average adjusted gross income for programs where income restrictions apply
      (Note: This form does not apply to the 2017 Wildfires and Hurricanes Indemnity Program)'
    ],
    [
      'type' => 'forms_to_bring',
      'filename' => 'Form-AD1026-Highly-Erodible-Land.pdf',
      'field_form_name' => 'AD-1026',
      'field_form_description' => 'Ensures a conservation plan is in place before lands with highly erodible soils are farmed, identified wetland areas are protected, and conservation compliance provisions are met'
    ],
    [
      'type' => 'forms_to_bring',
      'filename' => 'Form-CCC0901-Membership.pdf',
      'field_form_name' => 'CCC-901',
      'field_form_description' => 'Identifies members of a farm or ranch that is a legal entity'
    ]
  ];

  $prepare_section_load = Paragraph::load($prepare_section->id());
  foreach($pdfs as $row) uploadPdfAndSave($row, $prepare_section_load);
  $prepare_section_load->save();

  $disclaimer = Paragraph::create([
    'type' => 'content',
    'field_content' => [
      'value' => '<strong>Disclaimer:</strong> The Disaster Assistance Discovery Tool uses your answers to five questions to identify USDA disaster assistance programs that might meet your business needs. Local program availability and individual eligibility will be determined by the USDA servicing office. For more information about disaster programs in your area, contact your local USDA service center.',
      'format' => 'formatted',
    ]
  ]);
  $disclaimer->save(); 

  // NO RESULTS
  $noResults = Paragraph::create([
    'type' => 'no_results',
    'field_no_results_description' => [
      'value' => '<p>Based on your answers, we were unable to determine specific disaster assistance programs for you. You will find other helpful resources listed below.</p>',
      'format' => 'full_html',
    ]
  ]);
  $noResults->save();
  
  // NO RESULTS CARDS
  $noResultsUSDAResources = Paragraph::create([
    'type' => 'card_multiples',
    'field_cards_multiple_header' => 'USDA Resources'
  ]);
  $noResultsUSDAResources->save();

  addNoResultsCards($noResultsUSDAResources, 
  [
    [ 
      'field_card_header' => 'Crop Insurance',
      'field_card_content' => '<p>USDA provides crop insurance for producers through the Federal Crop Insurance Corporation. Visit USDA <a href="https://www.rma.usda.gov/">Risk Management Agency’s website</a> for crop insurance information and use the <a href="https://prodwebnlb.rma.usda.gov/apps/AgentLocator/#/">Agent Locator</a> to find a crop insurance agent near you.</p>' 
    ]
  ]);

  $noResultsOtherResources = Paragraph::create([
    'type' => 'card_multiples',
    'field_cards_multiple_header' => 'Other Government Resources'
  ]);
  $noResultsOtherResources->save();

  addNoResultsCards($noResultsOtherResources, 
  [
    [ 
      'field_card_header' => 'Federal Emergency Management Agency or FEMA',
      'field_card_content' => '<p>Download the
                  <a href="https://www.fema.gov/mobile-app">FEMA mobile app</a>
                  for local weather, maps of disaster resources, and other disaster relief options – including how you can help.</p>' 
    ],
    [ 
      'field_card_header' => 'Disasterassistance.gov',
      'field_card_content' => '<p>Find news feeds for wildfire, drought, hurricanes and more at
                            <a href="https://www.disasterassistance.gov/" target="_blank" class="ext" data-extlink="" rel="noopener" id="anch_92">disasterassistance.gov</a></p>' 
    ],
    [ 
      'field_card_header' => 'Small Business Administration or SBA',
      'field_card_content' => '<p>Visit the
                            <a href="https://disasterloan.sba.gov/ela/Declarations/Index">Small Business Administration’s website</a>
                            to search current Presidential emergency disaster declarations and USDA Secretarial disaster designations by state and county.</p>' 
    ]
  ]);

  $noResultsResource = Paragraph::load($noResults->id());
  $noResultsResource->field_no_results_cards->appendItem($noResultsUSDAResources);
  $noResultsResource->field_no_results_cards->appendItem($noResultsOtherResources);

  // NO SELECTED
  $noSelected = Paragraph::create([
    'type' => 'no_selected_step_1',
    'field_no_selected_description' => [
      'value' => '<p>Based on your answers, you did not suffer an agricultural loss or damage as a result of a natural disaster. If this is incorrect, please review your answers above and then select the Next button.</p>
        <p>To prepare for disaster before it strikes, you might be interested in the following resources.</p>',
      'format' => 'full_html',
    ]
  ]);
  $noSelected->save();

  // NO SELECTED CARDS
  $noSelectedUSDAResources = Paragraph::create([
    'type' => 'card_multiples',
    'field_cards_multiple_header' => 'USDA Resources'
  ]);
  $noSelectedUSDAResources->save();

  addNoResultsCards($noSelectedUSDAResources, 
  [
    [ 
      'field_card_header' => 'Crop Insurance',
      'field_card_content' => '<p>USDA provides crop insurance for producers through the Federal Crop Insurance Corporation. Visit USDA <a href="https://www.rma.usda.gov/">Risk Management Agency’s website</a> for crop insurance information and use the <a href="https://prodwebnlb.rma.usda.gov/apps/AgentLocator/#/">Agent Locator</a> to find a crop insurance agent near you.</p>' 
    ],
    [ 
      'field_card_header' => 'Noninsured Crop Assistance Disaster Program',
      'field_card_content' => '<p>The <a href="https://www.fsa.usda.gov/programs-and-services/disaster-assistance-program/noninsured-crop-disaster-assistance/index">Noninsured Crop Disaster Assistance Program</a> helps producers to manage risk through coverage for both crop losses and crop planting that was prevented due to natural disasters. The eligible or “noninsured” crops include agricultural commodities not covered by federal crop insurance.</p>' 
    ]
  ]);

  $noSelectedOtherResources = Paragraph::create([
    'type' => 'card_multiples',
    'field_cards_multiple_header' => 'Other Government Resources'
  ]);
  $noSelectedOtherResources->save();

  addNoResultsCards($noSelectedOtherResources, 
  [
    [ 
      'field_card_header' => 'Federal Emergency Management Agency or FEMA',
      'field_card_content' => '<p>Download the <a href="https://www.fema.gov/mobile-app">FEMA mobile app</a> for local weather, maps of disaster resources, and other disaster relief options – including how you can help.</p>' 
    ],
    [ 
      'field_card_header' => 'Ready.gov',
      'field_card_content' => '<p>Visit <a href="https://www.ready.gov/">ready.gov/</a> to prepare for disaster before it happens.</p>' 
    ]
  ]);

  // add no result cards to no results paragraph type
  $noSelectedResource = Paragraph::load($noSelected->id());
  $noSelectedResource->field_no_selected_cards->appendItem($noSelectedUSDAResources);
  $noSelectedResource->field_no_selected_cards->appendItem($noSelectedOtherResources);


  $nid = $node->id();  

  $node_load = Node::load($nid);

  $node_load->field_paragraphs->appendItem($prepare_section_load);
  $node_load->field_paragraphs->appendItem($disclaimer); 
  $node_load->field_paragraphs->appendItem($noSelected); 
  $node_load->field_paragraphs->appendItem($noResults); 

  $node_load->save();
}