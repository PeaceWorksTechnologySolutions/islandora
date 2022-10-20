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

    $form['inputargs']['#title'] = $this->t('Additional input arguments');
    $form['inputargs']['#description'] = $this->t('Additional input options for ImageMagick convert (e.g. -density 144).<br>Check the <a target="_blank" href="https://manpages.ubuntu.com/manpages/trusty/man1/convert.im6.1.html">man page</a> to see which options are input options.');

    $form['args']['#title'] = $this->t('Additional output arguments');
    $form['args']['#description'] = $this->t('Additional output options for ImageMagick convert (e.g. -resize 50% -unsharp 0x.5).<br>See <a target="_blank" href="https://imagemagick.org/script/convert.php">documentation</a> for available options.');
    return $form;
  }

}
