<? namespace Craft;

class MailchimpPlugin extends BasePlugin
{
  public function getName()
  {
    return Craft::t('Mailchimp Subscriptions');
  }

  public function getVersion()
  {
    return '0.0.1';
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
}
