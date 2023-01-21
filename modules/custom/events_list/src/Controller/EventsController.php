<?php
/**
 * @file
 * Contains \Drupal\events_list\Controller
 */
namespace Drupal\events_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An EventsController.
 */
class EventsController extends ControllerBase {
  /**
   * Instance of Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * File url generator object.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
    * Constructs a EventsController object.
    *
    * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
    *   The Entity Type Manager.
    * @param \Drupal\Core\File\FileUrlGeneratorInterface $fileUrlGenerator
    *   File url generator object.
    */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FileUrlGeneratorInterface $fileUrlGenerator) {
    $this->entityTypeManager = $entity_type_manager;
    $this->fileUrlGenerator = $fileUrlGenerator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('file_url_generator'),
    );
  }

  /**
   * An array of the published events with theme.
   *
   * @return array
   *   An array with the published events
   */
  public function listPublishedEvents() {
    $nids = $this->entityTypeManager->getStorage('node')->getQuery()->accessCheck(FALSE)->condition('status', 1)->condition('type','event')->execute();
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);

    foreach($nodes as $node) {
      $id = $node->id();
      $fid = $node->field_image->target_id;
      $file = $this->entityTypeManager->getStorage('file')->load($fid);
      $url = $file->getFileUri();
      $image_url = $this->fileUrlGenerator->generateAbsoluteString($url);
      $image_alt = $node->field_image->alt;
      $raw_date = $node->field_date->value;
      $date = date("F d, Y", strtotime($raw_date));
      $options = ['absolute' => TRUE];
      $node_url = Url::fromRoute('entity.node.canonical', ['node' => $id], $options);
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
      '#theme'=>'list_published_events',
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
