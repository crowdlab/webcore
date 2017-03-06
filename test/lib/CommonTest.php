<?php

require_once __DIR__.'/../../php/lib/autoload.php.inc';

/**
 * Тест базовых операций.
 *
 * @outputBuffering disabled
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class CommonTest extends Testing\CoreTestBase
{
    public function testStripTags()
    {
        // script
        $fixture = "<b onclick=\"alert('ololo');\">test</b>";
        $expect = '<b>test</b>';
        $this->assertEquals(Common::strip_tags_clean($fixture, ['b']), $expect);
        // bad tags
        $fixture = '<b>te<i>s</i>t</b>';
        $expect = '<b>test</b>';
        $this->assertEquals(Common::strip_tags_clean($fixture, ['b']), $expect);
    }

    public function testFixUrls()
    {
        // simple
        $fixture = 'http://ya.ru/';
        $result = '<a href="http://ya.ru/">http://ya.ru/</a>';
        $this->assertEquals($result, \Common::fix_urls($fixture));
        // with hash
        $fixture = 'http://ya.ru/#hash';
        $result = '<a href="http://ya.ru/#hash">http://ya.ru/#hash</a>';
        $this->assertEquals($result, \Common::fix_urls($fixture));
    }

    public function testArrayDiff()
    {
        $fixture_1 = $fixture_2 = [
            'fields' => ['x' => 'y'],
        ];
        $expect = [];
        $this->assertEquals(Common::array_diff_assoc_recursive(
            $fixture_1, $fixture_2), $expect);
    }
}
