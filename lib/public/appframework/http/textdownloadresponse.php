<?php
/**
 * ownCloud - App Framework
 *
 * @author Georg Ehrke
 * @copyright 2014 Georg Ehrke <georg@ownCloud.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCP\AppFramework\Http;

class TextDownloadResponse extends DownloadResponse {
	/**
	 * @var string
	 */
	private $data;

	/**
	 * Creates a response that prompts the user to download the text
	 * @param string $data text to be downloaded
	 * @param string $filename the name that the downloaded file should have
	 * @param string $contentType the mimetype that the downloaded file should have
	 */
	public function __construct($data, $filename, $contentType) {
		$this->data = $data;
		parent::__construct($filename, $contentType);
	}

	/**
	 * @param string $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->data;
	}
}