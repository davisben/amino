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
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $regions = $this->getPluginDefinition()->getRegions();
    $this->regions = array_keys($regions);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'heading' => '',
      'full_width' => 0,
      'additional_classes' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $configuration = $this->getConfiguration();

    if (count($this->regions) > 1) {
      $form['#attached']['library'][] = 'amino_page_builder/layout.settings';

      $form['regions'] = [
        '#type' => 'details',
        '#title' => $this->t('Column widths'),
        '#attributes' => [
          'class' => [
            'region-width-wrapper',
          ],
        ],
      ];

      $form['regions']['slider'] = [
        '#type' => 'markup',
        '#markup' => '<div id="region-slider"></div>',
      ];

      foreach ($this->regions as $region) {
        $key = 'region_width_' . $region;
        $form['regions'][$key] = [
          '#type' => 'textfield',
          '#title' => ucfirst($region),
          '#size' => 5,
          '#default_value' => $configuration['regions'][$key] ?? NULL,
        ];
      }

      $form['regions']['description'] = [
        '#type' => 'markup',
        '#markup' => '<p class="region-width-description">' . $this->t('Column widths, in percent.') . '</p>',
        '#attributes' => [
          'class' => [
            'region-width-description',
          ],
        ],
      ];
    }

    $form['heading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Heading'),
      '#default_value' => $configuration['heading'] ?? NULL,
    ];

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
          ':input[name="layout_paragraphs[config][background]"]' => ['value' => 'color'],
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
          ':input[name="layout_paragraphs[config][background]"]' => ['value' => 'image'],
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
      'heading',
      'background',
      'background_color',
      'background_image',
      'full_width',
      'additional_classes',
    ];

    foreach ($this->regions as $region) {
      $config_keys[] = 'region_width_' . $region;
    }

    foreach ($config_keys as $key) {
      $value = $form_state->getValue($key);
      if ($value) {
        $this->configuration[$key] = $value;
      }
    }
  }

}
