<?php

namespace Drupal\ai_chatbot\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot block visibility and permissions.
 *
 * @group ai_chatbot
 */
class AiChatbotBlockTest extends BrowserTestBase {
  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'ai_chatbot'];

  /**
   * Test block visibility for users with and without permission.
   */
  public function testBlockVisibility() {
    // Create user with permission.
    $user = $this->drupalCreateUser(['access ai chatbot']);
    $this->drupalLogin($user);
    $this->drupalPlaceBlock('ai_chatbot_block');
    $this->drupalGet('<front>');
    $this->assertSession()->elementExists('css', '.ai-chatbot-widget');

    // Create user without permission.
    $user2 = $this->drupalCreateUser([]);
    $this->drupalLogin($user2);
    $this->drupalGet('<front>');
    $this->assertSession()->elementNotExists('css', '.ai-chatbot-widget');
  }
}
