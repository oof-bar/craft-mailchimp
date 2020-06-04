<?php namespace Craft;

class Mailchimp_ListController extends BaseController
{
  protected $allowAnonymous = ['actionAddMember'];

  public function actionAddMember()
  {
    $this->requirePostRequest();

    $params = array_merge([
      'email' => null,
      'listId' => craft()->config->get('defaultListId', 'mailchimp')
    ], craft()->request->getPost(null));

    if (!filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
      $this->returnJson([
        'error' => craft()->config->get('messageInvalidEmail', 'mailchimp')
      ]);
    }

    $response = craft()->mailchimp_list->addMember($params['email'], $params['listId']);

    // ListService::addMember will return false if anything went wrong.
    if ($response) {
      $this->returnJson([
        'success' => true,
        'message' => craft()->config->get('messageSuccess', 'mailchimp')
      ]);
    } else {
      $this->returnErrorJson(craft()->config->get('messageFailure', 'mailchimp'));
    }
  }
}
