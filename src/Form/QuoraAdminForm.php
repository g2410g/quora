<?php
/**
 * @file
 * Contains \Drupal\quora\Form\QuoraAdminForm.
 */

namespace Drupal\quora\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * QuoraAdminForm class.
 */
class QuoraAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['quora.quora_admin_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'quora_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('quora.settings');
    $form = parent::buildForm($form, $form_state);

    $form['quora_google_cse_api'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Custom Search Api'),
      '#description' => t('Provide google cse api to be used my module'),
    );
    $form['quora_google_cse_cx'] = array(
      '#type' => 'textfield',
      '#title' => t('Google Custom Search Engine CX ID'),
      '#description' => t('The custom search engine corresponding to this cx-id must be able to search quora.com'),
    );
    $form['quora'] = array(
      '#type' => 'fieldset',
      '#title' => t('Select Tags field for content types.'),
      '#description' => t('This field content will be used for fetching related quora questions.'),
    );
    foreach (node_type_get_types() as $content_type) {
      $entity_type_id = 'node';
      $fields = \Drupal::entityManager()->getFieldDefinitions('node', $content_type->get('type'));
      if ($fields) {
        $options = array();
        foreach ($fields as $field_name => $field_definition) {
          if (!empty($field_definition->getTargetBundle())) {
            $options[$field_name] = $field_definition->getLabel();
          }
        }
        $form['quora']['quora_' . $content_type->get('type') . '_field'] = array(
          '#type' => 'select',
          '#title' => $content_type->get('name'),
          '#options' => array_merge(array('0' => t('Auto')), $options),
        );
      }
    }
    return $form;
  }

}
