<?php

namespace Drupal\tabt\Controller;

use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\tabt\Entity\TabtEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TabtViewController extends EntityViewController {

  public function __invoke(Request $request, string $view_mode = 'full'): array {
    return $this->view($this->extractEntityFromRequest($request), $view_mode);
  }

  public function title(Request $request): string {
    return $this->extractEntityFromRequest($request)->label();
  }

  private function extractEntityFromRequest(Request $request): TabtEntityInterface {
    foreach ($request->attributes->all() as $param) {
      if ($param instanceof TabtEntityInterface) {
        return $param;
      }
    }

    throw new NotFoundHttpException();
  }

}
