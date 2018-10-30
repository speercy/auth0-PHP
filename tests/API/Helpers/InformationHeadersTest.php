<?php
namespace Auth0\Tests\Api\Helpers;

use Auth0\SDK\API\Helpers\InformationHeaders;

/**
 * Class InformationHeadersTest
 *
 * @package Auth0\Tests\Api\Helpers
 */
class InformationHeadersTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Set the package data and make sure it's returned correctly.
     *
     * @return void
     */
    public function testThatSetPackageSetsDataCorrectly()
    {
        $header = new InformationHeaders();
        $header->setPackage( 'test_name', '1.2.3' );
        $header_data = $header->get();

        $this->assertCount(2, $header_data);
        $this->assertArrayHasKey('name', $header_data);
        $this->assertEquals('test_name', $header_data['name']);
        $this->assertArrayHasKey('version', $header_data);
        $this->assertEquals('1.2.3', $header_data['version']);
    }

    /**
     * Set and override an env property and make sure it's returned correctly.
     *
     * @return void
     */
    public function testThatSetEnvPropertySetsDataCorrectly()
    {
        $header = new InformationHeaders();
        $header->setEnvProperty( 'test_env_name', '2.3.4' );
        $header_data = $header->get();

        $this->assertArrayHasKey('env', $header_data);
        $this->assertCount(1, $header_data['env']);
        $this->assertArrayHasKey('test_env_name', $header_data['env']);
        $this->assertEquals('2.3.4', $header_data['env']['test_env_name']);

        $header->setEnvProperty( 'test_env_name', '3.4.5' );
        $header_data = $header->get();
        $this->assertEquals('3.4.5', $header_data['env']['test_env_name']);

        $header->setEnvProperty( 'test_env_name_2', '4.5.6' );
        $header_data = $header->get();
        $this->assertEquals('4.5.6', $header_data['env']['test_env_name_2']);
    }

    /**
     * Set the package and env and make sure it's built correctly.
     *
     * @return void
     */
    public function testThatBuildReturnsCorrectData()
    {
        $header      = new InformationHeaders();
        $header_data = (object) [
            'name' => 'test_name_2',
            'version' => '5.6.7',
            'env' => (object) [
                'test_env_name_3' => '6.7.8',
            ],
        ];
        $header->setPackage( $header_data->name, $header_data->version );
        $header->setEnvProperty( 'test_env_name_3', $header_data->env->test_env_name_3 );

        $header_built = base64_decode($header->build());
        $this->assertEquals( json_encode($header_data), $header_built );
    }
}
