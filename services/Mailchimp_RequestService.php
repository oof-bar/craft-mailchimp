<?php namespace Craft;

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
      $request = $this->getClient()->$method($this->getUrl($route));

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
      $cacheCriteria = compact('method', 'route', 'data');
      $cacheKey = craft()->mailchimp_cache->getCacheKey($cacheCriteria);

      if ($cache && $cachedValue = craft()->mailchimp_cache->getCachedValue($cacheCriteria))
      {
        return $cachedValue;
      }
      else
      {
        $request = $this->getClient()->$method($this->getUrl($route));

        $request->setAuth('mcapi', craft()->config->get('apiKey', 'mailchimp'));
        $query = $request->getQuery();

        foreach ($data as $key => $val) $query->set($key, $val);

        $response = $request->send();
        $data = $response->json();

        if ($cache)
        {
          craft()->mailchimp_cache->addCachedValue($cacheCriteria, $data);
        }

        return $data;
      }
    }
    catch (\Exception $e)
    {
      return false;
    }
  }

  /**
   * Get base URL for all API calls
   *
   * @return String $baseUrl
   */
  public function getBaseUrl()
  {
    return 'https://' . craft()->config->get('dc', 'mailchimp') . '.api.mailchimp.com/3.0/';
  }

  /**
   * Get fully-qualified URL for a request
   *
   * @return String $baseUrl
   */
  public function getUrl($path)
  {
    return $this->getBaseUrl() . $path;
  }
}
