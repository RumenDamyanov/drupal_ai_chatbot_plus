<?php

namespace Drupal\ai_chatbot_plus\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot Plus page access and permissions.
 *
 * @group ai_chatbot_plus
 */
class AiChatbotPlusPageTest extends BrowserTestBase {
  protected static $modules = ['ai_chatbot_plus'];

  public function testPageAccess() {
    // User with permission.
    $user = $this->drupalCreateUser(['access ai chatbot plus']);
    $this->drupalLogin($user);
    $this->drupalGet('/ai-chatbot');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->elementExists('css', '.ai-chatbot-widget');

    // User without permission.
    $user2 = $this->drupalCreateUser([]);
    $this->drupalLogin($user2);
    $this->drupalGet('/ai-chatbot');
    $this->assertSession()->statusCodeEquals(403);
  }
}
