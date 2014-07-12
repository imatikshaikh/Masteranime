<?php
if (empty($content) || empty($display)) {
    echo 'empty';
} else {
    Header('Content-type: text/xml');
    switch ($display) {
        case 'anime':
            $xml = new SimpleXMLElement('<xml/>');
            foreach ($content as $c) {
                $track = $xml->addChild('anime');
                $track->addChild('id', $c->id);
                $track->addChild('name', htmlspecialchars($c->name));
            }
            echo $xml->asXML();
            break;
        case 'episodes':
            $xml = new SimpleXMLElement('<xml/>');
            foreach ($content as $c) {
                $track = $xml->addChild('episode');
                $track->addChild('id', $c);
            }
            echo $xml->asXML();
            break;
        case 'episode':
            $xml = new SimpleXMLElement('<xml/>');
            foreach ($content as $c) {
                $track = $xml->addChild('mirror');
                $track->addChild('url', htmlspecialchars($c->src));
                $track->addChild('host', $c->host);
                $track->addChild('quality', $c->quality);
            }
            echo $xml->asXML();
            break;
        default:
            echo 'empty';
            break;
    }
}

