<?php

namespace Drupal\agreement;

use Drupal\agreement\Entity\Agreement;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Agreement handler interface.
 */
class AgreementHandlerInterface {

  /**
   * Check the status of an user account for a particular agreement.
   *
   * @param \Drupal\agreement\Entity\Agreement $agreement
   *   The agreement to check if a user has agreed.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The user account to check.
   *
   * @return boolean
   *   TRUE if the user account has agreed to this agreement.
   */
  public function hasAgreed(Agreement $agreement, AccountProxyInterface $account);

  /**
   * Accept the agreement for an user account.
   *
   * @param \Drupal\agreement\Entity\Agreement $agreement
   *   The agreement that the user is agreeing to.
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The user account to agree.
   * @param int $agree
   *   An optional integer to set the agreement status to. Defaults to 1.
   */
  public function agree(Agreement $agreement, AccountProxyInterface $account, $agree = 1);

  /**
   * Find the agreement by user account and path.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The user account to check.
   * @param string $path
   *   The path to check.
   */
  public function getAgreementByUserAndPath(AccountProxyInterface $account, $path);

}
