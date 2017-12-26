<?php

/**
* @file
* Contains \Drupal\admin\Form\AddMember.
*/

namespace Drupal\admin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\SafeMarkup;

class AddMember extends ConfigFormBase {

 /**
  * {@inheritdoc}.
  */
 // Method that renders form id.
 public function getFormId() {
   return 'addmember_form';
 }

 /**
  * {@inheritdoc}.
  */
 // Instead of hook_form.
 public function buildForm(array $form, FormStateInterface $form_state) {
   $form = parent::buildForm($form, $form_state);
   $config = $this->config('addmember.settings');
   $form['email'] = array(
    '#type' => 'email',
    '#title' => $this->t('Your .com email address.'),
    '#default_value' => $config->get('email_address'),
   );
   $form['username'] = array(
    '#type' => 'textfield',
    '#username' => $this->t('Username'),
    '#required' => TRUE,
   );
   $form['address'] = array(
    '#type' => 'textarea',
    '#title' => $this->t('Address'),
    '#rows' => 5,
    '#required' => TRUE,
   );
   $db_logic = \Drupal::service('member');
   $data = $db_logic->getAll();
   if ($data) {
    $form['data'] = array(
    '#type' => 'table',
    '#caption' => $this->t('Table Data'),
    '#header' => array($this->t('id'), $this->t('Username'), $this->t('Address')),
    );
    foreach ($data as $item) {
    // Example of link creation.
    // The first argument is route name, the second argument are its parameters
    // $url = Url::fromRoute('members.view', array(
    // $url = Url::fromRoute('members.view', array(
    //     'member_id' => $item->id,
    // ));
    $form['data'][] = array(
        'id' => array(
        '#type' => 'markup',
        // '#markup' => \Drupal::l($item->id, $url),
        '#markup' => '123',
        ),
        'username' => array(
        '#type' => 'markup',
        '#markup' => $item->username,
        ),
        'address' => array(
        '#type' => 'markup',
        '#markup' => $item->address,
        ),
    );
    }
   }
   return $form;
 }

 /**
  * {@inheritdoc}
  */
 // Instead of hook_form_validate.
 public function validateForm(array &$form, FormStateInterface $form_state) {
   if (strpos($form_state->getValue('email'), '.com') === FALSE) {
    $form_state->setErrorByName('email', $this->t('This is not a .com email address.'));
   }
 }

 /**
  * {@inheritdoc}
  */
 // Instead of hook_form_submit.
 public function submitForm(array &$form, FormStateInterface $form_state) {
   $db_logic = \Drupal::service('member');
   $username = SafeMarkup::checkPlain($form_state->getValue('username'));
   $address = SafeMarkup::checkPlain($form_state->getValue('address'));

   $db_logic->add($username, $address);
   // Instead of variable_set/get we have config.
   // Example of working with them.
   $config = $this->config('addmember.settings');
   $config->set('email_address', $form_state->getValue('email'));
   $config->save();
   return parent::submitForm($form, $form_state);
 }

 /**
  * {@inheritdoc}
  */
 // An array of names of configuration objects that are available for editing.
 protected function getEditableConfigNames() {
   return ['addmember.settings'];
 }

}
