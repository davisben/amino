<?php

namespace Drupal\amino_page_builder;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Paragraph preprocessing.
 */
class ParagraphPreprocess implements ContainerInjectionInterface {

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
   * Preprocess Amino Heading paragraphs.
   *
   * @param array $variables
   *   Theme variables to preprocess.
   */
  public function preprocessAminoHeading(array &$variables) {
    /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    $paragraph = $variables['paragraph'];

    $variables['content']['amino_heading_text'] = [
      '#type' => 'html_tag',
      '#tag' => $paragraph->amino_heading_tag->value,
      '#value' => $paragraph->amino_heading_text->value,
    ];
  }

  /**
   * Preprocess Amino Image paragraphs.
   *
   * @param array $variables
   *   Theme variables to preprocess.
   */
  public function preprocessAminoImage(array &$variables) {
    /** @var \Drupal\paragraphs\ParagraphInterface $paragraph */
    $paragraph = $variables['paragraph'];

    $media_storage = $this->entityTypeManager->getStorage('media');
    /** @var \Drupal\media\MediaInterface $image */
    $image = $media_storage->load($paragraph->amino_image->target_id);

    $file_storage = $this->entityTypeManager->getStorage('file');
    /** @var \Drupal\file\FileInterface $file */
    $file = $file_storage->load($image->field_media_image->target_id);

    $variables['content']['amino_image'] = [
      '#theme' => 'image_style',
      '#style_name' => $paragraph->amino_image_style->target_id,
      '#uri' => $file->getFileUri(),
    ];
  }

}
