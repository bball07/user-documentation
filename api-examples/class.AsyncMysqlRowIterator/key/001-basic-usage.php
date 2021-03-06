<?hh // partial

namespace Hack\UserDocumentation\API\Examples\AsyncMysql\RowIt\Key;

require __DIR__.'/../../__includes/async_mysql_connect.inc.php';

use \Hack\UserDocumentation\API\Examples\AsyncMysql\ConnectionInfo as CI;

async function connect(
  \AsyncMysqlConnectionPool $pool,
): Awaitable<\AsyncMysqlConnection> {
  return await $pool->connect(
    CI::$host,
    CI::$port,
    CI::$db,
    CI::$user,
    CI::$passwd,
  );
}
async function iterate(): Awaitable<int> {
  $pool = new \AsyncMysqlConnectionPool(darray[]);
  $conn = await connect($pool);
  $result = await $conn->query('SELECT * FROM test_table WHERE userID < 50');
  $conn->close();
  // A call to $result->rowBlocks() actually pops the first element of the
  // row block Vector. So the call actually mutates the Vector.
  $row_blocks = $result->rowBlocks();
  if (!$row_blocks->isEmpty()) {
    // An AsyncMysqlRowBlock
    $row_block = $row_blocks[0];
    if (!$row_block->isEmpty()) {
      // An AsyncMysqlRow
      $row = $row_block->getRow(0);
      // An AsyncMysqlRowIterator
      $it = $row->getIterator();
      while ($it->valid()) {
        // current() will give you a string value of the field in the row
        if ($it->key() > 0 && \is_numeric($it->current())) {
          return \intval($it->current());
        }
        $it->next();
      }
    }
    return -1;
  } else {
    return -1;
  }
}

function run(): void {
  $r = \HH\Asio\join(iterate());
  \var_dump($r);
}

run();
