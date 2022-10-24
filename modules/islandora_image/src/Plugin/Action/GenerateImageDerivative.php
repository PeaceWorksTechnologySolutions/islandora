<?php

namespace Drupal\islandora_image\Plugin\Action;

use Drupal\Core\Form\FormStateInterface;
use Drupal\islandora\Plugin\Action\AbstractGenerateDerivative;

/**
 * Emits a Node event.
 *
 * @Action(
 *   id = "generate_image_derivative",
 *   label = @Translation("Generate an image derivative"),
 *   type = "node"
 * )
 */
class GenerateImageDerivative extends AbstractGenerateDerivative {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config['mimetype'] = 'image/jpeg';
    $config['path'] = '[date:custom:Y]-[date:custom:m]/[node:nid].jpg';
    $config['destination_media_type'] = 'image';  
    $config['inputargs'] = '';
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['mimetype']['#description'] = $this->t('Mimetype to convert to (e.g. image/jpeg, image/png, etc...)');

    // adjust args title and description for better clarity
    $form['args']['#title'] = $this->t('Additional output arguments');
    $form['args']['#description'] = $this->t('Additional output options for ImageMagick convert (e.g. -resize 50% -unsharp 0x.5).<br>See <a target="_blank" href="https://imagemagick.org/script/convert.php">documentation</a> for available options.');

    $new = [
      'inputargs' => [
        '#type' => 'textfield',
        '#title' => $this->t('Additional input arguments'),
        '#default_value' => $this->configuration['inputargs'],
        '#rows' => '8',
        '#description' => $this->t('Additional input options for ImageMagick convert (e.g. -density 144).<br>Check the <a target="_blank" href="https://manpages.ubuntu.com/manpages/trusty/man1/convert.im6.1.html">man page</a> to see which options are input options.'),
      ]
    ];
    $form = $this->utils->array_insert_after($form, 'mimetype', $new);


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::validateConfigurationForm($form, $form_state);

    $exploded_mime = explode('/', $form_state->getValue('mimetype'));

    if ($exploded_mime[0] != "image") {
      $form_state->setErrorByName(
        'mimetype',
        $this->t('Please enter an image mimetype (e.g. image/jpeg, image/png, etc...)')
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['inputargs'] = $form_state->getValue('inputargs');
  }
}
