<?php

/**
 * @author     Nick Pope <nick@nickpope.me.uk>
 * @copyright  Copyright © 2010, Nick Pope
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 * @package    Twitter.Text
 */

namespace Twitter\Text\TestCase;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use Twitter\Text\HitHighlighter;

/**
 * Twitter HitHighlighter Class Unit Tests
 *
 * @author     Nick Pope <nick@nickpope.me.uk>
 * @copyright  Copyright © 2010, Nick Pope
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 * @package    Twitter.Text
 * @property   HitHighlighter $highlighter
 */
class HitHighlighterTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->highlighter = new HitHighlighter();
    }

    protected function tearDown()
    {
        unset($this->highlighter);
        parent::tearDown();
    }

    /**
     * A helper function for providers.
     *
     * @param string  $test  The test to fetch data for.
     *
     * @return array  The test data to provide.
     */
    protected function providerHelper($test)
    {
        $data = Yaml::parse(DATA . '/hit_highlighting.yml');
        return isset($data['tests'][$test]) ? $data['tests'][$test] : array();
    }

    public function testCreate()
    {
        $highlighter = HitHighlighter::create();
        $this->assertInstanceOf('Twitter\\Text\\HitHighlighter', $highlighter);
    }
}
