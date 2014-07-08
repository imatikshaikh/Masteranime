<?php
set_time_limit(600);

header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');

echo '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel>';
echo '<title>Masterani - Stream anime in HD</title>
		<link>http://www.masterani.me/</link>
		<description>Masteranime allows you to stream anime at high quality for desktop, tablet and mobile users! We also offer cool features like auto-updating your own MyAnimeList!</description>
		<item>
			<title><![CDATA[Masterani - Stream anime in HD]]></title>
			<link>http://www.masterani.me/</link>
			<description><![CDATA[Masteranime allows you to stream anime at high quality for desktop, tablet and mobile users! We also offer cool features like auto-updating your own MyAnimeList!]]></description>
		</item>
		<item>
			<title><![CDATA[Masterani - Latest anime]]></title>
			<link>http://www.masterani.me/anime/latest</link>
			<description><![CDATA[Latest anime on Mastarani!]]></description>
		</item>
		<item>
			<title><![CDATA[Masterani - Animelist]]></title>
			<link>http://www.masterani.me/anime</link>
			<description><![CDATA[All anime available at masteranime!]]></description>
		</item>';

$animes = Anime::all();
if (count($animes) > 0) {
    foreach ($animes as $anime) {
        echo '<item><title><![CDATA[Masterani - ' . $anime->name . ']]></title><link>http://www.masterani.me/' . $anime->id . '/' . str_replace(array(' ', '/'), '_', $anime->name) . '</link><description><![CDATA[Watch / Stream ' . $anime->name . ' subbed in HD on desktop, tablet and phone at Masterani]]></description></item>';
        if ($anime->english_name != null) {
            echo '<item><title><![CDATA[Masterani - ' . $anime->english_name . ']]></title><link>http://www.masterani.me/' . $anime->id . '/' . str_replace(array(' ', '/'), '_', $anime->english_name) . '</link><description><![CDATA[Watch / Stream ' . $anime->english_name . ' subbed in HD on desktop, tablet and phone at Masterani]]></description></item>';
        } else if ($anime->name_synonym_2 != null) {
            echo '<item><title><![CDATA[Masterani - ' . $anime->name_synonym_2 . ']]></title><link>http://www.masterani.me/' . $anime->id . '/' . str_replace(array(' ', '/'), '_', $anime->name_synonym_2) . '</link><description><![CDATA[Watch / Stream ' . $anime->name_synonym_2 . ' subbed in HD on desktop, tablet and phone at Masterani]]></description></item>';
        }
    }
}
echo '</channel></rss>';