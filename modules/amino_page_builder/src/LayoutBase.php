<?php

namespace Drupal\amino_page_builder;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;

/**
 * Base class for defining layouts.
 */
class LayoutBase extends LayoutDefault implements PluginFormInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'full_width' => 0,
      'additional_classes' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();

    $form['background'] = [
      '#type' => 'select',
      '#title' => $this->t('Background'),
      '#options' => [
        'none' => $this->t('None'),
        'color' => $this->t('Color'),
        'image' => $this->t('Image'),
      ],
      '#default_value' => $configuration['background'] ?? NULL,
    ];

    $form['background_color'] = [
      '#type' => 'color',
      '#title' => $this->t('Background Color'),
      '#default_value' => $configuration['background_color'] ?? NULL,
      '#states' => [
        'visible' => [
          ':input[name="field_content[entity_form][0][layout_plugin_form][background]"]' => ['value' => 'color'],
        ],
      ],
    ];

    $form['background_image'] = [
      '#type' => 'media_library',
      '#title' => $this->t('Background Image'),
      '#allowed_bundles' => ['image'],
      '#default_value' => $configuration['background_image'] ?? NULL,
      '#states' => [
        'visible' => [
          ':input[name="field_content[entity_form][0][layout_plugin_form][background]"]' => ['value' => 'image'],
        ],
      ],
    ];

    $form['full_width'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Full width'),
      '#default_value' => $configuration['full_width'] ?? NULL,
    ];

    $form['additional_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Additional classes'),
      '#default_value' => $configuration['additional_classes'] ?? NULL,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $config_keys = [
      'full_width',
      'additional_classes',
      'background',
      'background_color',
      'background_image',
    ];

    foreach ($config_keys as $key) {
      $value = $form_state->getValue($key);
      if ($value) {
        $this->configuration[$key] = $value;
      }
    }
  }

}
