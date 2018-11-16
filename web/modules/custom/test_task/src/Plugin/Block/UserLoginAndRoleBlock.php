<?php

namespace Drupal\test_task\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'User name and Role' Block.
 *
 * @Block(
 *   id = "login_role_block",
 *   admin_label = @Translation("User name and Role block"),
 * )
 */
class UserLoginAndRoleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();

    if (is_array($roles)) {
      $roles = implode($roles, ', ');
    }

    return [
      '#theme' => 'block_name_role',
      '#cache' => ['max-age' => 0],
      '#name' => $current_user->getDisplayName(),
      '#roles' => $roles,
    ];
  }

}
