<?php namespace Craft;

class Mailchimp_ListService extends BaseApplicationComponent
{
  /**
   * Add a subscriber to the provided list, with additional metadata.
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

  /**
   * Get all lists. Requires two queries, one to get `total_items`, and a second to fetch with a `count` that guarantees all lists are included.
   *
   * @return Array $lists
   */
  public function getLists()
  {
    $prefetch = craft()->mailchimp_request->requestGet('lists', [
      'fields' => 'total_items'
    ], true);

    $all = craft()->mailchimp_request->requestGet('lists', [
      'count' => $prefetch['total_items']
    ], true);

    if (isset($all['lists']))
    {
      return $all['lists'];
    }
    else
    {
      return [];
    }
  }

  /**
   * Get Interest Categories for a single list.
   *
   * @return Array $lists
   */
  public function getListInterestCategories($listId)
  {
    $categories = craft()->mailchimp_request->requestGet("lists/$listId/interest-categories", [], true);

    if (isset($categories['categories']))
    {
      return $categories['categories'];
    }
    else
    {
      return [];
    }
  }

  /**
   * Get Interests for an Interest Category within a single list.
   *
   * @return Array $lists
   */
  public function getListInterestsByInterestCategory($listId, $interestCategoryId)
  {
    $category = craft()->mailchimp_request->requestGet("lists/$listId/interest-categories/$interestCategoryId/interests", [], true);

    if (isset($category['interests']))
    {
      return $category['interests'];
    }
    else
    {
      return [];
    }
  }
}
