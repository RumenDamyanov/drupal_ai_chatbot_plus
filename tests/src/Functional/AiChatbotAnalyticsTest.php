<?php

namespace Drupal\ai_chatbot\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot analytics page and permissions.
 *
 * @group ai_chatbot
 */
class AiChatbotAnalyticsTest extends BrowserTestBase {
  protected static $modules = ['ai_chatbot'];

  public function testAnalyticsAccess() {
    // User with permission.
    $user = $this->drupalCreateUser(['view ai chatbot analytics']);
    $this->drupalLogin($user);
    $this->drupalGet('/admin/reports/ai-chatbot-analytics');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('AI Chatbot Conversation Log');

    // User without permission.
    $user2 = $this->drupalCreateUser([]);
    $this->drupalLogin($user2);
    $this->drupalGet('/admin/reports/ai-chatbot-analytics');
    $this->assertSession()->statusCodeEquals(403);
  }
}
