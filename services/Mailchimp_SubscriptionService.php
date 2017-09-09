<? namespace Craft;

class Mailchimp_SubscriptionService extends BaseApplicationComponent
{
  /**
   * Shortcut method for getting a new Guzzle instance
   *
   * @return String $url
   */
  private function getClient()
  {
    return new \Guzzle\Http\Client();
  }

  /**
   * Send a POST request to the appropriate Mailchimp endpoint
   *
   * @return String $url
   */
  public function addSubscriber($email, $listId = null, $options = [])
  {
    if ($listId === null) $listId = craft()->config->get('defaultListId', 'mailchimp');

    try
    {
      $url = craft()->config->get('apiBaseUrl', 'mailchimp') . join([
        'lists',
        $listId,
        'members',
        md5(strtolower($email))
      ], '/');

      $params = array_merge([
        'email_address' => $email,
        'email_type' => 'html',
        'status_if_new' => 'pending'
      ], $options);

      $request = $this->getClient()->put($url);
      $request->setAuth('mcapi', craft()->config->get('apiKey', 'mailchimp'));
      $request->setBody(json_encode($params), 'application/json');

      $response = $request->send();

      return $response->json();
    }
    catch (\Guzzle\Http\Exception\BadResponseException $e)
    {
      return false;
    }
    catch (\Exception $e)
    {
      return false;
    }
  }
}
