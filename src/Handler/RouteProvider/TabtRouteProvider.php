<?php

namespace Drupal\tabt\Handler\RouteProvider;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class TabtRouteProvider implements EntityRouteProviderInterface {

  public function getRoutes(EntityTypeInterface $entity_type): RouteCollection {
    $route_collection = new RouteCollection();

    $route = new Route("/tabt/{$entity_type->id()}/{{$entity_type->id()}}");
    $route->setOption('parameters', [$entity_type->id() => ['type' => "entity:{$entity_type->id()}"]]);
    $route->setRequirement($entity_type->id(), '\d+');
    $route->setRequirement('_permission', "view {$entity_type->id()} tabt entities");
    $route->addDefaults([
      '_controller' => '\Drupal\tabt\Controller\TabtViewController',
      '_title_callback' => '\Drupal\tabt\Controller\TabtViewController::title',
    ]);

    $route_collection->add("entity.{$entity_type->id()}.canonical", $route);

    return $route_collection;
  }

}
