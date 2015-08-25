<?php

class CSClientScript extends CClientScript {

	/**
	 * Version to append to every request
	 */
	public $version = '1';

	/**
	 * Remember to prepare links only once..
	 */
	private $_prepared = false;
	
	/**
	 * Function which makes all dirty work
	 */
	private function prepareLinks() {
		if( $this->_prepared ) {
			return;
		}
		$v = '?v=' . $this->version;
		
		foreach( $this->scriptFiles as &$place ) {
			foreach( $place as &$script ) {
				if( strpos( $script, '?' ) === false ) {
					$script .= $v;
				} else {
					$script = strtr( $script, array( '?'=>$v . $this->getParamSeparator ) );
				}
			}
		}
		$files = array();
		foreach( $this->cssFiles as $url=>$media ) {
			if( strpos( $url, '?' ) === false ) {
				$url .= $v;
			} else {
				$url = strtr( $url, array( '?'=>$v . $this->getParamSeparator ) );
			}
			$files[ $url ] = $media;
		}
		$this->cssFiles = $files;
		$this->_prepared = true;
	}

	/**
	 * Overriden hook
	 */
	public function render( &$output ) {
		if( !$this->hasScripts ) {
			return;
		}

		$this->renderCoreScripts();

		if( !empty( $this->scriptMap ) ) {
			$this->remapScripts();
		}

		$this->unifyScripts();

		/* This line is the only change to default function body */
		$this->prepareLinks();

		$this->renderHead( $output );
		if( $this->enableJavaScript ) {
			$this->renderBodyBegin( $output );
			$this->renderBodyEnd( $output );
		}
	}
}