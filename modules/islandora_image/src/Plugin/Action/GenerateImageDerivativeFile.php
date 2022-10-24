<?php

namespace Drupal\islandora_image\Plugin\Action;

use Drupal\Core\Form\FormStateInterface;
use Drupal\islandora\Plugin\Action\AbstractGenerateDerivativeMediaFile;

/**
 * Emits a Node for generating derivatives event.
 *
 * @Action(
 *   id = "generate_image_derivative_file",
 *   label = @Translation("Generate an Image Derivative for Media Attachment"),
 *   type = "media"
 * )
 */
class GenerateImageDerivativeFile extends AbstractGenerateDerivativeMediaFile {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config['path'] = '[date:custom:Y]-[date:custom:m]/[media:mid]-ImageService.jpg';
    $config['mimetype'] = 'application/xml';
    $config['queue'] = 'islandora-connector-houdini';
    $config['destination_media_type'] = 'file';
    $config['scheme'] = $this->config->get('default_scheme');
    $config['inputargs'] = '';
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['mimetype']['#description'] = $this->t('Mimetype to convert to (e.g. application/xml, etc...)');
    $form['mimetype']['#value'] = 'image/jpeg';
    $form['mimetype']['#type'] = 'hidden';

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
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['inputargs'] = $form_state->getValue('inputargs');
  }
}
