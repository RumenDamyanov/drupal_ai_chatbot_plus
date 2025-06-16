<?php

namespace Drupal\ai_chatbot_plus\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Settings form for AI Chatbot Plus configuration.
 *
 * @category Form
 * @package  Ai_Chatbot_Plus
 * @author   Rumen Damyanov <contact@rumenx.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.drupal.org/project/ai_chatbot_plus
 */
class AiChatbotPlusSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   *
   * @return string
   *   The form ID.
   */
  public function getFormId() {
    return 'ai_chatbot_plus_settings_form';
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   Editable config names.
   */
  protected function getEditableConfigNames() {
    return ['ai_chatbot_plus.settings'];
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return array
   *   The built form array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('ai_chatbot_plus.settings');

    $form['use_env'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use environment variable for API keys'),
      '#default_value' => $config->get('use_env'),
    ];

    $models = $config->get('models') ?: [];
    $form['models'] = [
      '#type' => 'table',
      '#title' => $this->t('Available Models'),
      '#header' => [
        $this->t('Model ID'),
        $this->t('Label'),
        $this->t('API Key'),
      ],
      '#empty' => $this->t('No models added yet.'),
    ];
    foreach ($models as $delta => $model) {
      $form['models'][$delta]['id'] = [
        '#type' => 'textfield',
        '#default_value' => $model['id'] ?? '',
        '#size' => 20,
        '#required' => TRUE,
      ];
      $form['models'][$delta]['label'] = [
        '#type' => 'textfield',
        '#default_value' => $model['label'] ?? '',
        '#size' => 20,
        '#required' => TRUE,
      ];
      $form['models'][$delta]['api_key'] = [
        '#type' => 'textfield',
        '#default_value' => $model['api_key'] ?? '',
        '#size' => 30,
        '#states' => [
          'visible' => [
            ':input[name="use_env"]' => ['checked' => FALSE],
          ],
        ],
      ];
    }
    $form['add_model'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Model'),
      '#submit' => ['::addModelSubmit'],
      '#limit_validation_errors' => [],
    ];
    $options = [];
    foreach ($models as $model) {
      if (!empty($model['id']) && !empty($model['label'])) {
        $options[$model['id']] = $model['label'];
      }
    }
    $form['active_model'] = [
      '#type' => 'select',
      '#title' => $this->t('Active Model'),
      '#options' => $options,
      '#default_value' => $config->get('active_model'),
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
    ];
    $form['other_options'] = [
      '#type' => 'details',
      '#title' => $this->t('Other Options'),
      '#open' => TRUE,
    ];
    $form['other_options']['enable_logging'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable logging'),
      '#default_value' => $config->get('other_options.enable_logging'),
    ];
    $form['other_options']['show_popup_on_all_pages'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show popup on all pages by default'),
      '#default_value' => $config->get('other_options.show_popup_on_all_pages'),
    ];
    $form['model_parameters'] = [
      '#type' => 'details',
      '#title' => $this->t('Model Parameters'),
      '#open' => TRUE,
    ];
    $form['model_parameters']['temperature'] = [
      '#type' => 'number',
      '#title' => $this->t('Temperature'),
      '#default_value' => $config->get('model_parameters.temperature') ?? 1.0,
      '#step' => 0.01,
      '#min' => 0,
      '#max' => 2,
    ];
    $form['model_parameters']['max_tokens'] = [
      '#type' => 'number',
      '#title' => $this->t('Max Tokens'),
      '#default_value' => $config->get('model_parameters.max_tokens') ?? 2048,
      '#min' => 1,
      '#max' => 4096,
    ];
    $form['model_parameters']['system_prompt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('System Prompt'),
      '#default_value' => $config->get('model_parameters.system_prompt') ?? '',
      '#size' => 80,
      '#translatable' => TRUE,
    ];
    $form['widget_customization'] = [
      '#type' => 'details',
      '#title' => $this->t('Widget Customization'),
      '#open' => TRUE,
    ];
    $form['widget_customization']['color'] = [
      '#type' => 'color',
      '#title' => $this->t('Widget Color'),
      '#default_value' => $config->get('widget_customization.color') ?? '#0074d9',
    ];
    $form['widget_customization']['avatar'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Avatar Image URL'),
      '#default_value' => $config->get('widget_customization.avatar') ?? '',
      '#description' => $this->t('URL to an image to use as the chatbot avatar.'),
    ];
    $form['widget_customization']['welcome_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Welcome Message'),
      '#default_value' => $config->get('widget_customization.welcome_message') ?? $this->t('Hi! How can I help you?'),
      '#size' => 80,
      '#translatable' => TRUE,
    ];
    $form['widget_customization']['display_mode'] = [
      '#type' => 'select',
      '#title' => $this->t('Display Mode'),
      '#options' => [
        'inline' => $this->t('Inline'),
        'floating' => $this->t('Floating'),
        'modal' => $this->t('Modal/Popup'),
      ],
      '#default_value' => $config->get('widget_customization.display_mode') ?? 'floating',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $models = [];
    if ($form_state->hasValue('models')) {
      foreach ($form_state->getValue('models') as $row) {
        if (!empty($row['id']) && !empty($row['label'])) {
          $models[] = [
            'id' => $row['id'],
            'label' => $row['label'],
            'api_key' => $row['api_key'],
          ];
        }
      }
    }
    $this->config('ai_chatbot_plus.settings')
      ->set('models', $models)
      ->set('active_model', $form_state->getValue('active_model'))
      ->set('use_env', $form_state->getValue('use_env'))
      ->set('other_options', [
        'enable_logging' => $form_state->getValue(['other_options', 'enable_logging']),
        'show_popup_on_all_pages' => $form_state->getValue(['other_options', 'show_popup_on_all_pages']),
      ])
      ->set('model_parameters', [
        'temperature' => $form_state->getValue(['model_parameters', 'temperature']),
        'max_tokens' => $form_state->getValue(['model_parameters', 'max_tokens']),
        'system_prompt' => $form_state->getValue(['model_parameters', 'system_prompt']),
      ])
      ->set('widget_customization', [
        'color' => $form_state->getValue(['widget_customization', 'color']),
        'avatar' => $form_state->getValue(['widget_customization', 'avatar']),
        'welcome_message' => $form_state->getValue(['widget_customization', 'welcome_message']),
        'display_mode' => $form_state->getValue(['widget_customization', 'display_mode']),
      ])
      ->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Handles adding a new model row to the models table.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  public function addModelSubmit(array &$form, FormStateInterface $form_state) {
    $models = $form_state->getValue('models') ?: [];
    $models[] = ['id' => '', 'label' => '', 'api_key' => ''];
    $form_state->setValue('models', $models);
    $form_state->setRebuild();
  }

}
