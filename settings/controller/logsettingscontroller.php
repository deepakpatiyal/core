<?php
/**
 * @author Georg Ehrke
 * @copyright 2014 Georg Ehrke <georg@ownCloud.com>
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OC\Settings\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\TextDownloadResponse;
use OCP\IRequest;
use OCP\IConfig;

/**
 * Class LogSettingsController
 *
 * @package OC\Settings\Controller
 */
class LogSettingsController extends Controller {
	/**
	 * @var \OCP\IConfig
	 */
	private $config;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param IConfig $config
	 */
	public function __construct($appName,
								IRequest $request,
								IConfig $config) {
		parent::__construct($appName, $request);
		$this->config = $config;
	}

	/**
	 * set log level for logger
	 *
	 * @param int $level
	 * @return JSONResponse
	 */
	public function setLogLevel($level) {
		if ($level < 0 || $level > 4) {
			return new JSONResponse([
				'message' => \OC::$server->getL10N('settings')->t('log-level out of allowed range'),
				'status' => 'error',
			]);
		}

		$this->config->setSystemValue('loglevel', $level);
		return new JSONResponse([
			'level' => $level,
			'status' => 'success',
		]);
	}

	/**
	 * get log entries from logfile
	 *
	 * @NoCSRFRequired
	 *
	 * @param int $count
	 * @param int $offset
	 * @return JSONResponse
	 */
	public function getEntries($count=50, $offset=0) {
		$routeId = $this->request->getParam('_route');
		if ($routeId === 'settings.LogSettings.download') {
			$count = $offset = null;
		}

		$data = \OC_Log_Owncloud::getEntries($count, $offset);

		if ($routeId === 'settings.LogSettings.download') {
			return new TextDownloadResponse(
				json_encode($data),
				$this->getFilenameForDownload(),
				'application/json'
			);
		} else {
			return new JSONResponse([
				'data' => $data,
				'remain' => count(\OC_Log_Owncloud::getEntries(1, $offset + $count)) !== 0,
				'status' => 'success',
			]);
		}
	}

	/**
	 * get filename for the logfile that's being downloaded
	 *
	 * @param int $timestamp (defaults to time())
	 * @return string
	 */
	private function getFilenameForDownload($timestamp=null) {
		$instanceId = $this->config->getSystemValue('instanceid');

		$filename = implode([
			'ownCloud',
			$instanceId,
			(!is_null($timestamp)) ? $timestamp : time()
		], '-');
		$filename .= '.log';

		return $filename;
	}
}
