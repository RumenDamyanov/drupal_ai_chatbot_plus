<?php

namespace Drupal\ai_chatbot_plus\Service;

use rumenx\php_chatbot\Chatbot;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\State\StateInterface;

/**
 * Service class for integrating the rumenx/php-chatbot package with Drupal.
 *
 * Handles chatbot instantiation, chat history, conversation logging, and analytics.
 * This is the main service for AI Chatbot Plus.
 *
 * @category Service
 * @package  Ai_Chatbot_Plus
 * @author   Rumen Damyanov <contact@rumenx.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.drupal.org/project/ai_chatbot_plus
 */
class AiChatbotPlusService
{

    /**
     * The API key for the chatbot service.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Model parameters for the chatbot (e.g., temperature, max_tokens).
     *
     * @var array
     */
    protected $modelParameters;

    /**
     * The currently active model ID.
     *
     * @var string|null
     */
    protected $activeModel;

    /**
     * List of available models and their configuration.
     *
     * @var array
     */
    protected $models;

    /**
     * Drupal state service for persistent storage.
     *
     * @var \Drupal\Core\State\StateInterface|object
     */
    protected $state;

    /**
     * AiChatbotPlusService constructor.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The config factory service.
     * @param \Drupal\Core\State\StateInterface $state
     *   The Drupal state service.
     */
    public function __construct(ConfigFactoryInterface $config_factory, StateInterface $state)
    {
        $config = $config_factory->get('ai_chatbot_plus.settings');
        $this->apiKey = '';
        $this->models = $config->get('models') ?? [];
        $this->activeModel = $config->get('active_model') ?? null;
        $this->modelParameters = $config->get('model_parameters') ?? [];
        // Optionally set apiKey from first model if available
        if (!empty($this->models) && !empty($this->models[0]['api_key'])) {
            $this->apiKey = $this->models[0]['api_key'];
        }
        $this->state = $state;
    }

    /**
     * Get a configured Chatbot instance for the current model and API key.
     *
     * @return \rumenx\php_chatbot\Chatbot
     *   The chatbot instance.
     */
    public function getChatbotInstance()
    {
        $apiKey = $this->apiKey;
        // If an active model is set and models are available, use the model's API key if present.
        if ($this->activeModel && !empty($this->models)) {
            foreach ($this->models as $model) {
                if ($model['id'] === $this->activeModel && !empty($model['api_key'])) {
                    $apiKey = $model['api_key'];
                    break;
                }
            }
        }
        $chatbot = new Chatbot($apiKey);
        // Apply model parameters if available.
        if (!empty($this->modelParameters)) {
            foreach ($this->modelParameters as $param => $value) {
                if (method_exists($chatbot, 'set' . ucfirst($param))) {
                    $chatbot->{'set' . ucfirst($param)}($value);
                }
            }
        }
        return $chatbot;
    }

    /**
     * Retrieve chat history for a user or session.
     *
     * @param int|null $uid
     *   The user ID (optional).
     * @param string|null $session_id
     *   The session ID (optional).
     *
     * @return array
     *   The chat history messages.
     */
    public function getChatHistory($uid = null, $session_id = null)
    {
        if ($uid) {
            $history = $this->state->get('ai_chatbot_plus.chat_history.' . $uid, []);
        } else {
            $history = $this->state->get('ai_chatbot_plus.chat_history.session.' . $session_id, []);
        }
        return $history;
    }

    /**
     * Save chat history for a user or session.
     *
     * @param array $messages
     *   The chat messages to save.
     * @param int|null $uid
     *   The user ID (optional).
     * @param string|null $session_id
     *   The session ID (optional).
     *
     * @return void
     */
    public function saveChatHistory($messages, $uid = null, $session_id = null)
    {
        if ($uid) {
            $this->state->set('ai_chatbot_plus.chat_history.' . $uid, $messages);
        } else {
            $this->state->set('ai_chatbot_plus.chat_history.session.' . $session_id, $messages);
        }
    }

    /**
     * Log a conversation message for analytics.
     *
     * @param int|null $uid
     *   The user ID (optional).
     * @param string|null $session_id
     *   The session ID (optional).
     * @param string $message
     *   The message content.
     * @param string $role
     *   The role of the message sender (default: 'user').
     *
     * @return void
     */
    public function logConversation($uid, $session_id, $message, $role = 'user')
    {
        $log_entry = [
            'uid' => $uid,
            'session_id' => $session_id,
            'role' => $role,
            'message' => $message,
            'timestamp' => time(),
        ];
        $existing = $this->state->get('ai_chatbot_plus.conversation_log', []);
        $existing[] = $log_entry;
        $this->state->set('ai_chatbot_plus.conversation_log', $existing);
    }

    /**
     * Retrieve the conversation log for analytics.
     *
     * @param int $limit
     *   The maximum number of log entries to return (default: 100).
     *
     * @return array
     *   The conversation log entries, most recent first.
     */
    public function getConversationLog($limit = 100)
    {
        $log = $this->state->get('ai_chatbot_plus.conversation_log', []);
        return array_slice(array_reverse($log), 0, $limit);
    }
}
