<?php
/**
* @file
* Contains \Drupal\mypage\Controller\MyPageController.
*/

namespace Drupal\mypage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MyPageController extends ControllerBase {
 // The name of the variable is the same as in the route!!!
 public function content($mypage_id = NULL) {
   // Service loading.
   $db_logic = \Drupal::service('mypage.db_logic');
   if ($record = $db_logic->getById($mypage_id, TRUE)) {
    return array(
    // Working with our theme.
    '#theme' => 'mypage_theme',
    '#data' => $record,
    );
   }
   // Will render: page not found.
   throw new NotFoundHttpException();
 }
}
