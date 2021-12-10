<?php

namespace Drupal\amino_page_builder;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Layout preprocessing.
 */
class LayoutPreprocess implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->setEntityTypeManager($container->get('entity_type.manager'));

    return $instance;
  }

  /**
   * Set the entity type manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   *
   * @return $this
   */
  protected function setEntityTypeManager(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    return $this;
  }

  /**
   * Layout preprocess.
   *
   * @param array $variables
   *   Theme variables to preprocess.
   */
  public function preprocessLayout(array &$variables) {
    if ($variables['layout']->getProvider() == 'amino_page_builder') {
      if (isset($variables['settings']['regions'])) {
        $regions = $variables['layout']->getRegions();

        foreach (array_keys($regions) as $region) {
          $key = 'region_width_' . $region;
          if (isset($variables['settings']['regions'][$key])) {
            $width = $variables['settings']['regions'][$key];
            $variables['region_attributes'][$region]->setAttribute('style', "flex-basis: $width%;");
          }
        }
      }

      $background = $variables['settings']['background'];

      if ($background == 'color') {
        $color = $variables['settings']['background_color'];

        $variables['attributes']['style'][] = "background-color: $color;";
      }
      elseif ($background == 'image') {
        $media_storage = $this->entityTypeManager->getStorage('media');
        $file_storage = $this->entityTypeManager->getStorage('file');

        /** @var \Drupal\media\MediaInterface $media */
        $media = $media_storage->load($variables['settings']['background_image']);
        $fid = $media->getSource()->getSourceFieldValue($media);

        /** @var \Drupal\file\FileInterface $file */
        $file = $file_storage->load($fid);
        $image_url = $file->createFileUrl();

        $variables['attributes']['style'][] = "background-image: url('$image_url');";
      }
    }
  }

}
