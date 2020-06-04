<?php namespace Craft;

class Mailchimp_SettingsController extends BaseController
{
  public function actionClearCache()
  {
    $this->requirePostRequest();
    $this->requireAdmin();

    $recordCount = craft()->mailchimp_cache->purgeCache();

    craft()->userSession->setNotice('The MailChimp cache as been purged.');

    $this->redirect('mailchimp/settings');
  }
}
