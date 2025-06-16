<?php

namespace Drupal\ai_chatbot_plus\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ai_chatbot_plus\Service\AiChatbotPlusService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the AI Chatbot Plus page.
 *
 * @category Controller
 * @package  Ai_Chatbot_Plus
 * @author   Rumen Damyanov <contact@rumenx.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.drupal.org/project/ai_chatbot_plus
 */
class AiChatbotPlusController extends ControllerBase
{
    /**
     * The AI Chatbot Plus service.
     *
     * @var \Drupal\ai_chatbot_plus\Service\AiChatbotPlusService
     */
    protected $chatbotService;

    /**
     * Constructs the controller.
     *
     * @param \Drupal\ai_chatbot_plus\Service\AiChatbotPlusService $chatbotService
     *   The chatbot service instance.
     */
    public function __construct(AiChatbotPlusService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The service container.
     *
     * @return static
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('ai_chatbot_plus.service')
        );
    }

    /**
     * Returns the chatbot page render array.
     *
     * @return array
     *   A Drupal render array for the chatbot page.
     */
    public function chatbotPage()
    {
        $config = \Drupal::config('ai_chatbot_plus.settings');
        $widget = $config->get('widget_customization') ?: [];
        return [
            '#theme' => 'ai_chatbot_plus_page',
            '#color' => $widget['color'] ?? '#0074d9',
            '#avatar' => $widget['avatar'] ?? '',
            '#welcome_message' => $widget['welcome_message'] ??
                'Hi! How can I help you?',
            '#display_mode' => $widget['display_mode'] ?? 'floating',
            '#attached' => [
                'library' => [
                    'ai_chatbot_plus/ai_chatbot_plus',
                ],
            ],
        ];
    }
}
