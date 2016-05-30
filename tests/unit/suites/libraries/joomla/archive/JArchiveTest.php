<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Archive
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/JArchiveTestCase.php';

/**
 * Test class for JArchive.
 * Generated by PHPUnit on 2011-10-26 at 19:32:35.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Archive
 * @since       11.1
 */
class JArchiveTest extends JArchiveTestCase
{
	/**
	 * Tests extracting ZIP.
	 *
	 * @return  void
	 */
	public function testExtractZip()
	{
		if (!JArchiveZip::isSupported())
		{
			$this->markTestSkipped('ZIP files can not be extracted.');
		}

		JArchive::extract(__DIR__ . '/logo.zip', $this->outputPath);
		$this->assertFileExists($this->outputPath . '/logo-zip.png');
	}

	/**
	 * Tests extracting TAR.
	 *
	 * @return  void
	 */
	public function testExtractTar()
	{
		if (!JArchiveTar::isSupported())
		{
			$this->markTestSkipped('Tar files can not be extracted.');
		}

		JArchive::extract(__DIR__ . '/logo.tar', $this->outputPath);
		$this->assertFileExists($this->outputPath . '/logo-tar.png');
	}

	/**
	 * Tests extracting gzip.
	 *
	 * @return  void
	 */
	public function testExtractGzip()
	{
		if (!JArchiveGzip::isSupported())
		{
			$this->markTestSkipped('Gzip files can not be extracted.');
		}

		// we need a configuration with a tmp_path set
		$config = JFactory::$config;
		$config->set('tmp_path', __DIR__ . '/output');

		JArchive::extract(__DIR__ . '/logo-gz.png.gz', $this->outputPath);
		$this->assertFileExists($this->outputPath . '/logo-gz.png');
	}

	/**
	 * Tests extracting bzip2.
	 *
	 * @return  void
	 */
	public function testExtractBzip2()
	{
		if (!JArchiveBzip2::isSupported())
		{
			$this->markTestSkipped('Bzip2 files can not be extracted.');
		}

		// we need a configuration with a tmp_path set
		$config = JFactory::$config;
		$config->set('tmp_path', __DIR__ . '/output');

		JArchive::extract(__DIR__ . '/logo-bz2.png.bz2', $this->outputPath);
		$this->assertFileExists($this->outputPath . '/logo-bz2.png');
	}

	/**
	 * Tests extracting an unknown type
	 *
	 * @expectedException  InvalidArgumentException
	 *
	 * @return  void
	 */
	public function testExtractUnknownType()
	{

		JArchive::extract(__DIR__ . '/unknown.type', $this->outputPath);
	}


	/**
	 * Test if Zip adapter is available
	 *
	 * @return  mixed
	 */
	public function testGetZipAdapter()
	{
		$zip = JArchive::getAdapter('zip');
		$this->assertInstanceOf('JArchiveZip', $zip);
	}

	/**
	 * Test if Bzip2 adapter is available
	 *
	 * @return  mixed
	 */
	public function testGetBzip2Adapter()
	{
		$bzip2 = JArchive::getAdapter('bzip2');
		$this->assertInstanceOf('JArchiveBzip2', $bzip2);
	}

	/**
	 * Test if Gzip adapter is available
	 *
	 * @return  mixed
	 */
	public function testGetGzipAdapter()
	{
		$gzip = JArchive::getAdapter('gzip');
		$this->assertInstanceOf('JArchiveGzip', $gzip);
	}

	/**
	 * Test if tar adapter is available
	 *
	 * @return  mixed
	 */
	public function testGetTarAdapter()
	{
		$tar = JArchive::getAdapter('tar');
		$this->assertInstanceOf('JArchiveTar', $tar);
	}

	/**
	 * Test if the method throws an exception if the adapter is unknown
	 *
	 * @expectedException  UnexpectedValueException
	 *
	 * @return  mixed
	 */
	public function testIfItThrowsAnExceptionWhenAdapterIsNotKnown()
	{
		JArchive::getAdapter('unknown');
	}
}
