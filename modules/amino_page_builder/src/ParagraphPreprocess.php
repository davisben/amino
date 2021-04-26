<?php

namespace Drupal\amino_page_builder;

/**
 * Paragraph preprocessing.
 */
class ParagraphPreprocess {

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

}
