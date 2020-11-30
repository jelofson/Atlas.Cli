<?php
namespace Atlas\Cli;

use Atlas\Cli\FakeFsio;
use Atlas\Cli\Logger;
use Aura\Cli\Stdio\Handle;

class SkeletonTest extends \PHPUnit\Framework\TestCase
{
    protected $fsio;
    protected $logger;
    protected $stdout;
    protected $factory;

    protected function setUp()
    {
        $this->fsio = $this->newFsio();
        $this->stdout = fopen('php://memory', 'w+');
        $this->logger = new Logger($this->stdout);
    }

    protected function newFsio()
    {
        $fsio = new FakeFsio();

        // put the real templates into the fake fsio
        $dir = dirname(__DIR__) . '/resources/templates';
        $tpls = [
            'Type.tpl',
            'TypeEvents.tpl',
            'TypeFields.tpl',
            'TypeRecord.tpl',
            'TypeRecordSet.tpl',
            'TypeRelationships.tpl',
            'TypeSelect.tpl',
            'TypeTable.tpl',
            'TypeTableEvents.tpl',
            'TypeRow.tpl',
            'TypeTableSelect.tpl',
        ];
        foreach ($tpls as $tpl) {
            $file = $dir . DIRECTORY_SEPARATOR . $tpl;
            $fsio->put($file, file_get_contents($file));
        }

        return $fsio;
    }

    public function test()
    {
        
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/Author.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorEvents.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorFields.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorRecord.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorRecordSet.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorRelationships.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorSelect.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorTable.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorTableEvents.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorRow.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Author/AuthorTableSelect.php'));

        $this->fsio->mkdir('/app/DataSource');

        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
        ]);

        $skeleton = new Skeleton($config, $this->fsio, $this->logger);
        $skeleton();

        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/Author.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorEvents.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorFields.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorRecord.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorRecordSet.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorRelationships.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorSelect.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorTable.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorTableEvents.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorRow.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/AuthorTableSelect.php'));
    }

    public function testTablesConfig()
    {
        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
            'tables'=>['authors', 'employee']
        ]);
 
        $this->fsio->mkdir('/app/DataSource');
        $skeleton = new Skeleton($config, $this->fsio, $this->logger);
        $skeleton();

        // The only orm classes should be for authors and employee
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/Author.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Employee/Employee.php'));

        $this->assertFalse($this->fsio->isFile('/app/DataSource/Tag/Tag.php'));
        $this->assertFalse($this->fsio->isFile('/app/DataSource/Tagging/Tagging.php'));
    
    }

    public function testConfig()
    {
        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
        ]);
 
        $this->fsio->mkdir('/app/DataSource');
        $skeleton = new Skeleton($config, $this->fsio, $this->logger);
        $skeleton();

        // All tables should have ORM files
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Author/Author.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Employee/Employee.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Tag/Tag.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Tagging/Tagging.php'));
        $this->assertTrue($this->fsio->isFile('/app/DataSource/Thread/Thread.php'));
    
    }

    protected function readHandle($handle)
    {
        $text = '';
        rewind($handle);
        while ($read .= fread($handle, 8192)) {
            $text .= $read;
        }
        return $text;
    }
}
