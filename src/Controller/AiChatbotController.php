<?php

namespace Drupal\ai_chatbot\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ai_chatbot\Service\AiChatbotService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AiChatbotController extends ControllerBase {
  protected $chatbotService;

  public function __construct(AiChatbotService $chatbotService) {
    $this->chatbotService = $chatbotService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ai_chatbot.service')
    );
  }

  public function chatbotPage() {
    $config = Drupal::config('ai_chatbot.settings');
    $widget = $config->get('widget_customization') ?: [];
    return [
      '#theme' => 'ai_chatbot_page',
      '#color' => $widget['color'] ?? '#0074d9',
      '#avatar' => $widget['avatar'] ?? '',
      '#welcome_message' => $widget['welcome_message'] ?? 'Hi! How can I help you?',
      '#display_mode' => $widget['display_mode'] ?? 'floating',
      '#attached' => [
        'library' => [
          'ai_chatbot/ai_chatbot',
        ],
      ],
    ];
  }
}
