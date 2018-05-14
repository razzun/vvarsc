<?php
/**
 * Hooks for CollapsibleVector extension
 *
 * @file
 * @ingroup Extensions
 */

class CollapsibleVectorHooks {

	/* Protected Static Members */

	protected static $features = [
		'collapsiblenav' => [
			'preferences' => [
				'collapsiblevector-collapsiblenav' => [
					'type' => 'toggle',
					'label-message' => 'collapsiblevector-collapsiblenav-preference',
					'section' => 'rendering/advancedrendering',
				],
			],
			'requirements' => [
				'collapsiblevector-collapsiblenav' => true,
			],
			'modules' => [ 'ext.collapsiblevector.collapsibleNav' ],
		],
		'experiments' => [
			'preferences' => [
				'collapsiblevector-noexperiments' => [
					'type' => 'toggle',
					'label-message' => 'collapsiblevector-noexperiments-preference',
					'section' => 'rendering/advancedrendering',
				],
			],
		],
	];

	/* Static Methods */

	/**
	 * Checks if a certain option is enabled
	 *
	 * This method is public to allow other extensions that use CollapsibleVector to use the
	 * same configuration as CollapsibleVector itself
	 *
	 * @param $name string Name of the feature, should be a key of $features
	 * @return bool
	 */
	public static function isEnabled( $name ) {
		global $wgCollapsibleVectorFeatures, $wgUser;

		// Features with global set to true are always enabled
		if (
			!isset( $wgCollapsibleVectorFeatures[$name] ) || $wgCollapsibleVectorFeatures[$name]['global']
		) {
			return true;
		}
		// Features with user preference control can have any number of preferences
		// to be specific values to be enabled
		if ( $wgCollapsibleVectorFeatures[$name]['user'] ) {
			if ( isset( self::$features[$name]['requirements'] ) ) {
				foreach ( self::$features[$name]['requirements'] as $requirement => $value ) {
					// Important! We really do want fuzzy evaluation here
					if ( $wgUser->getOption( $requirement ) != $value ) {
						return false;
					}
				}
			}
			return true;
		}
		// Features controlled by $wgCollapsibleVectorFeatures with both global and user set to false
		// are awlways disabled
		return false;
	}

	/* Static Methods */

	/**
	 * BeforePageDisplay hook
	 *
	 * Adds the modules to the page
	 *
	 * @param $out OutputPage output page
	 * @param $skin Skin current skin
	 */
	public static function beforePageDisplay( $out, $skin ) {
		if ( $skin instanceof SkinVector ) {
			// Add modules for enabled features
			foreach ( self::$features as $name => $feature ) {
				if ( isset( $feature['modules'] ) && self::isEnabled( $name ) ) {
					$out->addModules( $feature['modules'] );
				}
			}
		}
		return true;
	}

	/**
	 * GetPreferences hook
	 *
	 * Adds Vector-releated items to the preferences
	 *
	 * @param $user User current user
	 * @param $defaultPreferences array list of default user preference controls
	 */
	public static function getPreferences( $user, &$defaultPreferences ) {
		global $wgCollapsibleVectorFeatures;

		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['preferences'] ) &&
				( !isset( $wgCollapsibleVectorFeatures[$name] ) || $wgCollapsibleVectorFeatures[$name]['user'] )
			) {
				foreach ( $feature['preferences'] as $key => $options ) {
					$defaultPreferences[$key] = $options;
				}
			}
		}
		return true;
	}

	/**
	 * ResourceLoaderGetConfigVars hook
	 *
	 * Adds enabled/disabled switches for Vector modules
	 */
	public static function resourceLoaderGetConfigVars( &$vars ) {
		global $wgCollapsibleVectorFeatures;

		$configurations = [];
		foreach ( self::$features as $name => $feature ) {
			if (
				isset( $feature['configurations'] ) &&
				( !isset( $wgCollapsibleVectorFeatures[$name] ) || self::isEnabled( $name ) )
			) {
				foreach ( $feature['configurations'] as $configuration ) {
					global $$wgConfiguration;
					$configurations[$configuration] = $$wgConfiguration;
				}
			}
		}
		if ( count( $configurations ) ) {
			$vars = array_merge( $vars, $configurations );
		}
		return true;
	}

	/**
	 * @param $vars array
	 * @return bool
	 */
	public static function makeGlobalVariablesScript( &$vars ) {
		// Build and export old-style wgVectorEnabledModules object for back compat
		$enabledModules = [];
		foreach ( self::$features as $name => $feature ) {
			$enabledModules[$name] = self::isEnabled( $name );
		}

		$vars['wgCollapsibleVectorEnabledModules'] = $enabledModules;
		return true;
	}
}
