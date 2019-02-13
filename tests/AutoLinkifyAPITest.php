<?hh // strict

namespace HHVM\UserDocumentation\Tests;

use function Facebook\FBExpect\expect;
use type Facebook\HackTest\DataProvider;

/**
 * @group remote
 * @small
 */
class AutoLinkifyAPITest extends \Facebook\HackTest\HackTest {
  public function autoLinkifyExamplesProvider(
  ): array<string, (string, string, string)> {
    return [
      'PHP function' => tuple(
        '/hack/async/utility-functions',
        'array_filter()',
        'http://php.net/manual/en/function.array-filter.php',
      ),
      'Hack class' => tuple(
        '/hack/async/extensions',
        'MCRouter',
        '/hack/reference/class/MCRouter/',
      ),
      'Namespaced Hack function' => tuple(
        '/hack/async/extensions',
        'HH\Asio\curl_exec()',
        '/hack/reference/function/HH.Asio.curl_exec/',
      ),
      'Hack fully-qualified method' => tuple(
        '/hack/reference/class/AsyncMysqlConnectResult/elapsedMicros/',
        'AsyncMysqlConnection::connectResult',
        '/hack/reference/class/AsyncMysqlConnection/connectResult/',
      ),
      'Hack method with incomplete name from class doc' => tuple(
        '/hack/reference/class/AsyncMysqlClient/',
        'connect()',
        '/hack/reference/class/AsyncMysqlClient/connect/',
      ),
      'Hack method with incomplete name from method doc' => tuple(
        '/hack/reference/class/AsyncMysqlConnection/query/',
        'escapeString()',
        '/hack/reference/class/AsyncMysqlConnection/escapeString/',
      ),
      'Hack function with parameter information' => tuple(
        '/hack/async/utility-functions',
        'HH\Asio\wrap(Awaitable<Tv>)',
        '/hack/reference/function/HH.Asio.wrap/',
      ),
      'Hack class with type parameter' => tuple(
        '/hack/collections/classes',
        'ImmSet<T>',
        '/hack/reference/class/HH.ImmSet/',
      ),
      'Hack class with a class with type parameter as its type parameter' =>
        tuple(
          '/hack/collections/interfaces',
          'ConstCollection<Pair<Tk, Tv>>',
          '/hack/reference/interface/ConstCollection/',
        ),
      'Hack class method with missing HH namespace' => tuple(
        '/hack/FAQ/faq',
        'KeyedIterable::map()',
        '/hack/reference/interface/HH.KeyedIterable/map/',
      ),
      'Default Namepsace' => tuple(
        '/hack/async/awaitables',
        'join',
        '/hack/reference/function/HH.Asio.join/',
      ),
    ];
  }

  <<DataProvider('autoLinkifyExamplesProvider')>>
  public async function testAutoLinkify(
    string $source,
    string $keyword,
    string $dest,
  ): Awaitable<void> {
    list($page, $body) = await PageLoader::getPageAsync($source);

    /* HH_FIXME[2049] No DOM HHI: facebook/hhvm#5322 */
    $dom = new \DOMDocument();
    $dom->loadHTML($body);
    /* HH_FIXME[2049] No DOM HHI: facebook/hhvm#5322 */
    $xpath = new \DOMXPath($dom);

    $nodes = $xpath->query(
      '//a[@href = "'.$dest.'"]'.'/code[text() = "'.$keyword.'"]',
    );
    expect($nodes->length)->toBeGreaterThanOrEqualTo(
      1,
      'Expected %s to link to %s',
      $keyword,
      $dest,
    );
  }
}
