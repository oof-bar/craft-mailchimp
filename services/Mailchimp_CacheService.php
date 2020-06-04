<?php namespace Craft;

/**
 * Cacheing layer for Mailchimp interactions.
 *
 * @author    oof. Studio <hello@oof.studio>
 * @copyright oof. LLC
 * @see       https://oof.studio
 */

class Mailchimp_CacheService extends BaseApplicationComponent
{
  /**
   * Builds a cache key based on the passed criteria.
   *
   * @return String $key
   */
  public function getCacheKey($criteria)
  {
    return join(['mailchimp', md5(json_encode($criteria))], '_');
  }

  /**
   * Get a cached value by key. Proxy for Craft's CacheService.
   *
   * @return Mixed $data
   */
  public function getCachedValue($criteria)
  {
    return craft()->cache->get($this->getCacheKey($criteria));
  }

  /**
   * Set a cached value by criteria. Proxy for Craft's CacheService#add.
   *
   * @return Mixed $data
   */
  public function addCachedValue($criteria, $data)
  {
    $key = $this->getCacheKey($criteria);

    $success = craft()->cache->add($key, $data, craft()->config->get('cacheDuration', 'mailchimp'));

    if ($success) $this->addKeyToCacheManifest($key);

    return $success;
  }

  /**
   * Purge existing cache keys
   *
   * @return Mixed $data
   */
  public function purgeCache()
  {
    $manifest = craft()->cache->get($this->getCacheKey('manifest'));

    if (is_array($manifest)) {
      foreach ($manifest as $item) craft()->cache->delete($item);
    }

    craft()->cache->delete($this->getCacheKey('manifest'));

    return count($manifest);
  }

  /**
   * Add a key to the cache manifest
   *
   * @return Mixed $data
   */
  public function addKeyToCacheManifest($key)
  {
    $manifest = craft()->cache->get($this->getCacheKey('manifest'));

    $manifest[] = $key;

    craft()->cache->set($this->getCacheKey('manifest'), $manifest);
  }

  /**
   * Get a list of existing cache keys
   *
   * @return Mixed $data
   */
  public function getCacheManifest()
  {
    return craft()->cache->get($this->getCacheKey('manifest'));
  }
}
