<? namespace Craft;

class Mailchimp_InterestFieldType extends BaseFieldType
{
  public function getName()
  {
    return Craft::t('MailChimp Interests');
  }
  protected function defineSettings()
  {
    return [
      'listId' => [AttributeType::String]
    ];
  }

  public function defineContentAttribute()
  {
    return AttributeType::Mixed;
  }

  public function getSettingsHtml()
  {
    return craft()->templates->render('mailchimp/fields/settings/interestCategories', [
      'settings' => $this->getSettings(),
      'lists' => craft()->mailchimp_list->getLists()
    ]);
  }

  public function getInputHtml($name, $value)
  {
    $categories = craft()->mailchimp_list->getListInterestCategories($this->getSettings()->listId);
    $interests = [];

    foreach ($categories as $category)
    {
      $interests = array_merge($interests, craft()->mailchimp_list->getListInterestsByInterestCategory($this->getSettings()->listId, $category['id']));
    }

    craft()->templates->includeCssResource('mailchimp/css/mailchimp.css');

    return craft()->templates->render('mailchimp/fields/interestCategories', [
      'name'  => $name,
      'values' => $value,
      'settings' => $this->getSettings(),
      'categories' => $categories,
      'interests' => $interests
    ]);
  }
}
