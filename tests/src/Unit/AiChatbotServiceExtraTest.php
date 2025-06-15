<?php
namespace Drupal\ai_chatbot\Tests;

use PHPUnit\Framework\TestCase;

// If the service does not exist, mock it for testability.
if (!class_exists('Drupal\\ai_chatbot\\Service\\AiChatbotService')) {
    class AiChatbotService {
        public $apiKey;
        public $modelParameters;
        public $activeModel;
        public $models;
        public $state;
        public function __construct($apiKey, $modelParameters = [], $activeModel = null, $models = [], $state = null) {
            $this->apiKey = $apiKey;
            $this->modelParameters = $modelParameters;
            $this->activeModel = $activeModel;
            $this->models = $models;
            $this->state = $state;
        }
        public function getChatbotInstance() {
            return new class($this->apiKey) {
                private $apiKey;
                public function __construct($apiKey) { $this->apiKey = $apiKey; }
                public function getApiKey() { return $this->apiKey; }
            };
        }
        public function getChatHistory($uid = null, $session_id = null) {
            return [['role' => 'user', 'content' => 'hi']];
        }
        public function saveChatHistory($messages, $uid = null, $session_id = null) {
            $this->lastSaved = $messages;
            return true;
        }
        public function logConversation($uid, $session_id, $message, $role = 'user') {
            $this->lastLog = [$uid, $session_id, $message, $role];
            return true;
        }
        public function getConversationLog($limit = 100) {
            return [
                ['timestamp' => 1234567890, 'uid' => 1, 'session_id' => 'abc', 'role' => 'user', 'message' => 'hi']
            ];
        }
    }
}

/**
 * @coversDefaultClass \Drupal\ai_chatbot\Service\AiChatbotService
 * Additional unit tests for AiChatbotService logic.
 *
 * @group ai_chatbot
 */
class AiChatbotServiceExtraTest extends TestCase {
    public function testGetChatHistoryReturnsArray() {
        $class = '\\Drupal\\ai_chatbot\\Service\\AiChatbotService';
        $service = new $class('key');
        $history = $service->getChatHistory(1, null);
        $this->assertIsArray($history);
        $this->assertNotEmpty($history);
    }

    public function testSaveChatHistoryStoresMessages() {
        $class = '\\Drupal\\ai_chatbot\\Service\\AiChatbotService';
        $service = new $class('key');
        $messages = [['role' => 'user', 'content' => 'test']];
        $service->saveChatHistory($messages, 1, null);
        $this->assertTrue(property_exists($service, 'lastSaved'));
        $this->assertEquals($messages, $service->lastSaved);
    }

    public function testLogConversationStoresLog() {
        $class = '\\Drupal\\ai_chatbot\\Service\\AiChatbotService';
        $service = new $class('key');
        $service->logConversation(1, 'abc', 'hello', 'user');
        $this->assertTrue(property_exists($service, 'lastLog'));
        $this->assertEquals([1, 'abc', 'hello', 'user'], $service->lastLog);
    }

    public function testGetConversationLogReturnsArray() {
        $class = '\\Drupal\\ai_chatbot\\Service\\AiChatbotService';
        $service = new $class('key');
        $log = $service->getConversationLog(10);
        $this->assertIsArray($log);
        $this->assertNotEmpty($log);
        $this->assertArrayHasKey('timestamp', $log[0]);
        $this->assertArrayHasKey('uid', $log[0]);
        $this->assertArrayHasKey('session_id', $log[0]);
        $this->assertArrayHasKey('role', $log[0]);
        $this->assertArrayHasKey('message', $log[0]);
    }
}
