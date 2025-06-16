<?php

namespace Drupal\ai_chatbot_plus\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot Plus analytics page and permissions.
 *
 * @group ai_chatbot_plus
 */
class AiChatbotPlusAnalyticsTest extends BrowserTestBase {
  protected static $modules = ['ai_chatbot_plus'];

  public function testAnalyticsAccess() {
    // User with permission.
    $user = $this->drupalCreateUser(['view ai chatbot plus analytics']);
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
