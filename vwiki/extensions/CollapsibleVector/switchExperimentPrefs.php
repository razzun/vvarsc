<?php

$path = '../..';

if ( getenv( 'MW_INSTALL_PATH' ) !== false ) {
	$path = getenv( 'MW_INSTALL_PATH' );
}

require_once $path . '/maintenance/Maintenance.php';

class SwitchExperimentPrefs extends Maintenance {
	function __construct() {
		parent::__construct();
		$this->addOption( 'pref', 'Preference to set', true, true );
		$this->addOption( 'value', 'Value to set the preference to', true, true );
		$this->mDescription = '
			Set a preference for all users that have the collapsiblevector-noexperiments preference enabled.
		';
	}

	function execute() {
		$dbw = wfGetDB( DB_MASTER );

		$batchSize = 100;
		$total = 0;
		$lastUserID = 0;
		while ( true ) {
			$res = $dbw->select( 'user_properties', [ 'up_user' ],
				[ 'up_property' => 'collapsiblevector-noexperiments', "up_user > $lastUserID" ],
				__METHOD__,
				[ 'LIMIT' => $batchSize ] );
			if ( !$res->numRows() ) {
				break;
			}
			$total += $res->numRows();

			$ids = [];
			foreach ( $res as $row ) {
				$ids[] = $row->up_user;
			}
			$lastUserID = max( $ids );

			foreach ( $ids as $id ) {
				$user = User::newFromId( $id );
				if ( !$user->isLoggedIn() ) {
					continue;
				}
				$user->setOption( $this->getOption( 'pref' ), $this->getOption( 'value' ) );
				$user->saveSettings();
			}

			echo "$total\n";

			wfWaitForSlaves();
		}
		echo "Done\n";
	}
}

$maintClass = 'SwitchExperimentPrefs';
require_once RUN_MAINTENANCE_IF_MAIN;
