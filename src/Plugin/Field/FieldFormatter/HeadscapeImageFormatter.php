<?php

namespace Drupal\headscape_image_formatter\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'headscape_image_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "headscape_image_formatter",
 *   label = @Translation("Headscape image formatter"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class HeadscapeImageFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
      'headscape' => 'headscape',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
      'headscape' => [
        '#title' => $this->t('Image class'),
        '#type' => 'textfield',
        '#default_value' => $this->getSetting('headscape'),
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    // Get class name.
    $class_name = $this->getSetting('headscape');

    if (isset($class_name)) {
      $summary[] = t('Image class: @class',
        ['@class' => $class_name]);
    }
    else {
      $summary[] = t('default-img-class');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    // Get class name.
    $image_class = $this->getSetting('headscape');

    foreach ($items as $delta => $item) {
      if ($item->entity) {
        $image_url = $item->entity->url();
        $data = [$image_class, $image_url];
        // Format field using the headscape_image_formatter theme function.
        $elements[$delta] = [
          '#theme' => 'headscape_image_formatter',
          '#items' => $data,
        ];
      }

    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
