<?php

namespace Drupal\tabt\Handler\RouteProvider;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class TabtRouteProvider implements EntityRouteProviderInterface {

  public function getRoutes(EntityTypeInterface $entity_type): RouteCollection {
    $route_collection = new RouteCollection();

    $route = new Route($this->getPath($entity_type));
    $route->setOption('parameters', ['tabt' => ['type' => "entity:{$entity_type->id()}"]]);
    $route->setRequirement($entity_type->id(), '\d+');
    $route->setRequirement('_permission', "view {$entity_type->id()} tabt entities");
    $route->addDefaults([
      '_controller' => '\Drupal\tabt\Controller\TabtViewController::view',
      '_title_callback' => '\Drupal\tabt\Controller\TabtViewController::title',
    ]);

    $route_collection->add("entity.{$entity_type->id()}.canonical", $route);

    return $route_collection;
  }

  private function getPath(EntityTypeInterface $entity_type): string {
    return '/tabt/' . str_replace('tabt_', '', $entity_type->id()) . '/{tabt}';
  }

}
