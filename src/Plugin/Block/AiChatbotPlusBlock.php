<?php

namespace Drupal\ai_chatbot_plus\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an AI Chatbot Plus block.
 *
 * @category Block
 * @package  Ai_Chatbot_Plus
 * @author   Rumen Damyanov <contact@rumenx.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.drupal.org/project/ai_chatbot_plus
 *
 * @Block(
 *   id = "ai_chatbot_plus_block",
 *   admin_label = @Translation("AI Chatbot Plus Block"),
 *   category = @Translation("Artificial Intelligence")
 * )
 */
class AiChatbotPlusBlock extends BlockBase implements BlockPluginInterface
{

    /**
     * Builds the AI Chatbot Plus block content.
     *
     * @return array
     *   A render array representing the block content.
     */
    public function build()
    {
        if (!\Drupal::currentUser()->hasPermission('access ai chatbot plus')) {
            return [];
        }
        $config = \Drupal::config('ai_chatbot_plus.settings');
        $widget = $config->get('widget_customization') ?: [];
        $block_title = !empty($this->configuration['block_title'])
            ? $this->configuration['block_title']
            : '';
        return [
            '#theme' => 'ai_chatbot_plus_block',
            '#color' => $widget['color'] ?? '#0074d9',
            '#avatar' => $widget['avatar'] ?? '',
            '#welcome_message' => $widget['welcome_message'] ??
                'Hi! How can I help you?',
            '#display_mode' => $widget['display_mode'] ?? 'floating',
            '#block_title' => $block_title,
            '#attached' => [
                'library' => [
                    'ai_chatbot_plus/ai_chatbot_plus',
                ],
            ],
        ];
    }

    /**
     * Block form for AI Chatbot Plus block configuration.
     *
     * @param array $form
     *   The form array.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The form state object.
     *
     * @return array
     *   The modified form array.
     */
    public function blockForm(array $form, FormStateInterface $form_state)
    {
        $form = parent::blockForm($form, $form_state);
        $form['block_title'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Block title'),
            '#default_value' => $this->configuration['block_title'] ?? '',
        ];
        return $form;
    }

    /**
     * Handles submission of the block configuration form.
     *
     * @param array $form
     *   The form array.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The form state object.
     *
     * @return void
     */
    public function blockSubmit(array $form, FormStateInterface $form_state)
    {
        $this->configuration['block_title'] = $form_state->getValue('block_title');
    }
}
