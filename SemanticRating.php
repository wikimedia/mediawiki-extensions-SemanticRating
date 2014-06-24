<?php
/*
 * Copyright (c) 2013 The MITRE Corporation
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

if (!defined('MEDIAWIKI')) {
	die('<b>Error:</b> This file is part of a MediaWiki extension and cannot be run standalone.');
}

if (version_compare($wgVersion, '1.21', 'lt')) {
	die('<b>Error:</b> This version of SemanticRating is only compatible with MediaWiki 1.21 or above.');
}

if (!defined('SF_VERSION')) {
	die('<b>Error:</b> SemanticRating is a Semantic Forms extension so must be included after Semantic Forms.');
}

if (version_compare(SF_VERSION, '2.5.2', 'lt')) {
	die('<b>Error:</b> This version of SemanticRating is only compatible with Semantic Forms 2.5.2 or above.');
}

$wgExtensionCredits['semantic'][] = array (
	'name' => 'SemanticRating',
	'version' => '1.3',
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Cindy.cicalese Cindy Cicalese]'
	),
	'descriptionmsg' => 'semanticrating-desc',
	'url' => 'https://www.mediawiki.org/wiki/Extension:Semantic_Rating'
);

// Special thanks to
// [https://www.mediawiki.org/wiki/User:Bernadette Bernadette Clemente]
// for the original idea that inspired this extension and to Kelly Hatfield
// for an early implementation of this extension.

$wgAutoloadClasses['SemanticRating'] =
	__DIR__ . '/SemanticRating.class.php';

$wgMessagesDirs['SemanticRating'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['SemanticRating'] =
	__DIR__ . '/SemanticRating.i18n.php';

$wgExtensionMessagesFiles['SemanticRatingMagic'] =
	__DIR__ . '/SemanticRating.i18n.magic.php';

$wgResourceModules['ext.SemanticRating'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'SemanticRating',
	'scripts' => 'scripts/SemanticRating.js'
);

$wgHooks['ParserFirstCallInit'][] = 'efSemanticRatingParserFunction_Setup';

function efSemanticRatingParserFunction_Setup (&$parser) {
	global $SemanticRating_Parse;
	if (!isset($SemanticRating_Parse)) {
		$SemanticRating_Parse = true;
	}
	$parser->setFunctionHook('rating', 'renderRating');
	global $sfgFormPrinter;
	$sfgFormPrinter->setInputTypeHook('rating', 'editRating', array());
	return true;
}

$SemanticRating_ImagePath = $wgServer . $wgScriptPath .
	"/extensions/SemanticRating/images/";

function renderRating($parser, $input) {
	global $SemanticRating_ImagePath;
	$instance = new SemanticRating;
	$output = $instance->renderRating($input, $SemanticRating_ImagePath);
	global $SemanticRating_Parse;
	if ($SemanticRating_Parse) {
		$output = array($parser->insertStripItem($output, $parser->mStripState),
			'noparse' => false, 'isHTML' => true);
	}
	return $output;
}

function editRating($cur_value, $input_name, $is_mandatory, $is_disabled,
	$field_args) {
	global $wgOut, $SemanticRating_ImagePath;
	$wgOut->addModules('ext.SemanticRating');
	$instance = new SemanticRating;
	$output = $instance->editRating($cur_value, $input_name,
		$SemanticRating_ImagePath);
	return array($output, null);
}
