<?php

namespace Drupal\ci_social_media\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ci_social_media\Form\ConfigurationForm;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block contains links to social media.
 *
 * @Block(
 *   id = "social_media_block",
 *   admin_label = @Translation("Social Media Block"),
 * )
 */
class SocialMediaBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactory $configFactory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $links = [];
    $config = $this->configFactory->get('ci_social_media.settings');
    foreach (ConfigurationForm::getMediaNames() as $name) {
      if (!empty($config->get("link_$name"))) {
        $links[] = [
          'name' => $name,
          'link' => $config->get("link_$name"),
        ];
      }
    }

    // Not render block if links are empty.
    if (empty($links)) {
      return [];
    }

    return [
      '#theme' => 'ci_social_media',
      '#attached' => [
        'library' => [
          'ci_social_media/last-element-in-a-row',
        ],
      ],
      '#links' => $links,
    ];
  }

}
