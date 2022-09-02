<?php

namespace Drupal\tabt_team_management\Controller;

use Drupal\Component\Utility\Crypt;
use Drupal\Component\Utility\Tags;
use Drupal\Core\Site\Settings;
use Drupal\system\Controller\EntityAutocompleteController;
use Drupal\tabt\Entity\TeamInterface;
use Drupal\tabt\Entity\TournamentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PlayerAutocompleteController extends EntityAutocompleteController {

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('tabt.autocomplete_matcher.player'),
      $container->get('keyvalue')->get('entity_autocomplete')
    );
  }

  public function __invoke(
    Request $request,
    TournamentInterface $tournament,
    TeamInterface $team,
    string $selection_settings_key
  ): JsonResponse {
    $input = $request->query->get('q');
    if (empty($input)) {
      return new JsonResponse([]);
    }

    $selection_settings = $this->keyValue->get($selection_settings_key, FALSE);
    if ($selection_settings === FALSE) {
      throw new AccessDeniedHttpException();
    }

    $data = serialize($selection_settings) . 'tabt_member' . 'default';
    $selection_settings_hash = Crypt::hmacBase64($data, Settings::getHashSalt());
    if (!hash_equals($selection_settings_hash, $selection_settings_key)) {
      throw new AccessDeniedHttpException('Invalid selection settings key.');
    }

    $tag_list = Tags::explode($input);
    $typed_string = !empty($tag_list) ? mb_strtolower(array_pop($tag_list)) : '';

    $matches = $this->matcher->getMatches(
      'tabt_member',
      'default',
      $selection_settings,
      $typed_string,
      $tournament,
      $team
    );

    return new JsonResponse($matches);
  }

}
