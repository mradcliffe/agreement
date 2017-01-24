<?php

namespace Drupal\agreement\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Agreement entity.
 *
 * @ConfigEntityType(
 *   id = "agreement",
 *   label = @Translation("Agreement"),
 *   admin_permission = "administer agreements",
 *   handlers = {
 *     "list_builder" = "Drupal\agreement\Entity\AgreementListBuilder",
 *     "form" => {
 *       "default" = "Drupal\agreement\Form\AgreementForm",
 *     },
 *     "view_builder" = "Drupal\agreement\Entity\AgreementViewBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\agreement\Entity\Routing\AgreementRouteProvider",
 *     },
 *   },
 *   config_prefix = "agreement",
 *   static_cache = TRUE,
 *   entity_keys = {
 *     "id" = "name",
 *     "type" = "label",
 *   },
 *   links = {
 *     "delete-form" = "/admin/config/people/agreement/manage/{agreement}/delete",
 *     "edit-form" = "/admin/config/people/agreement/manage/{agreement}",
 *     "collection" = "/admin/config/people/agreement/manage",
 *   },
 *   config_export = {
 *     "name",
 *     "type",
 *     "path",
 *     "settings",
 *     "agreement",
 *   }
 * )
 */
class Agreement extends ConfigEntityBase {

  /**
   * Agreement frequency setting.
   *
   * @return bool
   *   TRUE if the agreement is configured for users to agree only once.
   */
  public function agreeOnce() {
    return $this->get('settings')->get('frequency')->getValue() === 0;
  }

  /**
   * Find if the agreement applies to an user account by role.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $account
   *   The user account to check roles for.
   *
   * @return bool
   *   TRUE if the user account has a role configured for this agreement.
   */
  public function accountHasAgreementRole(AccountProxyInterface $account) {
    $account_roles = $account->getRoles();
    $roles = $this->get('settings')->get('roles');
    $has_roles = array_intersect($roles, $account_roles);
    return !empty($has_roles);
  }

  /**
   * Get a formatted visibility pages as a string.
   *
   * @return string
   *   Get the visibility pages setting as a string.
   */
  public function getVisibilityPages() {
    $pages = $this
      ->get('settings')
      ->get('visibility')
      ->get('pages')
      ->getValue();
    return html_entity_decode(strtolower(implode("\n", $pages)));
  }

  /**
   * Get the visibility setting.
   *
   * @return int
   *   The visibility setting: 0 for match all except, and 1 for match any.
   */
  public function getVisibilitySetting() {
    return $this->get('settings')->get('visibility')->get('setting')->getValue();
  }

}
