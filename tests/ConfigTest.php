<?php
namespace Atlas\Cli;



class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testTables()
    {
        $tables = ['foo', 'bar'];
        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
            'tables'=> $tables
        ]);

        $this->assertEquals($tables, $config->tables);
    }

    public function testTablesEmpty()
    {
        $tables = [];
        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
            
        ]);

        $this->assertEquals($tables, $config->tables);
    }

    public function testTablesException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $config = new Config([
            'pdo' => 'sqlite:' . __DIR__ . '/fixture.sqlite',
            'directory' => '/app/DataSource',
            'namespace' => 'App\\DataSource\\Author',
            'tables'=>'foo'
        ]);

        
    }
}