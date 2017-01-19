<?php

/**
 * @file
 * Contains \Drupal\article\Plugin\Block\XaiBlock.
 */
namespace Drupal\quora\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an 'Quora' block.
 *
 * @Block(
 *   id = "quora_block",
 *   admin_label = @Translation("Related Quora Questions"),
 * )
 */
class QuoraContent extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#cache']['max-age'] = 0;
    $results = $this->_quora_content();
    if (isset($results) && !empty($results)) {
      $build[] = [
        '#theme' => 'quora_results',
        '#results' => $results,
      ];
    }
    return $build;
  }

  /**
   * Returns content for block.
   */
  public function _quora_content() {
    // Getting available contexts from menu.
    $context = \Drupal::request()->attributes->get('node');
    if (!($context && isset($context->nid))) {
      return NULL;
    }
    return _quora_build_content('block', $context);
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = array();
    _quora_settings_form('block', $form);
    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('quora_no_questions', $form_state->getValue('quora_no_questions'));
    $this->setConfigurationValue('quora_description', $form_state->getValue('quora_description'));
    $this->setConfigurationValue('quora_description_size', $form_state->getValue('quora_description_size'));
    $this->setConfigurationValue('quora_search_sensitivity', $form_state->getValue('quora_search_sensitivity'));
    $this->setConfigurationValue('quora_include', $form_state->getValue('quora_include'));
    $this->setConfigurationValue('quora_exclude', $form_state->getValue('quora_exclude'));
  }
}