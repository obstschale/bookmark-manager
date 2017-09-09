<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{

    /**
     * Update changelog.
     *
     * Add an entry to the Bookmark Manager CHANGELOG.md file.
     *
     * @param string $addition The text to add to the change log.
     *
     * @param array  $options
     *
     * @option type Type of change is prepended on text. Available shortcuts are:
     *              f => [Feature],
     *              e => [Enhancement]
     * @return \Robo\Result
     */
    public function changed(
        $addition,
        $options = [
            'type|t' => '',
        ]
    ) {
        switch ( $options[ 'type' ] ) {
            case 'f':
                $options[ 'type' ] = 'Feature';
                break;

            case 'e':
                $options[ 'type' ] = 'Enhancement';
                break;
        }

        if ( ! empty( $options[ 'type' ] ) ) {
            $options[ 'type' ] = '[' . ucfirst( strtolower( $options[ 'type' ] ) ) . '] ';
        }
        $addition = $options[ 'type' ] . $addition;

        return $this->taskChangelog()->version( 'Upcoming' )->change( $addition )->run();

    }


    /**
     * Update the version of Bookmark Manager.
     *
     * @param string $version The new verison for plugin.
     *                        Defaults to the next minor (bugfix) version after the current
     *                        relelase.
     *
     * @param array  $options
     *
     * @option stage The version stage: dev, alpha, beta or rc. Use empty for stable.
     * @return \Robo\Result
     */
    public function versionBump( $version = '', $options = [ 'stage' => '' ] )
    {
        // If the user did not specify a version, then update the current version.
        if ( empty( $version ) ) {
            $version = $this->incrementVersion( \BookmarkManager\BookmarkManager::VERSION,
                $options[ 'stage' ] );
        }

        return $this->writeVersion( $version );
    }


    /**
     * Write the specified version string back into the BookmarkManager.php and plugin header.
     *
     * @param string $version
     *
     * @return \Robo\Result
     */
    protected function writeVersion( $version )
    {
        $collection = $this->collectionBuilder();

        // Update constant in BookmarkManager.php
        $collection->taskReplaceInFile( __DIR__ . '/app/BookmarkManager.php' )
            ->regex( "#VERSION = '[^']*'#" )
            ->to( "VERSION = '" . $version . "'" );

        // Update version number in plugin header
        // RegEx copied from https://github.com/sindresorhus/semver-regex
        $collection->taskReplaceInFile( __DIR__ . '/bookmark-manager.php' )
            ->regex( "#\bv?(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)\.(?:0|[1-9]\d*)(?:-[\da-z\-]+(?:\.[\da-z\-]+)*)?(?:\+[\da-z\-]+(?:\.[\da-z\-]+)*)?\b#" )
            ->to( $version );

        return $collection;
    }


    /**
     * Advance to the next SemVer version.
     *
     * The behavior depends on the parameter $stage.
     *   - If $stage is empty, then the patch or minor version of $version is incremented
     *   - If $stage matches the current stage in the current version, then add one
     *     to the stage (e.g. alpha3 -> alpha4)
     *   - If $stage does not match the current stage in the current version, then
     *     reset to '1' (e.g. alpha4 -> beta1)
     *
     * @param string $version A SemVer version
     * @param string $stage   dev, alpha, beta, rc or an empty string for stable.
     *
     * @return string
     */
    protected function incrementVersion( $version, $stage = '' )
    {
        $stable             = empty( $stage );
        $versionStageNumber = '0';
        preg_match( '/-([a-zA-Z]*)([0-9]*)/', $version, $match );
        $match              += [ '', '', '' ];
        $versionStage       = $match[ 1 ];
        $versionStageNumber = $match[ 2 ];
        if ( $versionStage != $stage ) {
            $versionStageNumber = 0;
        }
        $version      = preg_replace( '/-.*/', '', $version );
        $versionParts = explode( '.', $version );
        if ( $stable ) {
            $versionParts[ count( $versionParts ) - 1 ]++;
        }
        $version = implode( '.', $versionParts );
        if ( ! $stable ) {
            $version .= '-' . $stage;
            if ( $stage != 'dev' ) {
                $versionStageNumber++;
                $version .= $versionStageNumber;
            }
        }

        return $version;
    }

}
