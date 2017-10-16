# Craft + Mailchimp

We use this, internally, to build seamless Mailchimp signup experiences, via AJAX. Currently, there is no support for standard form submissions.

## Implementation

Here's an example of what your front-end code would look like, in response to a form submission:

```js
$('#my-subscription-form').on('submit', function (e) {
  e.preventDefault();

  $.ajax({
    // Without a `url`, this just POSTS back to current URL
    method: 'POST',
    // Provide an `action` param in the body:
    data: {
      action: 'mailchimp/list/addMember',
      email: $('#email').value(),
      listId: $('#listId').value() // This input should probably be of type `hidden`.
    },
    success: function (data) {
      if (data.success) {
        alert(data.message);
      } else {
        alert(data.error);
      }
    }
  });
});
```

You can also directly call the Subscription service, from your own plugin—say you wanted to hook into an event emitted from a form submission, or commerce order:

```php
// Only if the customer asked to be subscribed! This assumes you've added a custom order field and exposed it to your order form.
craft()->on('commerce_orders.onOrderComplete', function ($event) {
  $order = $event->params['order'];

  // Did they request to be added?
  if ($order->myNewsletterSubscribeField) {
    // Ok, add 'em!
    craft()->mailchimp_list->addMember($order->email);
  }
});
```

The service defaults to a `defaultListId` param in your config file—just create `craft/config/mailchimp.php`:

```
<? return [
  'defaultListId' => 'abcd1234',
  'apiKey' => 'us1-yourApiKey'
];
```

You can override the `defaultListId` with any call to the service:

```php
craft()->mailchimp_list->addMember($order->email, 'abcd1234');
```

A third argument allows you to customize any additional params sent to the API, including `status_if_new`, in case you've decided to bypass double opt-in (strongly discouraged, but appropriate in some limited circumstances):

```php
craft()->mailchimp_list->addMember($order->email, 'abcd1234', [
  'statis_if_new' => 'subscribed'
]);
```

There are a few more configuration options that you should set:

```php
<? return [
  # The datacenter that your account is tied to:
  'dc' => 'us1',

  # Your API secret:
  'apiKey' => 'Your API Key',

  # The default list ID for new members:
  'defaultListId' => 'abcd1234',

  # Various user feedback messages:
  'messageSuccess' => 'Thanks! Please check your email to confirm your membership.',
  'messageFailure' => 'Sorry, something went wrong. Please check that you are not already subscribed, and try again.',
  'messageInvalidEmail' => 'Please provide a valid email address.'
];
```


## Installation

Add to your own project by downloading, renaming to `mailchimp`, and adding to your `craft/plugins` folder. Alternatively, you can use this repository as a submodule:

```
$ cd /path/to/your/project
$ git submodule add https://github.com/oof-bar/craft-mailchimp.git craft/plugins/mailchimp
```

:deciduous_tree:
