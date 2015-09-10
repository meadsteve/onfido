<?php

use Onfido\Client;
use Faker\Factory;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	const ONFIDO_TOKEN = "test_R6me4f2LQnkSHHbSh9UpckuZg4LGcOsK";

	/**
	 * @expectedException Onfido\Exception\InvalidRequestException
	 * @dataProvider invalidFirstNameProvider
	 */
	public function testCreateWithInvalidFirstName($first_name)
	{
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $first_name
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function invalidFirstNameProvider()
	{
		return array(
			array(''),
			array(null)
		);
	}

	/**
	 * @expectedException Onfido\Exception\InvalidRequestException
	 * @dataProvider invalidLastNameProvider
	 */
	public function testCreateWithInvalidLastName($first_name, $last_name)
	{
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $first_name,
			'last_name' => $last_name
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function invalidLastNameProvider()
	{
		$faker = Factory::create();

		return array(
			array($faker->firstName, ''),
			array($faker->firstName, null)
		);
	}

	/**
	 * @dataProvider validTitleProvider
	 */
	public function testValidTitles($title)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'title' => $title
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function validTitleProvider()
	{
		return array(
			array('Mr'),
			array('Ms'),
			array('Miss'),
			array('Mrs'),
			array(null)
		);
	}

	/**
	 * @expectedException Onfido\Exception\InvalidRequestException
	 * @dataProvider invalidTitleProvider
	 */
	public function testInvalidTitles($title)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'title' => $title
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function invalidTitleProvider()
	{
		return array(
			array('Mr.'),
			array('Ms.'),
			array('Mrs.'),
			array(''),
		);
	}

	/**
	 * @dataProvider validGenderProvider
	 */
	public function testValidGenders($gender)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'gender' => $gender
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function validGenderProvider()
	{
		return array(
			array('male'),
			array('Male'),
			array('female'),
			array('Female'),
			array(null)
		);
	}

	/**
	 * @expectedException Onfido\Exception\InvalidRequestException
	 * @dataProvider invalidGenderProvider
	 */
	public function testInvalidGenders($gender)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'gender' => $gender
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function invalidGenderProvider()
	{
		return array(
			array('m'),
			array('M'),
			array('f'),
			array('F'),
			array(''),
		);
	}

	/**
	 * @dataProvider validCountryProvider
	 */
	public function testValidCountries($country)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'email' => $faker->email, // Include email because applicants created in the USA need it
			'country' => $country
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function validCountryProvider()
	{
		return array(
			array('usa'),
			array('USA'),
			array('can'),
			array('CAN'),
			array(null)
		);
	}

	/**
	 * @expectedException Onfido\Exception\InvalidRequestException
	 * @dataProvider invalidCountryProvider
	 */
	public function testInvalidCountries($country)
	{
		$faker = Factory::create();
		$client = new Client(self::ONFIDO_TOKEN);
		$params = array(
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'country' => $country
		);

		$applicant = $client->createApplicant($params);
		$this->assertInstanceOf('Onfido\Applicant', $applicant);
	}

	public function invalidCountryProvider()
	{
		return array(
			array('U.S.A.'),
			array('US'),
			array('CA'),
			array(''),
		);
	}

	public function testCreateRetrieveApplicant()
	{
		$faker = Factory::create();
		$title = 'Mr';
		$first_name = $faker->firstName;
		$last_name = $faker->lastName;
		$middle_name = $faker->firstName;
		$email = $faker->email;
		$gender = 'Male';
		$dob = '1980-11-23';
		$telephone = '11234567890';
		$mobile = '10987654321';
		$country = 'usa';

		$params = array(
			'title' => $title,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'middle_name' => $middle_name,
			'email' => $email,
			'gender' => $gender,
			'dob' => $dob,
			'telephone' => $telephone,
			'mobile' => $mobile,
			'country' => $country
		);

		$client = new Client(self::ONFIDO_TOKEN);

		// Create the applicant on Onfido
		$applicant = $client->createApplicant($params);

		$this->assertInstanceOf('Onfido\Applicant', $applicant);
		$this->assertNotNull($applicant->getId());
		$this->assertNotNull($applicant->getHref());
		$this->assertNotNull($applicant->getCreatedAt());
		$this->assertEquals($title, $applicant->getTitle());
		$this->assertEquals($first_name, $applicant->getFirstName());
		$this->assertEquals($last_name, $applicant->getLastName());
		$this->assertEquals($middle_name, $applicant->getMiddleName());
		$this->assertEquals($email, $applicant->getEmail());
		$this->assertEquals($gender, $applicant->getGender());
		$this->assertEquals($dob, $applicant->getDob());
		$this->assertEquals($telephone, $applicant->getTelephone());
		$this->assertEquals($mobile, $applicant->getMobile());
		$this->assertEquals($country, $applicant->getCountry());

		// Retrieve the applicant from Onfido
		$id = $applicant->getId();
		$applicant = $client->retrieveApplicant($id);

		$this->assertInstanceOf('Onfido\Applicant', $applicant);
		$this->assertNotNull($applicant->getId());
		$this->assertNotNull($applicant->getHref());
		$this->assertNotNull($applicant->getCreatedAt());
		$this->assertEquals($title, $applicant->getTitle());
		$this->assertEquals($first_name, $applicant->getFirstName());
		$this->assertEquals($last_name, $applicant->getLastName());
		$this->assertEquals($middle_name, $applicant->getMiddleName());
		$this->assertEquals($email, $applicant->getEmail());
		$this->assertEquals($gender, $applicant->getGender());
		$this->assertEquals($dob, $applicant->getDob());
		$this->assertEquals($telephone, $applicant->getTelephone());
		$this->assertEquals($mobile, $applicant->getMobile());
		$this->assertEquals($country, $applicant->getCountry());
	}
}
