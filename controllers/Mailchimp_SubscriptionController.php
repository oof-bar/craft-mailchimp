<? namespace Craft;

class Mailchimp_SubscriptionController extends BaseController
{
  protected $allowAnonymous = ['actionAddSubscriber'];

  public function actionAddSubscriber()
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

    $response = craft()->mailchimp_subscription->addSubscriber($params['email'], $params['listId']);

    // SubscriptionService::addSubscriber will return false if anything went wrong.
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
