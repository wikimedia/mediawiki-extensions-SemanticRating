<?php
/*
 * Copyright (c) 2014 The MITRE Corporation
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( '<b>Error:</b> This file is part of a MediaWiki extension and cannot be run standalone.' );
}

if ( version_compare( $GLOBALS['wgVersion'], '1.21', 'lt' ) ) {
	die( '<b>Error:</b> This version of SemanticRating is only compatible with MediaWiki 1.21 or above.' );
}

if ( !defined( 'SF_VERSION' ) ) {
	die( '<b>Error:</b> SemanticRating is a Semantic Forms extension so must be included after Semantic Forms.' );
}

if ( version_compare( SF_VERSION, '2.5.2', 'lt' ) ) {
	die( '<b>Error:</b> This version of SemanticRating is only compatible with Semantic Forms 2.5.2 or above.' );
}

$GLOBALS['wgExtensionCredits']['semantic'][] = array (
	'path' => __FILE__,
	'name' => 'SemanticRating',
	'version' => '2.2',
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Cindy.cicalese Cindy Cicalese]',
		'...'
	),
	'descriptionmsg' => 'semanticrating-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Rating',
	'license-name' => 'MIT'
);

// Special thanks to
// [https://www.mediawiki.org/wiki/User:Bernadette_Clemente Bernadette Clemente]
// for the original idea that inspired this extension and to Kelly Hatfield
// for an early implementation of this extension.

$GLOBALS['wgAutoloadClasses']['SemanticRating'] =
	__DIR__ . '/SemanticRating.class.php';

$GLOBALS['wgAutoloadClasses']['SemanticRatingHtmlRenderer'] =
	__DIR__ . '/SemanticRatingHtmlRenderer.php';

$GLOBALS['wgAutoloadClasses']['SemanticRatingFormInput'] =
	__DIR__ . '/SemanticRatingFormInput.php';

$GLOBALS['wgMessagesDirs']['SemanticRating'] = __DIR__ . '/i18n';
$GLOBALS['wgExtensionMessagesFiles']['SemanticRating'] =
	__DIR__ . '/SemanticRating.i18n.php';

$GLOBALS['wgExtensionMessagesFiles']['SemanticRatingMagic'] =
	__DIR__ . '/SemanticRating.i18n.magic.php';

$GLOBALS['wgResourceModules']['ext.SemanticRating'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'SemanticRating',
	'scripts' => 'scripts/SemanticRating.js'
);

$GLOBALS['wgHooks']['ParserFirstCallInit'][] = 'SemanticRating::setup';
