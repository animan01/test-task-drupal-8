<?php

namespace Drupal\test_task\Plugin\views\access;

use Drupal\Core\Session\AccountInterface;
use Drupal\views\Plugin\views\access\AccessPluginBase;
use Symfony\Component\Routing\Route;

/**
 * Class ViewsCustomAccess.
 *
 * @ingroup views_access_plugins
 *
 * @ViewsAccess(
 *     id = "ViewsCustomAccess",
 *     title = @Translation("Customised access for views (1 or more Post's)"),
 *     help = @Translation("Add custom logic to access() method"),
 *     description = @Translation("User have one and more node 'Post'."),
 * )
 */
class ViewsCustomAccess extends AccessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function summaryTitle() {
    return $this->t('User have one and more node "Post"');
  }

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {

    $query = \Drupal::database()->select('node_field_data', 'n');
    $query->fields('n', []);
    $query->condition('type', 'post');
    $query->condition('uid', $account->id());
    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return FALSE;
    }
    else {
      return TRUE;
    }

  }

  /**
   * {@inheritdoc}
   */
  public function alterRouteDefinition(Route $route) {
  }

}
