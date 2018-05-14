<?php

/**
 * CheckUser API Query Module
 */
class ApiQueryCheckUser extends ApiQueryBase {
	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'cu' );
	}

	public function execute() {
		global $wgCheckUserForceSummary;

		$db = $this->getDB();
		$params = $this->extractRequestParams();

		list( $request, $target, $reason, $timecond, $limit, $xff ) = [
			$params['request'], $params['target'], $params['reason'],
			$params['timecond'], $params['limit'], $params['xff'] ];

		if ( is_callable( [ $this, 'checkUserRightsAny' ] ) ) {
			$this->checkUserRightsAny( 'checkuser' );
		} else {
			if ( !$this->getUser()->isAllowed( 'checkuser' ) ) {
				$this->dieUsage( 'You need the checkuser right', 'permissionerror' );
			}
		}

		if ( $wgCheckUserForceSummary && is_null( $reason ) ) {
			if ( is_callable( [ $this, 'dieWithError' ] ) ) {
				$this->dieWithError( 'apierror-checkuser-missingsummary', 'missingdata' );
			} else {
				$this->dieUsage( 'You must define reason for check', 'missingdata' );
			}
		}

		$reason = $this->msg( 'checkuser-reason-api', $reason )->inContentLanguage()->text();
		$timeCutoff = strtotime( $timecond ); // absolute time
		if ( !$timeCutoff ) {
			if ( is_callable( [ $this, 'dieWithError' ] ) ) {
				$this->dieWithError( 'apierror-checkuser-timelimit', 'invalidtime' );
			} else {
				$this->dieUsage(
					'You need use correct time limit (like "-2 weeks" or "2 weeks ago")',
					'invalidtime'
				);
			}
		}

		$this->addTables( 'cu_changes' );
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'ORDER BY', 'cuc_timestamp DESC' );
		$this->addWhere( "cuc_timestamp > " . $db->addQuotes( $db->timestamp( $timeCutoff ) ) );

		switch ( $request ) {
			case 'userips':
				$user_id = User::idFromName( $target );
				if ( !$user_id ) {
					if ( is_callable( [ $this, 'dieWithError' ] ) ) {
						$this->dieWithError(
							[ 'nosuchusershort', wfEscapeWikiText( $target ) ], 'nosuchuser'
						);
					} else {
						$this->dieUsage( 'Target user does not exist', 'nosuchuser' );
					}
				}

				$this->addFields( [ 'cuc_timestamp', 'cuc_ip', 'cuc_xff' ] );
				$this->addWhereFld( 'cuc_user_text', $target );
				$res = $this->select( __METHOD__ );
				$result = $this->getResult();

				$ips = [];
				foreach ( $res as $row ) {
					$timestamp = wfTimestamp( TS_ISO_8601, $row->cuc_timestamp );
					$ip = strval( $row->cuc_ip );

					if ( !isset( $ips[$ip] ) ) {
						$ips[$ip]['end'] = $timestamp;
						$ips[$ip]['editcount'] = 1;
					} else {
						$ips[$ip]['start'] = $timestamp;
						$ips[$ip]['editcount']++;
					}
				}

				$resultIPs = [];
				foreach ( $ips as $ip => $data ) {
					$data['address'] = $ip;
					$resultIPs[] = $data;
				}

				CheckUser::addLogEntry( 'userips', 'user', $target, $reason, $user_id );
				$result->addValue( [
					'query', $this->getModuleName() ], 'userips', $resultIPs );
				$result->addIndexedTagName( [
					'query', $this->getModuleName(), 'userips' ], 'ip' );
				break;

			case 'edits':
				if ( IP::isIPAddress( $target ) ) {
					$cond = CheckUser::getIpConds( $db, $target, isset( $xff ) );
					if ( !$cond ) {
						if ( is_callable( [ $this, 'dieWithError' ] ) ) {
							$this->dieWithError( 'apierror-badip', 'invalidip' );
						} else {
							$this->dieUsage( 'IP or range is invalid', 'invalidip' );
						}
					}
					$this->addWhere( $cond );
					$log_type = [];
					if ( isset( $xff ) ) {
						$log_type[] = 'ipedits-xff';
					} else {
						$log_type[] = 'ipedits';
					}
					$log_type[] = 'ip';
				} else {
					$user_id = User::idFromName( $target );
					if ( !$user_id ) {
						if ( is_callable( [ $this, 'dieWithError' ] ) ) {
							$this->dieWithError(
								[ 'nosuchusershort', wfEscapeWikiText( $target ) ], 'nosuchuser'
							);
						} else {
							$this->dieUsage( 'Target user does not exist', 'nosuchuser' );
						}
					}
					$this->addWhereFld( 'cuc_user_text', $target );
					$log_type = [ 'useredits', 'user' ];
				}

				$this->addFields( [
					'cuc_namespace', 'cuc_title', 'cuc_user_text', 'cuc_actiontext',
					'cuc_comment', 'cuc_minor', 'cuc_timestamp', 'cuc_ip', 'cuc_xff', 'cuc_agent'
				] );

				$res = $this->select( __METHOD__ );
				$result = $this->getResult();

				$edits = [];
				foreach ( $res as $row ) {
					$edit = [
						'timestamp' => wfTimestamp( TS_ISO_8601, $row->cuc_timestamp ),
						'ns'        => intval( $row->cuc_namespace ),
						'title'     => $row->cuc_title,
						'user'      => $row->cuc_user_text,
						'ip'        => $row->cuc_ip,
						'agent'     => $row->cuc_agent,
					];
					if ( $row->cuc_actiontext ) {
						$edit['summary'] = $row->cuc_actiontext;
					} elseif ( $row->cuc_comment ) {
						$edit['summary'] = $row->cuc_comment;
					}
					if ( $row->cuc_minor ) {
						$edit['minor'] = 'm';
					}
					if ( $row->cuc_xff ) {
						$edit['xff'] = $row->cuc_xff;
					}
					$edits[] = $edit;
				}

				CheckUser::addLogEntry( $log_type[0], $log_type[1],
					$target, $reason, isset( $user_id ) ? $user_id : '0' );
				$result->addValue( [
					'query', $this->getModuleName() ], 'edits', $edits );
				$result->addIndexedTagName( [
					'query', $this->getModuleName(), 'edits' ], 'action' );
				break;

			case 'ipusers':
				if ( IP::isIPAddress( $target ) ) {
					$cond = CheckUser::getIpConds( $db, $target, isset( $xff ) );
					$this->addWhere( $cond );
					$log_type = 'ipusers';
					if ( isset( $xff ) ) {
						$log_type .= '-xff';
					}
				} else {
					if ( is_callable( [ $this, 'dieWithError' ] ) ) {
						$this->dieWithError( 'apierror-badip', 'invalidip' );
					} else {
						$this->dieUsage( 'IP or range is invalid', 'invalidip' );
					}
				}

				$this->addFields( [
					'cuc_user_text', 'cuc_timestamp', 'cuc_ip', 'cuc_agent' ] );

				$res = $this->select( __METHOD__ );
				$result = $this->getResult();

				$users = [];
				foreach ( $res as $row ) {
					$user = $row->cuc_user_text;
					$ip = $row->cuc_ip;
					$agent = $row->cuc_agent;

					if ( !isset( $users[$user] ) ) {
						$users[$user]['end'] = wfTimestamp( TS_ISO_8601, $row->cuc_timestamp );
						$users[$user]['editcount'] = 1;
						$users[$user]['ips'][] = $ip;
						$users[$user]['agents'][] = $agent;
					} else {
						$users[$user]['start'] = wfTimestamp( TS_ISO_8601, $row->cuc_timestamp );
						$users[$user]['editcount']++;
						if ( !in_array( $ip, $users[$user]['ips'] ) ) {
							$users[$user]['ips'][] = $ip;
						}
						if ( !in_array( $agent, $users[$user]['agents'] ) ) {
							$users[$user]['agents'][] = $agent;
						}
					}
				}

				$resultUsers = [];
				foreach ( $users as $userName => $userData ) {
					$userData['name'] = $userName;
					$result->setIndexedTagName( $userData['ips'], 'ip' );
					$result->setIndexedTagName( $userData['agents'], 'agent' );

					$resultUsers[] = $userData;
				}

				CheckUser::addLogEntry( $log_type, 'ip', $target, $reason );
				$result->addValue( [
					'query', $this->getModuleName() ], 'ipusers', $resultUsers );
				$result->addIndexedTagName( [
					'query', $this->getModuleName(), 'ipusers' ], 'user' );
				break;

			default:
				if ( is_callable( [ $this, 'dieWithError' ] ) ) {
					$this->dieWithError( 'apierror-checkuser-invalidmode', 'invalidmode' );
				} else {
					$this->dieUsage( 'Invalid request mode', 'invalidmode' );
				}
		}
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		return [
			'request'  => [
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_TYPE => [
					'userips',
					'edits',
					'ipusers',
				]
			],
			'target'   => [
				ApiBase::PARAM_REQUIRED => true,
			],
			'reason'   => null,
			'limit'    => [
				ApiBase::PARAM_DFLT => 1000,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN  => 1,
				ApiBase::PARAM_MAX  => 500,
				ApiBase::PARAM_MAX2 => 5000,
			],
			'timecond' => [
				ApiBase::PARAM_DFLT => '-2 weeks'
			],
			'xff'      => null,
		];
	}

	/**
	 * @see ApiBase::getExamplesMessages()
	 * @return array
	 */
	protected function getExamplesMessages() {
		return [
			'action=query&list=checkuser&curequest=userips&cutarget=Jimbo_Wales'
				=> 'apihelp-query+checkuser-example-1',
			'action=query&list=checkuser&curequest=edits&cutarget=127.0.0.1/16&xff=1&cureason=Some_check'
				=> 'apihelp-query+checkuser-example-2',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Extension:CheckUser#API';
	}

	public function needsToken() {
		return 'csrf';
	}
}
