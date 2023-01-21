<?php
/**
 * @file
 * Contains \Drupal\events_list\Controller
 */
namespace Drupal\events_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

class EventsController extends ControllerBase {
  public function listPublishedEvents() {

    $nids = \Drupal::entityQuery('node')->accessCheck(FALSE)->condition('status', 1)->condition('type','event')->execute();
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

    foreach($nodes as $node) {
      $id = $node->id();
      $fid = $node->field_image->target_id;
      $file = File::load($fid);
      $url = $file->getFileUri();
      $image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($url);
      $image_alt = $node->field_image->alt;
      $raw_date = $node->field_date->value;
      $date = date("F d, Y", strtotime($raw_date));
      $options = ['absolute' => TRUE];
      $node_url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $id], $options);
      $node_url = $node_url->toString();
      $rating = $node->field_rating->value;
      if( $rating == NULL) {
        $rating = 0;
      }

      $data[] = [
      'title'=>$node->title->value,
      'body'=>$node->body->value,
      'date'=>$date,
      'place'=>$node->field_place->value,
      'image_url'=>$image_url,
      'image_alt'=>$image_alt,
      'node_url'=>$node_url,
      'rating'=>$rating,
      ];
    }

    $build[] = [
      '#theme'=>'listevents',
      '#items'=>$data,
      '#cache'=>[
        'max-age'=>0
      ],
      '#attached'=>[
        'library'=>[
          'events_list/events_list.list_published_events'
        ]
      ]
    ];

    return [
      $build,
    ];
  }
}
