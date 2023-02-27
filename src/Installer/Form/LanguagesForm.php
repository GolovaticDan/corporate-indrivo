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
class LanguagesForm extends FormBase {

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
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ci_languages_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->messenger()->deleteAll();

    $form['#title'] = $this->t('Additional languages');

    $form['translations'] = [
      '#type' => 'select',
      '#title' => $this->t('Additional languages'),
      '#description' => $this->t('Select additional languages to enable and download contributed interface translations.'),
      '#options' => _country_get_predefined_list(),
      '#multiple' => TRUE,
      '#size' => 10,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Install selected languages'),
      '#button_type' => 'primary',
      '#submit' => ['::submitForm'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form['multilanguage_selected_translations'] = $form_state->getValue('translations');
  }

}
