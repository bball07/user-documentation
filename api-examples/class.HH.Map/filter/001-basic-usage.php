<?hh // partial

namespace Hack\UserDocumentation\API\Examples\Map\Filter;

$m = Map {
  'red' => '#ff0000',
  'green' => '#00ff00',
  'blue' => '#0000ff',
  'yellow' => '#ffff00',
  'purple' => '#663399',
};

// Filter $m for colors with a 100% red component
$red_100 = $m->filter($hex_code ==> strpos($hex_code, '#ff') === 0);
var_dump($red_100);
