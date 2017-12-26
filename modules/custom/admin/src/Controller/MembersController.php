<?php
/**
* @file
* Contains \Drupal\admin\Controller\MembersController.
*/

namespace Drupal\admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Request;

// Redirect
use Symfony\Component\HttpFoundation\RedirectResponse;

class MembersController extends ControllerBase {
  public function index() {
    $members = \Drupal::service('member')->getAll();
    if ($members) {
      return array(
        '#theme' => 'member_list',
        '#members' => $members,
      );
    }

    throw new NotFoundHttpException();
  }


  // The name of the variable is the same as in the route!!!
  public function show($member_id = NULL) {
    // Service loading.
    $members = \Drupal::service('member');
    if ($record = $members->getById($member_id, TRUE)) {
      return array(
        // Working with our theme.
        '#theme' => 'member_theme',
        '#data' => $record,
      );
    }
    // Will render: page not found.
    throw new NotFoundHttpException();
  }

  public function new() {
    return array(
      '#theme' => 'member_add'
    );
  }

  public function store(Request $request) {
    $members = \Drupal::service('member');

    $username = $request->request->get('username');
    $address = $request->request->get('address');

    $members->add($username, $address);

    return new RedirectResponse("/admin/members");
  }
}
