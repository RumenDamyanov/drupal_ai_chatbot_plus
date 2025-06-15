<?php

namespace Drupal\ai_chatbot\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ai_chatbot\Service\AiChatbotService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AiChatbotAdminController extends ControllerBase {
  protected $chatbotService;

  public function __construct(AiChatbotService $chatbotService) {
    $this->chatbotService = $chatbotService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ai_chatbot.service')
    );
  }

  public function analyticsPage() {
    $log = $this->chatbotService->getConversationLog(200);
    $rows = [];
    foreach ($log as $entry) {
      $rows[] = [
        'data' => [
          $entry['timestamp'] ? date('Y-m-d H:i:s', $entry['timestamp']) : '',
          $entry['uid'] ?? '-',
          $entry['session_id'] ?? '-',
          $entry['role'] ?? '-',
          $entry['message'] ?? '',
        ],
      ];
    }
    $header = [
      $this->t('Timestamp'),
      $this->t('User ID'),
      $this->t('Session ID'),
      $this->t('Role'),
      $this->t('Message'),
    ];
    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No conversation logs found.'),
      '#title' => $this->t('AI Chatbot Conversation Log'),
    ];
  }
}
