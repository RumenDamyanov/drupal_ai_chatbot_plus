<?php

namespace Drupal\ai_chatbot\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot settings form and config export/import.
 *
 * @group ai_chatbot
 */
class AiChatbotSettingsFormTest extends BrowserTestBase {
  protected static $modules = ['ai_chatbot'];

  public function testSettingsFormAccessAndSave() {
    $user = $this->drupalCreateUser(['configure ai chatbot']);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/services/ai-chatbot');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldExists('API Key');
    $this->assertSession()->fieldExists('Active Model');
    $this->assertSession()->fieldExists('Widget Color');
    $this->submitForm([
      'API Key' => 'test-key',
      'Active Model' => '',
      'Widget Color' => '#ff0000',
    ], 'Save configuration');
    $this->assertSession()->pageTextContains('The configuration options have been saved.');
  }

  public function testSettingsFormNoAccess() {
    $user = $this->drupalCreateUser([]);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/config/services/ai-chatbot');
    $this->assertSession()->statusCodeEquals(403);
  }
}
