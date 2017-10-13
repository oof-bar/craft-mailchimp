<? namespace Craft;

class Mailchimp_RequestService extends BaseApplicationComponent
{
  /**
   * Shortcut method for getting a new Guzzle instance
   *
   * @return \Guzzle\Http\Client $client
   */
  private function getClient()
  {
    return new \Guzzle\Http\Client();
  }

  /**
   * Make a GET Request
   *
   * @return Array $data
   */
  public function requestGet($route, $data, $cache = false)
  {
    return $this->requestWithQuery('get', $route, $data, $cache);
  }

  /**
   * Make a POST Request
   *
   * @return Array $data
   */
  public function requestPost($route, $data)
  {
    return $this->requestWithBody('post', $route, $data);
  }

  /**
   * Make a PUT Request
   *
   * @return Array $data
   */
  public function requestPut($route, $data)
  {
    return $this->requestWithBody('put', $route, $data);
  }

  /**
   * Abstract method for requests with a body (POST/PUT/PATCH)
   *
   * @return Array $data
   */
  public function requestWithBody($method, $route, $data, $cache = false)
  {
    try
    {
      $url = craft()->config->get('apiBaseUrl', 'mailchimp') . $route;

      $request = $this->getClient()->$method($url);

      $request->setAuth('mcapi', craft()->config->get('apiKey', 'mailchimp'));
      $request->setBody(json_encode($data), 'application/json');

      $response = $request->send();

      return $response->json();
    }
    catch (\Exception $e)
    {
      return false;
    }
  }

  /**
   * Abstract method for requests with query params (GET)
   *
   * @return Array $data
   */
  public function requestWithQuery($method, $route, $data, $cache = false)
  {
    try
    {
      $url = craft()->config->get('apiBaseUrl', 'mailchimp') . $route;

      $request = $this->getClient()->$method($url);

      $request->setAuth('mcapi', craft()->config->get('apiKey', 'mailchimp'));
      $query = $request->getQuery();

      foreach ($data as $key => $val) $query->set($key, $val);

      $response = $request->send();

      return $response->json();
    }
    catch (\Exception $e)
    {
      return false;
    }
  }
}
