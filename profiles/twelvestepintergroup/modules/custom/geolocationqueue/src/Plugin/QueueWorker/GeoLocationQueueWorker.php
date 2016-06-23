<?php

namespace Drupal\geolocationqueue\Plugin\QueueWorker;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @QueueWorker(
 *   id = "geolocation",
 *   title = @Translation("Geo Location Field Updater"),
 *   cron = {"time" = 10}
 * )
 */
class GeoLocationQueueWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {


  /**
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, Client $client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->httpClient = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition,
      $container->get('http_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    // Load the entity.
    if (!$entity = entity_load($data->entity_type, $data->entity_id)) {
      // @todo: throw exception?
      return;
    }
    
    // Get the entities address field.
    if (!$address = $entity->get($data->field_address)->first()) {
      // @todo: throw exception?
      return;
    }
    
    // Get the address's geo coordinates from Google APIs.
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode(implode(', ', [
      $address->address_line1,
      $address->address_line2,
      $address->locality,
      $address->administrative_area,
      $address->postal_code,
      $address->country_code,
    ]));
    $response = $this->httpClient->get($url);
    if ($response->getStatusCode() != 200) {
      // @todo: throw exception?
      return;
    }
    $json = json_decode($response->getBody());
    if (empty($json->status) || $json->status != 'OK') {
      // @todo: throw exception?
      return;
    }
    $result = reset($json->results);
    $coordinates = $result->geometry->location;

    // Save the coordinates.
    $field_name = $data->field_coordinates;
    if ($entity->{$field_name}->lat != $coordinates->lat || $entity->{$field_name}->lng != $coordinates->lng) {
      $entity->{$field_name}->lat = $coordinates->lat;
      $entity->{$field_name}->lng = $coordinates->lng;
      $entity->save();
      \Drupal::logger('geolocationqueue')->notice('{%entity_type:%entity_id} %field_name changed from {%original_lat, %original_lng} to {%lat, %lng}', [
        '%entity_type' => $entity_type,
        '%entity_id' => $entity_id,
        '%field_name' => $field_name,
        '%original_lat' => $entity->original->{$field_name}->lat,
        '%original_lng' => $entity->original->{$field_name}->lng,
        '%lat' => $coordinates->lat,
        '%lng' => $coordinates->lng,
      ]);
    }
  }

}
