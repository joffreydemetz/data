<?php

namespace JDZ\Utils\Tests;

use JDZ\Utils\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    private Data $data;

    protected function setUp(): void
    {
        $this->data = new Data();
    }

    public function testSetAndGetSimpleValue(): void
    {
        $this->data->set('key', 'value');
        $this->assertEquals('value', $this->data->get('key'));
    }

    public function testSetAndGetNestedValue(): void
    {
        $this->data->set('level1.level2.level3', 'deep value');
        $this->assertEquals('deep value', $this->data->get('level1.level2.level3'));
    }

    public function testSetAndGetNumericKeys(): void
    {
        $this->data->set('array.0', 'first');
        $this->data->set('array.1', 'second');
        $this->assertEquals('first', $this->data->get('array.0'));
        $this->assertEquals('second', $this->data->get('array.1'));
    }

    public function testGetWithDefault(): void
    {
        $this->assertEquals('default', $this->data->get('nonexistent', 'default'));
    }

    public function testGetBool(): void
    {
        $this->data->set('bool.true', true);
        $this->data->set('bool.false', false);
        $this->data->set('bool.one', 1);
        $this->data->set('bool.zero', 0);

        $this->assertTrue($this->data->getBool('bool.true'));
        $this->assertFalse($this->data->getBool('bool.false'));
        $this->assertTrue($this->data->getBool('bool.one'));
        $this->assertFalse($this->data->getBool('bool.zero'));
        $this->assertFalse($this->data->getBool('nonexistent'));
        $this->assertTrue($this->data->getBool('nonexistent', true));
    }

    public function testGetInt(): void
    {
        $this->data->set('number', '42');
        $this->data->set('string', 'not a number');

        $this->assertEquals(42, $this->data->getInt('number'));
        $this->assertEquals(0, $this->data->getInt('string'));
        $this->assertEquals(100, $this->data->getInt('nonexistent', 100));
    }

    public function testGetArray(): void
    {
        $this->data->set('arr', ['a', 'b', 'c']);
        $this->data->set('string', 'text');

        $this->assertEquals(['a', 'b', 'c'], $this->data->getArray('arr'));
        $this->assertEquals(['text'], $this->data->getArray('string'));
        $this->assertEquals(['default'], $this->data->getArray('nonexistent', ['default']));
    }

    public function testHas(): void
    {
        $this->data->set('exists', 'value');
        $this->data->set('nested.exists', 'value');

        $this->assertTrue($this->data->has('exists'));
        $this->assertTrue($this->data->has('nested.exists'));
        $this->assertFalse($this->data->has('nonexistent'));
        $this->assertFalse($this->data->has('nested.nonexistent'));
    }

    public function testDef(): void
    {
        $this->data->def('key', 'default');
        $this->assertEquals('default', $this->data->get('key'));

        $this->data->set('key', 'actual');
        $this->data->def('key', 'default');
        $this->assertEquals('actual', $this->data->get('key'));
    }

    public function testErase(): void
    {
        $this->data->set('key', 'value');
        $this->data->set('nested.key', 'value');

        $this->assertTrue($this->data->has('key'));
        $this->data->erase('key');
        $this->assertFalse($this->data->has('key'));

        $this->assertTrue($this->data->has('nested.key'));
        $this->data->erase('nested.key');
        $this->assertFalse($this->data->has('nested.key'));
    }

    public function testEraseNonexistent(): void
    {
        $this->data->erase('nonexistent');
        $this->data->erase('nested.nonexistent');
        $this->assertFalse($this->data->has('nonexistent'));
    }

    public function testSetsWithMerge(): void
    {
        $this->data->set('key1', 'value1');
        $this->data->set('nested.key2', 'value2');

        $this->data->sets([
            'key3' => 'value3',
            'nested.key4' => 'value4',
        ], true);

        $this->assertEquals('value1', $this->data->get('key1'));
        $this->assertEquals('value2', $this->data->get('nested.key2'));
        $this->assertEquals('value3', $this->data->get('key3'));
        $this->assertEquals('value4', $this->data->get('nested.key4'));
    }

    public function testSetsWithoutMerge(): void
    {
        $this->data->set('key1', 'value1');

        $this->data->sets([
            'key2' => 'value2',
            'nested.key3' => 'value3',
        ], false);

        $this->assertEquals('value1', $this->data->get('key1'));
        $this->assertEquals('value2', $this->data->get('key2'));
        $this->assertEquals('value3', $this->data->get('nested.key3'));
    }

    public function testAll(): void
    {
        $this->data->set('key1', 'value1');
        $this->data->set('key2.nested', 'value2');

        $all = $this->data->all();

        $this->assertIsArray($all);
        $this->assertEquals('value1', $all['key1']);
        $this->assertEquals('value2', $all['key2']['nested']);
    }

    public function testPreserveNulls(): void
    {
        $this->data->withPreserveNulls(true);
        $this->data->set('null_value', null);

        // Note: has() cannot distinguish between null values and missing keys
        // This is a known limitation when preserveNulls is false
        $all = $this->data->all();
        $this->assertArrayHasKey('null_value', $all);
        $this->assertNull($this->data->get('null_value'));
    }

    public function testComplexNestedStructure(): void
    {
        $this->data->set('users.0.name', 'John');
        $this->data->set('users.0.email', 'john@example.com');
        $this->data->set('users.1.name', 'Jane');
        $this->data->set('users.1.email', 'jane@example.com');

        $this->assertEquals('John', $this->data->get('users.0.name'));
        $this->assertEquals('jane@example.com', $this->data->get('users.1.email'));
    }

    public function testOverwriteValue(): void
    {
        $this->data->set('key', 'old');
        $this->assertEquals('old', $this->data->get('key'));

        $this->data->set('key', 'new');
        $this->assertEquals('new', $this->data->get('key'));
    }

    public function testChaining(): void
    {
        $result = $this->data
            ->set('key1', 'value1')
            ->set('key2', 'value2')
            ->def('key3', 'value3')
            ->erase('key2');

        $this->assertInstanceOf(Data::class, $result);
        $this->assertTrue($this->data->has('key1'));
        $this->assertFalse($this->data->has('key2'));
        $this->assertTrue($this->data->has('key3'));
    }
}
