<?php

namespace Drupal\ai_chatbot_plus\Tests;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional test for AI Chatbot Plus block visibility and permissions.
 *
 * @group ai_chatbot_plus
 */
class AiChatbotPlusBlockTest extends BrowserTestBase {
  /**
   * {@inheritdoc}
   */
  protected static $modules = ['block', 'ai_chatbot_plus'];

  /**
   * Test block visibility for users with and without permission.
   */
  public function testBlockVisibility() {
    // Create user with permission.
    $user = $this->drupalCreateUser(['access ai chatbot plus']);
    $this->drupalLogin($user);
    $this->drupalPlaceBlock('ai_chatbot_plus_block');
    $this->drupalGet('<front>');
    $this->assertSession()->elementExists('css', '.ai-chatbot-widget');

    // Create user without permission.
    $user2 = $this->drupalCreateUser([]);
    $this->drupalLogin($user2);
    $this->drupalGet('<front>');
    $this->assertSession()->elementNotExists('css', '.ai-chatbot-widget');
  }
}
