<? namespace Craft;

class Mailchimp_ListService extends BaseApplicationComponent
{
  /**
   * Add a subscriber to the provided list, with additional metadata:
   *
   * @return Array $subscriber
   */
  public function addMember($email, $listId = null, $options = [])
  {
    if ($listId === null) $listId = craft()->config->get('defaultListId', 'mailchimp');

    $path = join([
      'lists',
      $listId,
      'members',
      md5(strtolower($email))
    ], '/');

    $body = array_merge([
      'email_address' => $email,
      'email_type' => 'html',
      'status_if_new' => 'pending'
    ], $options);

    return craft()->mailchimp_request->requestPut($path, $body);
  }

  public function getLists()
  {
    $lists = craft()->mailchimp_request->requestGet('lists', []);

    if (isset($lists['lists']))
    {
      return $lists['lists'];
    }
    else
    {
      return [];
    }
  }
}
