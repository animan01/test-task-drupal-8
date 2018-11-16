<?php

namespace Drupal\test_task\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a form that configures Trustlink forms module settings.
 */
class EnvironmentForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test_task_environment_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'test_task.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('test_task.settings');
    $form = [];

    $form['environment']['welcome'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Welcome'),
      '#description' => $this->t('This is welcome text.'),
      '#default_value' => $config->get('welcome'),
      '#size' => 60,
      '#maxlength' => 100,
      '#weight' => 0,
    ];

    $form['environment']['promotion_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Promotion File'),
      '#description' => t('Allowed extensions: gif png jpg jpeg pdf'),
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg pdf'],
        'file_validate_size' => [5120000],
      ],
      '#default_value' => ['target_id' => $config->get('promotion_file')],
      '#upload_location' => 'public://promotion_file/',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->config('test_task.settings');

    if (!empty($values['promotion_file'])) {
      $fid = current($values['promotion_file']);
      $config->set('promotion_file', $fid)
        ->save();
    }

    drupal_set_message($this->t('Configuration success saved'));

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->config('test_task.settings');

    if (!empty($values['promotion_file'])) {
      $fid = current($values['promotion_file']);
      $config->set('promotion_file', $fid)
        ->save();
    }

    $config->set('welcome', $values['welcome'])
      ->save();

  }

}
