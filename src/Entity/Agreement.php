<?php

namespace Drupal\agreement\Entity\Agreement;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
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
class Agreement extends ConfigEntityBase {}
