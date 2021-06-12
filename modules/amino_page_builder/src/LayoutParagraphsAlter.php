<?php

namespace Drupal\amino_page_builder;

/**
 * Layout paragraphs widget alter.
 */
class LayoutParagraphsAlter {

  /**
   * Alter the layout paragraphs widget.
   *
   * @param array $element
   *   The widget to alter.
   */
  public function alterWidget(array &$element) {
    $element['#type'] = 'container';
  }

}
