<?php
use Goutte\Client;

if (isset($mirror) && !empty($mirror)) {
    $client = new Client();
    $crawler = $client->request('GET', $mirror->src);
    echo $crawler->html();
} else {
    echo "Required mirror wasn't found!";
}