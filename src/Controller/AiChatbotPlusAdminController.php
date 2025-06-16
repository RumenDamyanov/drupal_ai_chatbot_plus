<?php

namespace Drupal\ai_chatbot_plus\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ai_chatbot_plus\Service\AiChatbotPlusService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the AI Chatbot Plus analytics page.
 *
 * @category Controller
 * @package  Ai_Chatbot_Plus
 * @author   Rumen Damyanov <contact@rumenx.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.drupal.org/project/ai_chatbot_plus
 */
class AiChatbotPlusAdminController extends ControllerBase
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
     * Returns the analytics page render array.
     *
     * @return array
     *   A Drupal render array for the analytics page.
     */
    public function analyticsPage()
    {
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
