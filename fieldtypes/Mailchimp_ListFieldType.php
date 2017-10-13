<? namespace Craft;

class Mailchimp_ListFieldType extends BaseFieldType
{
  public function getName()
  {
    return Craft::t('MailChimp List');
  }

  public function getInputHtml($name, $value)
  {
    return craft()->templates->render('mailchimp/fields/lists', [
      'name'  => $name,
      'value' => $value,
      'lists' => craft()->mailchimp_list->getLists(),
      'settings' => $this->getSettings()
    ]);
  }
}
