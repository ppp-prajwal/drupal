<?php

namespace Drupal\events_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\events_list\Controller\EventsController;

/**
* Provides a block with a simple text.
*
* @Block(
*   id = "list_published_event",
*   admin_label = @Translation("List Published Events Block"),
* )
*/
class ListPublishedEventsBlock extends BlockBase {

 /**
  * {@inheritdoc}
  */
  public function build() {
    $controller_variable = new EventsController;
    $rendering_in_block = $controller_variable->listPublishedEvents();
    return $rendering_in_block;
  }
}
