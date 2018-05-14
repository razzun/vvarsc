CollapsibleVector
=========

Vector provides enhancements to the Vector skin

This extension requires MediaWiki 1.25+.

Example LocalSettings.php additions

require_once( "$IP/extensions/CollapsibleVector/CollapsibleVector.php" );

Before configuring this extension, see CollapsibleVector.php and become familiar with the initial state and structure of the
$wgCollapsibleVector configuration variable. Essentially it's an array of arrays, keyed by feature name, each containing
global and user keys with boolean values. "global" indicates that it should be turned on for everyone always, while
user indicates that users should be allowed to turn it on or off in their user preferences.

To enable a preference by default but still allow users to disable it in preferences, use something like...

$wgDefaultUserOptions['collapsiblevector-collapsiblenav'] = 1;
