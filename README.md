# [AI Chatbot Plus Drupal Module](https://www.drupal.org/project/ai_chatbot_plus)

AI Chatbot Plus is a robust, configurable Drupal 11 module that seamlessly integrates the [php-chatbot](https://github.com/RumenDamyanov/php-chatbot) PHP package, bringing advanced conversational AI to your Drupal site. It features a service-based architecture, flexible admin configuration, analytics, theming, and full Composer support. The module is designed for extensibility, accessibility, and easy theming, making it suitable for both site builders and developers.

## Features

- Configurable admin settings page for API keys (database or environment variable)
- Configuration export/import and multilingual support
- Custom block for layout builder (visibility by page, language, etc.)
- Twig-based templates for block and page, with theme suggestions
- Popup integration (programmatic and via block)
- Service-based architecture for chatbot logic and custom integrations
- Example controller and route for chatbot page
- Analytics logging and admin reporting of chatbot conversations
- Granular permissions for access, analytics, and configuration
- Accessibility and theming support (CSS, JS, ARIA)
- High unit and functional test coverage, Composer/QA ready

## Installation

**Recommended (Composer):**

1. Run `composer require drupal/ai_chatbot_plus` in your Drupal root.
2. Enable the module via the Drupal admin or Drush.

**Manual installation:**

1. Download or clone this module into `modules/custom/ai_chatbot_plus`.
2. Enable the module via the Drupal admin or Drush.

## Configuration

- Go to **Configuration → Web services → AI Chatbot Plus** to set your API key or choose to use an environment variable.
- Configuration is exportable via Drupal's config system.

## Block Usage

- Add the **AI Chatbot Plus Block** via the Block Layout admin.
- Configure block visibility by page, language, or other conditions using standard Drupal block settings.

## Popup Integration

- The chatbot popup can be added programmatically to any route or attached to views using Drupal behaviors in `js/ai_chatbot_plus.js`.
- Example: Use `Drupal.behaviors.aiChatbotPlus` to trigger the popup on specific pages.

## Theming & Customization

- Twig template suggestions for advanced theming:
  - `ai-chatbot-plus-block--[block-id].html.twig`, `ai-chatbot-plus-block--[region].html.twig`, `ai-chatbot-plus-block.html.twig`
  - `ai-chatbot-plus-page--[route-name].html.twig`, `ai-chatbot-plus-page.html.twig`
- Easily override templates in your theme for custom UI.
- Customize CSS in `css/ai_chatbot_plus.css`.

## Extending

- Use the `ai_chatbot_plus.service` for custom integrations.
- Add new settings to `config/schema/ai_chatbot_plus.schema.yml` and the settings form.

## Analytics

- Visit Configuration → Reports → AI Chatbot Plus Analytics to view a log of all chatbot conversations (timestamp, user/session, role, message).
- Logs are stored in Drupal state and can be extended for more advanced analytics or export.

## License

This project is licensed under the [MIT License](LICENSE.md).
