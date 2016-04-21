<?php
/**
 * @file
 * Contains \Drupal\quora\Plugin\Block\QuoraBlock.
 */

namespace Drupal\quora\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'quora' block.
 *
 * @Block(
 *   id = "quora_block",
 *   admin_label = @Translation("Related Quora Questions"),
 *   category = @Translation("Blocks")
 * )
 */
class QuoraBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Retrieve existing configuration for this block.
    $config = $this->getConfiguration();
    // Add a form field to the existing block configuration form.
    $form['quora_no_questions'] = array(
      '#type' => 'select',
      '#title' => t('Number of related questions to be shown'),
      '#options' => array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
      ),
      '#default_value' => isset($config['quora_no_questions']) ? $config['quora_no_questions'] : '',
    );
    $form['quora_description'] = array(
      '#type' => 'select',
      '#title' => t('Description with questions'),
      '#options' => array(
        'enable' => t('Enable'),
        'disable' => t('Disable'),
      ),
      '#default_value' => isset($config['quora_description']) ? $config['quora_description'] : '',
    );
    $form['quora_description_size'] = array(
      '#type' => 'number',
      '#title' => t('Limit Description text'),
      '#description' => t('Enter size, 0 for no limit'),
      '#element_validate' => array('element_validate_integer'),
      '#default_value' => isset($config['quora_description_size']) ? $config['quora_description_size'] : '',
      '#states' => array(
        'visible' => array(
          ':input[name="quora_description"]' => array('value' => 'enable'),
        ),
      ),
    );
    $form['quora_search_sensitivity'] = array(
      '#type' => 'select',
      '#title' => t('Search Sensitivity'),
      '#options' => array(
        0 => t('Auto'),
        1 => t('3 Words'),
        2 => t('5 Words'),
        3 => t('7 Words'),
        4 => t('Maximum'),
      ),
      '#default_value' => isset($config['quora_search_sensitivity']) ? $config['quora_search_sensitivity'] : '',
    );
    $form['quora_include'] = array(
      '#type' => 'textfield',
      '#title' => t('Always include certain words'),
      '#description' => t('Use comma to separate multiple words. (Case Insensitive)'),
      '#default_value' => isset($config['quora_include']) ? $config['quora_include'] : '',
    );
    $form['quora_exclude'] = array(
      '#type' => 'textfield',
      '#title' => t('Always exclude certain words'),
      '#description' => t('Use comma to separate multiple words. (Case Insensitive)'),
      '#default_value' => isset($config['quora_exclude']) ? $config['quora_exclude'] : '',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    // Save our custom settings when the form is submitted.
    $this->setConfigurationValue('quora_no_questions', $form_state->getValue('quora_no_questions'));
    $this->setConfigurationValue('quora_description', $form_state->getValue('quora_description'));
    $this->setConfigurationValue('quora_description_size', $form_state->getValue('quora_description_size'));
    $this->setConfigurationValue('quora_search_sensitivity', $form_state->getValue('quora_search_sensitivity'));
    $this->setConfigurationValue('quora_include', $form_state->getValue('quora_include'));
    $this->setConfigurationValue('quora_exclude', $form_state->getValue('quora_exclude'));
  }
}
