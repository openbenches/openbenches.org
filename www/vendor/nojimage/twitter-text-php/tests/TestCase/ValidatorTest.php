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
use Twitter\Text\Configuration;
use Twitter\Text\Validator;

/**
 * Twitter Validator Class Unit Tests
 *
 * @author     Nick Pope <nick@nickpope.me.uk>
 * @copyright  Copyright © 2010, Nick Pope
 * @license    http://www.apache.org/licenses/LICENSE-2.0  Apache License v2.0
 * @package    Twitter.Text
 * @property   Validator $validator
 */
class ValidatorTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    protected function tearDown()
    {
        unset($this->validator);
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
        $data = Yaml::parse(DATA . '/validate.yml');
        return isset($data['tests'][$test]) ? $data['tests'][$test] : array();
    }

    /**
     * @group Validation
     */
    public function testDefaultConfigurationIsV3()
    {
        $v3Config = new Configuration();
        $this->assertSame($v3Config->toArray(), $this->validator->getConfiguration()->toArray());
        $this->assertSame(3, $this->validator->getConfiguration()->version);
    }

    /**
     * @group Validation
     */
    public function testConfigrationFromObject()
    {
        $conf = new Configuration();
        $validator = Validator::create($conf);
        $this->assertSame($conf, $validator->getConfiguration());
    }
}
