<?php

namespace Drupal\corporate_indrivo\Installer\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Extension\ModuleExtensionList;

/**
 * A form class to customize Corporate Indrivo installation process.
 */
class AdditionalComponentsForm extends FormBase {

  use StringTranslationTrait;

  /**
   * Module extension list.
   *
   * @var \Drupal\Core\Extension\ModuleExtensionList
   */
  private ModuleExtensionList $moduleExtensionList;

  /**
   * Constructs a new class instance.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $module_extension_list
   *   The module extension list.
   */
  public function __construct(ModuleExtensionList $module_extension_list) {
    $this->moduleExtensionList = $module_extension_list;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.list.module')
    );
  }

  /**
   * Modules to enable directly from Corporate Indrivo installator.
   *
   * @var string[]
   */
  private $modules = [
    'ci_slide' => 'This module allows you to create slides for home page slider',
    'ci_news' => 'This module allows you to create news',
    'ci_partner' => 'This module allows you to create partners',
    'ci_faq' => 'This module allows you to create FAQ content',
    'ci_social_media' => 'This module allows you to create Social Media links block',
    'ci_job_vacancy' => 'This module allows you to create job vacancies',
    'ci_google_reviews' => 'This module allows you to display Google reviews',
    'ci_blog' => 'This module allows you to create blogs',
    'ci_team_member' => 'This module allows you to add team members',
  ];

  /**
   * List of all d_commerce dependencies.
   *
   * @var string[]
   */
  private $commerceModules = [
    'commerce',
    'commerce_cart',
    'commerce_checkout',
    'commerce_payment',
    'commerce_price',
    'commerce_product',
    'commerce_promotion',
    'commerce_tax',
  ];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ci_additional_modules_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->messenger()->deleteAll();

    $form['#title'] = $this->t('Install & configure Indrivo components');

    foreach ($this->modules as $name => $description) {
      $disabled = !$this->moduleExist($name);
      if ($name == 'ci_commerce' && !$this->modulesExists($this->commerceModules)) {
        $description = $this->t('Out-of-the-box support for Commerce module for Drupal. You have to install additional modules to enable this checkbox.');
        $disabled = TRUE;
      }

      $form['install']['module_' . $name] = [
        '#type' => 'checkbox',
        '#title' => $name,
        '#description' => $this->t('@description', ['@description' => $description]),
        '#disabled' => $disabled,
      ];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save and continue'),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $build_info = $form_state->getBuildInfo();
    $install_state = $build_info['args'];

    $install_modules = $additional_modules = [];
    foreach ($this->modules as $name => $desc) {
      if ($values['module_' . $name]) {
        $install_modules[] = $name;
      }
    }

    $install_state[0]['ci_additional_modules'] = array_unique(array_merge($install_modules, $additional_modules), SORT_REGULAR);
    $build_info['args'] = $install_state;
    $form_state->setBuildInfo($build_info);
  }

  /**
   * Check if module (enabled or not) exist in Drupal.
   *
   * @param string $module_name
   *   Module name.
   *
   * @return \Drupal\Core\Extension\Extension|mixed
   *   Return TRUE if module exist.
   */
  private function moduleExist(string $module_name) {
    $modules_data = $this->moduleExtensionList->getList();
    return !empty($modules_data[$module_name]);
  }

  /**
   * Check if all modules exists.
   *
   * @param array $modules
   *   List of modules.
   *
   * @return bool
   *   Return TRUE if all modules exist.
   */
  private function modulesExists(array $modules) {
    foreach ($modules as $module) {
      if (!$this->moduleExist($module)) {
        return FALSE;
      }
    }
    return TRUE;
  }

}
