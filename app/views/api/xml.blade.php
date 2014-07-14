<?php
if (empty($content) || empty($display) || count($content) <= 0) {
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
                $track->addChild('cover', $c->mal_image);
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
        case 'latest':
            $xml = new SimpleXMLElement('<xml/>');
            foreach ($content as $c) {
                $track = $xml->addChild('episode');
                $track->addChild('id', $c->anime_id);
                $track->addChild('name', htmlspecialchars($c->name));
                $track->addChild('episode', $c->episode);
                if (strpos($c->img, "hummingbird.me") !== false) {
                    $track->addChild('host', 'hummingbird');
                } else {
                    $track->addChild('host', 'masterani');
                }
                $track->addChild('thumbnail', $c->img);
            }
            echo $xml->asXML();
            break;
        default:
            echo 'empty';
            break;
    }
}

