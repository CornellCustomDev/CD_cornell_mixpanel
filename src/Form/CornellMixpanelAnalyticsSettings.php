<?php

namespace Drupal\cornell_mixpanel\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for this site.
 */
class CornellMixpanelAnalyticsSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cornell_mixpanel_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cornell_mixpanel.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('cornell_mixpanel.settings');

    $form['mixpanel_proxy_info'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Proxy settings'),
      '#description' => $this->t('These settings are for sending mixpanel requests through our own proxy.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['mixpanel_proxy_info']['cornell_mixpanel_prod_proxy_domain'] = [
      '#title' => $this->t('Mixpanel Proxy Domain'),
      '#type' => 'textfield',
      '#description' => $this->t('The full proxy server domain or IP address tat will proxy mixpanel events.'),
      '#default_value' => $config->get('cornell_mixpanel_prod_proxy_domain'),
    ];

    $form['mixpanel_tokens'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mixpanel Tokens'),
      '#description' => $this->t('Stored Tokens for mixpanel envs to use.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['mixpanel_tokens']['cornell_mixpanel_test_token'] = [
      '#title' => $this->t('Mixpanel DEVELOPMENT Token'),
      '#type' => 'textfield',
      '#description' => $this->t('The token from mixpanel.com for this DEVELOPMENT (Non-Live) project. REQUIRED for Mixpanel events to be sent.'),
      '#default_value' => $config->get('cornell_mixpanel_test_token'),
    ];
    $form['mixpanel_tokens']['cornell_mixpanel_token'] = [
      '#title' => $this->t('Mixpanel PRODUCTION Token'),
      '#type' => 'textfield',
      '#description' => $this->t('The token from mixpanel.com for this PRODUCTION (Live) project. REQUIRED for Mixpanel events to be sent.'),
      '#default_value' => $config->get('cornell_mixpanel_token'),
    ];

    $form['other'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mixpanel Other settings'),
      '#description' => $this->t('Various config settings in addition to Production and Test.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['other']['cornell_mixpanel_debug_mode'] = [
      '#title' => $this->t('Set Mixpanel debug mode on'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this will log mixpanel data to the console. <em>LIVE env will always be disabled.</em> <strong>Only use for debugging!</strong>'),
      '#default_value' => $config->get('cornell_mixpanel_debug_mode'),
    ];

    $form['other']['cornell_mixpanel_ignore_dnt'] = [
      '#title' => $this->t('Ignore DO NOT TRACK request from browsers.'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this will ignore the "Do Not Track" setting in client browsers. <em>LIVE env will always be disabled.</em> <strong>Only use for debugging!</strong>'),
      '#default_value' => $config->get('cornell_mixpanel_ignore_dnt'),
    ];

    $form['other']['cornell_mixpanel_cross_subdomain_cookie'] = [
      '#title' => $this->t('Set Mixpanel cookie across all subdomains'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this use the same Mixpanel cookie for <em>site1</em>.example.com and <em>site2</em>.example.com.'),
      '#default_value' => $config->get('cornell_mixpanel_cross_subdomain_cookie'),
    ];

    $form['drupal_specific'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Drupal specific settings'),
      '#description' => $this->t('These settings are specific to the Drupal site.'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
    ];
    $form['drupal_specific']['cornell_mixpanel_domains_to_track'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Production Domains to track'),
      '#description' => $this->t('Enter the domains to track, separated by commas. For example: <em>example.com, sub.example.com</em>. You must specify at least one domain to use mixpanel tracking.'),
      '#rows' => 5,
      '#default_value' => $config->get('cornell_mixpanel_domains_to_track'),
    ];

    $form['drupal_specific']['cornell_mixpanel_non_production_domains'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Non-Production Domains - to track with Test Token'),
      '#description' => $this->t('Enter the non-production domains, separated by commas. For example: <em>example.com, sub.example.com</em>. You must specify at least one domain to use mixpanel tracking.'),
      '#rows' => 5,
      '#default_value' => $config->get('cornell_mixpanel_non_production_domains'),
    ];

    $form['drupal_specific']['cornell_mixpanel_track_only_anonymous'] = [
      '#title' => $this->t('Track only anonymous users'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this will track only anonymous users.'),
      '#default_value' => $config->get('cornell_mixpanel_track_only_anonymous'),
    ];
    $form['drupal_specific']['cornell_mixpanel_ignore_admin_routes'] = [
      '#title' => $this->t('Ignore admin paths'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this will ignore admin paths.'),
      '#default_value' => $config->get('cornell_mixpanel_ignore_admin_routes'),
    ];
    $form['drupal_specific']['cornell_mixpanel_use_heatmap'] = [
      '#title' => $this->t('Use Heatmap'),
      '#type' => 'checkbox',
      '#description' => $this->t('Enabling this will allow the use of heatmaps in Mixpanel.'),
      '#default_value' => $config->get('cornell_mixpanel_use_heatmap'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration
    $this->configFactory->getEditable('cornell_mixpanel.settings')
      // Set the submitted configuration setting
      ->set('cornell_mixpanel_token', $form_state->getValue('cornell_mixpanel_token'))
      ->set('cornell_mixpanel_prod_proxy_domain', $form_state->getValue('cornell_mixpanel_prod_proxy_domain'))
      ->set('cornell_mixpanel_test_token', $form_state->getValue('cornell_mixpanel_test_token'))
      ->set('cornell_mixpanel_debug_mode', $form_state->getValue('cornell_mixpanel_debug_mode'))
      ->set('cornell_mixpanel_ignore_dnt', $form_state->getValue('cornell_mixpanel_ignore_dnt'))
      ->set('cornell_mixpanel_cross_subdomain_cookie', $form_state->getValue('cornell_mixpanel_cross_subdomain_cookie'))
      ->set('cornell_mixpanel_track_only_anonymous', $form_state->getValue('cornell_mixpanel_track_only_anonymous'))
      ->set('cornell_mixpanel_ignore_admin_routes', $form_state->getValue('cornell_mixpanel_ignore_admin_routes'))
      ->set('cornell_mixpanel_domains_to_track', $form_state->getValue('cornell_mixpanel_domains_to_track'))
      ->set('cornell_mixpanel_non_production_domains', $form_state->getValue('cornell_mixpanel_non_production_domains'))
      ->set('cornell_mixpanel_use_heatmap', $form_state->getValue('cornell_mixpanel_use_heatmap'))
      // Save the configuration
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Validation function for the admin settings form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (empty($form_state->getValue('cornell_mixpanel_token'))) {
      $form_state->setErrorByName('cornell_mixpanel_token', $this->t('You must enter a Production mixpanel token.'));
    }
    if (empty($form_state->getValue('cornell_mixpanel_test_token'))) {
      $form_state->setErrorByName('cornell_mixpanel_test_token', $this->t('You must enter a Test mixpanel token.'));
    }
  }

}
