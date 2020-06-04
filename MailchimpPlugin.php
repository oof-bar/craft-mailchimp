<?php namespace Craft;

class MailchimpPlugin extends BasePlugin
{
  public function getName()
  {
    return Craft::t('MailChimp');
  }

  public function getVersion()
  {
    return '0.0.2';
  }

  public function getDeveloper()
  {
    return 'oof. Studio';
  }

  public function getDeveloperUrl()
  {
    return 'https://oof.studio/';
  }

  public function hasCpSection()
  {
    return false;
  }

  public function getSettingsUrl()
  {
    return 'mailchimp/settings';
  }
}
