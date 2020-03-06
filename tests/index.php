<?php

require __DIR__ . '/../src/PeerJ/Conversion/JATS.php';
$jats = new \PeerJ\Conversion\JATS;

// load the XML
$document = new DOMDocument;
$document->load(__DIR__ . '/2020-03-02_JATS_Muster_f_MAK.xml', LIBXML_DTDLOAD | LIBXML_DTDVALID | LIBXML_NONET | LIBXML_NOENT);

// convert to HTML
$document = $jats->generateHTML($document);

// find the head element
$xpath = new DOMXPath($document);
$head = $xpath->query('head')->item(0);

// inject script elements
$scripts = array(
    'vendor/jquery/jquery.js',
    'vendor/jquery/jquery-ui.js',
    'vendor/polyfill/a.js',
    'vendor/polyfill/microdata.js',
    'vendor/qunit/qunit.js',
    'js/tests.js',
);

foreach ($scripts as $script) {
    $node = $document->createElement('script');
    $node->setAttribute('src', $script);
    $head->appendChild($node);
}

// inject CSS
$styles = array(
    'jats-preview.css',
    'css/layout.css',
    'vendor/qunit/qunit.css',
);

foreach ($styles as $style) {
    $node = $document->createElement('link');
    $node->setAttribute('rel', 'stylesheet');
    $node->setAttribute('href', $style);
    $head->appendChild($node);
}

header('Content-Type: text/html');
print '<!doctype html>';
print $document->saveHTML($document->documentElement);
