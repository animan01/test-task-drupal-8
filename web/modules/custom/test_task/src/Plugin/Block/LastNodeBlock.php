<?php

namespace Drupal\test_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'User name and Role' Block.
 *
 * @Block(
 *   id = "last_node_block",
 *   admin_label = @Translation("Last node block"),
 * )
 */
class LastNodeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $entity_type = 'node';
    $view_mode = 'teaser';

    $nid = \Drupal::entityQuery('node')
      ->condition('type', $this->configuration['node_type'])
      ->range(0, 1)
      ->sort('changed', 'DESC')
      ->execute();
    $nid = current($nid);

    // Render node content.
    $builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $node = $storage->load($nid);
    $build = $builder->view($node, $view_mode);
    $output = render($build);

    return [
      '#markup' => $output,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $types = \Drupal::entityTypeManager()
      ->getStorage('node_type')
      ->loadMultiple();

    $node_types = [];
    foreach ($types as $key => $type) {
      $node_types[$key] = $type->label();
    }

    $form['node_type'] = [
      '#type' => 'select',
      '#title' => t('Node type'),
      '#options' => $node_types,
      '#default_value' => $config['node_type'],
      '#description' => t('Set this to <em>Yes</em> if you would like this category to be selected by default.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['node_type'] = $form_state->getValue('node_type');
  }

}
