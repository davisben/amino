<?php

namespace Drupal\amino_page_builder;

/**
 * Layout Paragraphs widget alter.
 */
class LayoutParagraphsAlter {

  /**
   * Alter the Layout Paragraphs widget.
   *
   * @param array $element
   *   The widget to alter.
   */
  public function alterWidget(array &$element) {
    $element['#type'] = 'container';
  }

}
