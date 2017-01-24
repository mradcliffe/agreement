<?php

namespace Drupal\agreement\Entity;

use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Provides a dynamic canonical route for an agreement.
 */
class AgreementRouteProvider implements EntityRouteProviderInterface, EntityHandlerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Initialize method.
   *
   * This duplicates DefaultHtmlRouteProvider because for some silly reason
   * that class been marked as "internal" so IDEs will complain that you are
   * not supposed to use it. DrupalWTF.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = new RouteCollection();
    $agreements = $this->entityTypeManager
      ->getStorage('agreement')
      ->loadMultiple();

    foreach ($agreements as $agreement) {
      $id = $agreement->id();
      $collection->add("agreement.$id", $this->getCanonicalRoute($agreement));
    }

    return $collection;
  }

  /**
   * Get the route information from agreement entity.
   *
   * @param \Drupal\agreement\Entity\Agreement $agreement
   *   The agreement entity.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   A route object.
   */
  protected function getCanonicalRoute(Agreement $agreement) {
    
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

}
