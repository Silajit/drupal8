<?php

namespace Drupal\site_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
/**
 * Provides a 'Block' block.
 *
 * @Block(
 *   id = "my_block_socialshare",
 *   admin_label = @Translation("Share On Social Media"),
 * )
 */
class SocialShare extends BlockBase {
  
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'link_active_fb' => 1,
      'link_active_gp' => 1,
    );
  }
  /**
   * Helper function to get Links list.
   */
  public function getLinksList() {
    return array(
      'link_active_fb' => "Facebook",
      'link_active_gp' => "Google",
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $link_list = $this->getLinksList();
    foreach ($link_list as $key => $name) {
      $form[$key] = array(
        '#title' => t('Display the link : @link', ['@link' => $name]),
        '#type' => 'checkbox',
        '#default_value' => $this->configuration[$key],
      );
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $link_list = $this->getLinksList();
    foreach ($link_list as $key => $name) {
      $this->configuration[$key] = $form_state->getValue($key);
    }
  }


  /**
   * Block Access Example. Display for Anonymous users only.
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    if (!$account->isAnonymous()) {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }

/**
   * {@inheritdoc}
   */
  public function build() {
    $output = array();
    $output[]['#cache']['max-age'] = 0;
    if (\Drupal::service('path.matcher')->isFrontPage()) {
      $current_url = "";
    }
    else {
      $url = Url::fromRoute('<current>');
      $current_url = $url->toString();
    }
    $path = "http://" . \Drupal::request()->getHost() . $current_url;

    $html = "";
    $html .= "<div class='block-social'><ul>";
    if ($this->configuration['link_active_fb']) {
      $html .= "<li><a class='icon icon-fb' href='http://www.facebook.com/sharer.php?u=$path'>FaceBook</a></li>";
    }
    if ($this->configuration['link_active_gp']) {
      $html .= "<li><a class='icon icon-gp' href='http://www.google.com/bookmarks/mark?op=add&bkmk=$path'>Google</a></li>";
    }
    $html .= "</ul></div>";

    $output[] = [
      '#markup' => $html
    ];
    return $output;
  }
}