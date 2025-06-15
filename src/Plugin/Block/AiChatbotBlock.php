<?php

namespace Drupal\ai_chatbot\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal;

/**
 * Provides an AI Chatbot block.
 *
 * @Block(
 *   id = "ai_chatbot_block",
 *   admin_label = @Translation("AI Chatbot Block"),
 *   category = @Translation("Artificial Intelligence")
 * )
 */
class AiChatbotBlock extends BlockBase implements BlockPluginInterface {
  public function build() {
    if (!\Drupal::currentUser()->hasPermission('access ai chatbot')) {
      return [];
    }
    $config = Drupal::config('ai_chatbot.settings');
    $widget = $config->get('widget_customization') ?: [];
    $block_title = !empty($this->configuration['block_title']) ? $this->configuration['block_title'] : '';
    return [
      '#theme' => 'ai_chatbot_block',
      '#color' => $widget['color'] ?? '#0074d9',
      '#avatar' => $widget['avatar'] ?? '',
      '#welcome_message' => $widget['welcome_message'] ?? 'Hi! How can I help you?',
      '#display_mode' => $widget['display_mode'] ?? 'floating',
      '#block_title' => $block_title,
      '#attached' => [
        'library' => [
          'ai_chatbot/ai_chatbot',
        ],
      ],
    ];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $form['block_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block title'),
      '#default_value' => $this->configuration['block_title'] ?? '',
    ];
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['block_title'] = $form_state->getValue('block_title');
  }
}
