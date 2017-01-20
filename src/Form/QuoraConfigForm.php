<?php

namespace Drupal\quora\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

/**
 * Admin form for setting Google Api and CX ID.
 */
class QuoraConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quora_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return 'quora.admin';
  }

  /**
   * Returns Admin Form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = \Drupal::config('quora.admin');

    $form = array();
    $form['quora_google_cse_api'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Custom Search Api'),
      '#description' => t('Provide google cse api to be used my module'),
      '#default_value' => $config->get('quora_google_cse_api'),
    );
    $form['quora_google_cse_cx'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Custom Search Engine CX ID'),
      '#default_value' => $config->get('quora_google_cse_cx'),
      '#description' => t('The custom search engine corresponding to this cx-id must be able to search quora.com'),
    );
    $form['quora'] = array(
      '#type' => 'fieldset',
      '#title' => t('Select Tags field for content types.'),
      '#description' => t('This field content will be used for fetching related quora questions.'),
    );
    foreach (NodeType::loadMultiple() as $content_type) {
      $fields = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $content_type->get('type'));
      if ($fields) {
        $options = array();
        foreach ($fields as $id => $field) {
          $options[$id] = $field->getlabel();
        }
        $form['quora']['quora_' . $content_type->get('type') . '_field'] = array(
          '#type' => 'select',
          '#title' => $content_type->label(),
          '#options' => array_merge(array('0' => t('Auto')), $options),
          '#default_value' => $config->get('quora_' . $content_type->get('type') . '_field'),
        );
      }
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit function for Admin Form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $google_cse_api = $form_values['quora_google_cse_api'];
    $google_cse_cx = $form_values['quora_google_cse_cx'];
    \Drupal::getContainer()->get('config.factory')->getEditable('quora.admin')
      ->set('quora_google_cse_api', $google_cse_api)
      ->set('quora_google_cse_cx', $google_cse_cx)
      ->save();
    foreach (NodeType::loadMultiple() as $content_type) {
      \Drupal::getContainer()->get('config.factory')->getEditable('quora.admin')
        ->set('quora_' . $content_type->get('type') . '_field', $form_values['quora_' . $content_type->get('type') . '_field'])->save();
    }
    parent::submitForm($form, $form_state);
  }

}
