<?php

/**
 * @author     Nick Pope <nick@nickpope.me.uk>
 * @copyright  Copyright © 2010, Mike Cochrane, Nick Pope
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 * @package    Twitter.Text
 */

namespace Twitter\Text\TestCase;

use PHPUnit\Framework\TestCase;
use Twitter\Text\Autolink;

/**
 * Twitter Autolink Class Unit Tests
 *
 * @author     Nick Pope <nick@nickpope.me.uk>
 * @copyright  Copyright © 2010, Mike Cochrane, Nick Pope
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 * @package    Twitter.Text
 * @property Autolink $linker
 */
class AutolinkTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->linker = new Autolink();
    }

    protected function tearDown()
    {
        unset($this->linker);
        parent::tearDown();
    }

    public function testCreate()
    {
        $linker = Autolink::create();
        $this->assertInstanceOf('Twitter\\Text\\AutoLink', $linker);
    }

    /**
     * test for accessor / mutator
     */
    public function testAccessorMutator()
    {
        $this->assertSame('', $this->linker->getURLClass());
        $this->assertSame('-url', $this->linker->setURLClass('-url')->getURLClass());

        $this->assertSame('tweet-url username', $this->linker->getUsernameClass());
        $this->assertSame('-username', $this->linker->setUsernameClass('-username')->getUsernameClass());

        $this->assertSame('tweet-url list-slug', $this->linker->getListClass());
        $this->assertSame('-list', $this->linker->setListClass('-list')->getListClass());

        $this->assertSame('tweet-url hashtag', $this->linker->getHashtagClass());
        $this->assertSame('-hashtag', $this->linker->setHashtagClass('-hashtag')->getHashtagClass());

        $this->assertSame('tweet-url cashtag', $this->linker->getCashtagClass());
        $this->assertSame('-cashtag', $this->linker->setCashtagClass('-cashtag')->getCashtagClass());

        $this->assertSame('_blank', $this->linker->getTarget());
        $this->assertSame('', $this->linker->setTarget(false)->getTarget());

        $this->assertSame(true, $this->linker->getExternal());
        $this->assertSame(false, $this->linker->setExternal(false)->getExternal());

        $this->assertSame(true, $this->linker->getNoFollow());
        $this->assertSame(false, $this->linker->setNoFollow(false)->getNoFollow());

        $this->assertSame(false, $this->linker->isUsernameIncludeSymbol());
        $this->assertSame(true, $this->linker->setUsernameIncludeSymbol(true)->isUsernameIncludeSymbol());

        $this->assertSame('', $this->linker->getSymbolTag());
        $this->assertSame('i', $this->linker->setSymbolTag('i')->getSymbolTag());

        $this->assertSame('', $this->linker->getTextWithSymbolTag());
        $this->assertSame('b', $this->linker->setTextWithSymbolTag('b')->getTextWithSymbolTag());
    }

    public function testAutolinkWithEmoji()
    {
        $text = '@ummjackson 🤡 https://i.imgur.com/I32CQ81.jpg';
        $linkedText = $this->linker->autoLink($text);

        // @codingStandardsIgnoreStart
        $expected = '@<a class="tweet-url username" href="https://twitter.com/ummjackson" rel="external nofollow" target="_blank">ummjackson</a> 🤡 <a href="https://i.imgur.com/I32CQ81.jpg" rel="external nofollow" target="_blank">https://i.imgur.com/I32CQ81.jpg</a>';
        // @codingStandardsIgnoreEnd

        $this->assertSame($expected, $linkedText);
    }

    public function testUsernameIncludeSymbol()
    {
        $tweet = 'Testing @mention and @mention/list';
        // @codingStandardsIgnoreStart
        $expected = 'Testing <a class="tweet-url username" href="https://twitter.com/mention" rel="external nofollow" target="_blank">@mention</a> and <a class="tweet-url list-slug" href="https://twitter.com/mention/list" rel="external nofollow" target="_blank">@mention/list</a>';
        // @codingStandardsIgnoreEnd

        $this->linker->setUsernameIncludeSymbol(true);
        $linkedText = $this->linker->autoLink($tweet);
        $this->assertSame($expected, $linkedText);
    }

    public function testSymbolTag()
    {
        $this->linker
            ->setExternal(false)
            ->setTarget(false)
            ->setNoFollow(false)
            ->setSymbolTag('s')
            ->setTextWithSymbolTag('b');

        $tweet = '#hash';
        $expected = '<a href="https://twitter.com/search?q=%23hash" title="#hash" class="tweet-url hashtag"><s>#</s><b>hash</b></a>';
        $this->assertSame($expected, $this->linker->autoLink($tweet));

        $tweet = '@mention';
        $expected = '<s>@</s><a class="tweet-url username" href="https://twitter.com/mention"><b>mention</b></a>';
        $this->assertSame($expected, $this->linker->autoLink($tweet));

        $this->linker->setUsernameIncludeSymbol(true);
        $expected = '<a class="tweet-url username" href="https://twitter.com/mention"><s>@</s><b>mention</b></a>';
        $this->assertSame($expected, $this->linker->autoLink($tweet));
    }
}
